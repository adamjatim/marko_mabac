<?php

namespace App\Services\MABAC;

use Illuminate\Support\Collection;

/**
 * MABACCalculator - Main service for MABAC calculation
 * 
 * This service orchestrates all steps of the MABAC algorithm:
 * 1. Build decision matrix from alternatives and criteria
 * 2. Normalize the matrix
 * 3. Apply weights
 * 4. Calculate Border Approximation Area (BAA)
 * 5. Calculate Q matrix (distance from BAA)
 * 6. Rank alternatives
 * 
 * Responsibility: Coordinates all MABAC algorithm steps in correct order
 */
class MABACCalculator
{
    private MatrixBuilder $matrixBuilder;
    private MatrixNormalizerInterface $normalizer;
    private CriteriaTypeHandler $typeHandler;

    public function __construct(
        MatrixBuilder $matrixBuilder,
        MatrixNormalizerInterface $normalizer,
        CriteriaTypeHandler $typeHandler
    ) {
        $this->matrixBuilder = $matrixBuilder;
        $this->normalizer = $normalizer;
        $this->typeHandler = $typeHandler;
    }

    /**
     * Execute complete MABAC calculation
     * 
     * @param Collection $mobils Selected mobils to evaluate
     * @param Collection $kriterias All available criteria
     * @param array $weights Normalized weights [kriteria_id => weight]
     * @return array Ranked results with scores
     * 
     * @throws \Exception If calculation fails
     * 
     * @example
     * $calculator = app(MABACCalculator::class);
     * $results = $calculator->calculate(
     *     Mobil::whereIn('id', $selected_ids)->get(),
     *     Kriteria::all(),
     *     [1 => 0.2, 2 => 0.3, 3 => 0.25, 4 => 0.15, 5 => 0.1]
     * );
     * 
     * Result:
     * [
     *     ['mobil' => Mobil object, 'score' => 3.5, 'rank' => 1],
     *     ['mobil' => Mobil object, 'score' => 2.8, 'rank' => 2],
     * ]
     */
    public function calculate(Collection $mobils, Collection $kriterias, array $weights): array
    {
        try {
            // Step 1: Validate inputs
            $this->validate($mobils, $kriterias, $weights);

            // Step 2: Build decision matrix
            $matrix = $this->buildMatrix($mobils, $kriterias);

            // Step 3: Normalize matrix
            $normalized = $this->normalizeMatrix($matrix, $kriterias);

            // Step 4: Apply weights
            $weighted = $this->applyWeights($normalized, $weights, $kriterias);

            // Step 5: Calculate BAA
            $baa = $this->calculateBAA($weighted, $kriterias);

            // Step 6: Calculate Q matrix
            $qMatrix = $this->calculateQMatrix($weighted, $baa, $kriterias);

            // Step 7: Calculate scores and rank
            return $this->rankResults($mobils, $qMatrix);
        } catch (\Exception $e) {
            throw new MABACException("MABAC calculation failed: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Validate inputs before calculation
     * 
     * @param Collection $mobils
     * @param Collection $kriterias
     * @param array $weights
     * @return void
     * @throws \InvalidArgumentException
     */
    private function validate(Collection $mobils, Collection $kriterias, array $weights): void
    {
        if ($mobils->count() < 2) {
            throw new \InvalidArgumentException('Minimum 2 mobils required for MABAC calculation');
        }

        if ($kriterias->isEmpty()) {
            throw new \InvalidArgumentException('At least one criteria is required');
        }

        $weightSum = array_sum($weights);
        if (abs($weightSum - 1.0) > 0.01) { // Allow small floating point errors
            throw new \InvalidArgumentException("Weights must sum to 1, got {$weightSum}");
        }

        // Validate all criteria have weight
        foreach ($kriterias as $kriteria) {
            if (!isset($weights[$kriteria->id])) {
                throw new \InvalidArgumentException("Missing weight for criteria {$kriteria->id}");
            }
        }
    }

    /**
     * Step 1: Build decision matrix
     * 
     * @param Collection $mobils
     * @param Collection $kriterias
     * @return array
     */
    private function buildMatrix(Collection $mobils, Collection $kriterias): array
    {
        return $this->matrixBuilder->build($mobils, $kriterias);
    }

    /**
     * Step 2: Normalize matrix using configured normalizer
     * 
     * @param array $matrix
     * @param Collection $kriterias
     * @return array
     */
    private function normalizeMatrix(array $matrix, Collection $kriterias): array
    {
        return $this->normalizer->normalize($matrix, $kriterias);
    }

    /**
     * Step 3: Apply weights to normalized matrix
     * 
     * Weighted value = normalized value × weight
     * 
     * @param array $normalized
     * @param array $weights
     * @param Collection $kriterias
     * @return array
     */
    private function applyWeights(array $normalized, array $weights, Collection $kriterias): array
    {
        $weighted = [];
        $criteriaOrder = $kriterias->pluck('id')->toArray();

        foreach ($normalized as $mobilId => $row) {
            $weighted[$mobilId] = [];
            foreach ($criteriaOrder as $kriteriaId) {
                $weighted[$mobilId][$kriteriaId] = $row[$kriteriaId] * $weights[$kriteriaId];
            }
        }

        return $weighted;
    }

    /**
     * Step 4: Calculate Border Approximation Area
     * 
     * BAA represents the threshold for each criteria:
     * - Benefit: minimum weighted value (reference point to exceed)
     * - Cost: maximum weighted value (reference point to stay below)
     * 
     * @param array $weighted
     * @param Collection $kriterias
     * @return array [kriteria_id => baa_value]
     */
    private function calculateBAA(array $weighted, Collection $kriterias): array
    {
        $baa = [];

        foreach ($kriterias as $kriteria) {
            $values = array_column($weighted, $kriteria->id);
            $baa[$kriteria->id] = $this->typeHandler->calculateBAA($values, $kriteria->tipe);
        }

        return $baa;
    }

    /**
     * Step 5: Calculate Q matrix
     * 
     * Q matrix represents distance from BAA for each alternative-criteria pair:
     * - Positive value: better than BAA
     * - Negative value: worse than BAA
     * 
     * @param array $weighted
     * @param array $baa
     * @param Collection $kriterias
     * @return array [mobil_id => [kriteria_id => q_value]]
     */
    private function calculateQMatrix(array $weighted, array $baa, Collection $kriterias): array
    {
        $qMatrix = [];

        foreach ($weighted as $mobilId => $row) {
            $qMatrix[$mobilId] = [];
            foreach ($kriterias as $kriteria) {
                $qMatrix[$mobilId][$kriteria->id] = $this->typeHandler->calculateQ(
                    $row[$kriteria->id],
                    $baa[$kriteria->id],
                    $kriteria->tipe
                );
            }
        }

        return $qMatrix;
    }

    /**
     * Step 6: Calculate final scores and rank
     * 
     * Final score = sum of all Q values for an alternative
     * Ranking: Higher score = Better alternative
     * 
     * @param Collection $mobils
     * @param array $qMatrix
     * @return array Ranked results
     */
    private function rankResults(Collection $mobils, array $qMatrix): array
    {
        $scores = [];

        foreach ($mobils as $mobil) {
            $score = array_sum($qMatrix[$mobil->id] ?? []);
            $scores[] = [
                'mobil' => $mobil,
                'score' => (float) $score,
            ];
        }

        // Sort by score descending (highest score first)
        usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);

        // Add ranking
        foreach ($scores as $key => &$item) {
            $item['rank'] = $key + 1;
        }

        return $scores;
    }

    /**
     * Get detailed calculation report (for debugging/analysis)
     * 
     * Returns intermediate results of each calculation step
     * 
     * @param Collection $mobils
     * @param Collection $kriterias
     * @param array $weights
     * @return array Report with all intermediate data
     */
    public function getDetailedReport(Collection $mobils, Collection $kriterias, array $weights): array
    {
        $matrix = $this->buildMatrix($mobils, $kriterias);
        $normalized = $this->normalizeMatrix($matrix, $kriterias);
        $weighted = $this->applyWeights($normalized, $weights, $kriterias);
        $baa = $this->calculateBAA($weighted, $kriterias);
        $qMatrix = $this->calculateQMatrix($weighted, $baa, $kriterias);
        $results = $this->rankResults($mobils, $qMatrix);

        return [
            'matrix' => $matrix,
            'normalized' => $normalized,
            'weighted' => $weighted,
            'baa' => $baa,
            'qMatrix' => $qMatrix,
            'results' => $results,
            'normalizer' => $this->normalizer->getName(),
        ];
    }
}

/**
 * MABACException - Custom exception for MABAC calculation errors
 */
class MABACException extends \Exception
{
}
