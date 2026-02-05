<?php

namespace App\Services\MABAC;

/**
 * MatrixNormalizer - Base interface for matrix normalization strategies
 * 
 * Allows different normalization algorithms without changing MABAC calculator
 * Strategy Pattern implementation for flexibility
 */
interface MatrixNormalizerInterface
{
    /**
     * Normalize a matrix using specific algorithm
     * 
     * @param array $matrix Raw decision matrix [mobil_id => [kriteria_id => value]]
     * @param \Illuminate\Database\Eloquent\Collection $kriterias Criteria definitions
     * @return array Normalized matrix [mobil_id => [kriteria_id => normalized_value]]
     */
    public function normalize(array $matrix, $kriterias): array;

    /**
     * Get name/description of normalizer
     * 
     * @return string
     */
    public function getName(): string;
}

/**
 * MinMaxNormalizer - Normalize using min-max scaling to 1-5 scale
 * 
 * Formula: normalized = 1 + (value - min) / (max - min) * 4
 * Range: [1, 5]
 * 
 * Best for: When you want to scale all values to a fixed range (1-5)
 */
class MinMaxNormalizer implements MatrixNormalizerInterface
{
    private const SCALE_MIN = 1;
    private const SCALE_MAX = 5;
    private const DEFAULT_VALUE = 3; // Middle value when min == max

    public function normalize(array $matrix, $kriterias): array
    {
        $normalized = [];
        $minMaxValues = $this->getMinMaxValues($matrix, $kriterias);

        foreach ($matrix as $mobilId => $row) {
            $normalized[$mobilId] = [];
            foreach ($kriterias as $kriteria) {
                $normalizedValue = $this->normalizeValue(
                    $row[$kriteria->id],
                    $minMaxValues[$kriteria->id]['min'],
                    $minMaxValues[$kriteria->id]['max']
                );
                $normalized[$mobilId][$kriteria->id] = $normalizedValue;
            }
        }

        return $normalized;
    }

    /**
     * Get min and max values for each criteria
     * 
     * @param array $matrix
     * @param \Illuminate\Database\Eloquent\Collection $kriterias
     * @return array
     */
    private function getMinMaxValues(array $matrix, $kriterias): array
    {
        $minMaxValues = [];

        foreach ($kriterias as $kriteria) {
            $values = array_column($matrix, $kriteria->id);
            $minMaxValues[$kriteria->id] = [
                'min' => min($values),
                'max' => max($values),
            ];
        }

        return $minMaxValues;
    }

    /**
     * Normalize single value to 1-5 scale
     * 
     * @param float|int $value
     * @param float|int $min
     * @param float|int $max
     * @return float
     */
    private function normalizeValue($value, $min, $max): float
    {
        if ($max == $min) {
            return self::DEFAULT_VALUE;
        }

        // Normalize to 0-1 range
        $normalized = ($value - $min) / ($max - $min);

        // Scale to specified range
        return self::SCALE_MIN + ($normalized * (self::SCALE_MAX - self::SCALE_MIN));
    }

    public function getName(): string
    {
        return 'Min-Max Normalization (1-5 Scale)';
    }

    /**
     * Set custom scale range
     * Can be overridden in subclass for different ranges
     * 
     * @return array [min, max]
     */
    protected function getScaleRange(): array
    {
        return [self::SCALE_MIN, self::SCALE_MAX];
    }
}

/**
 * ZScoreNormalizer - Normalize using Z-Score (Standardization)
 * 
 * Formula: normalized = (value - mean) / std_deviation
 * Mean: 0, Standard Deviation: 1
 * 
 * Best for: Statistical analysis, when you want to identify outliers
 * 
 * Note: This is a future implementation template
 */
class ZScoreNormalizer implements MatrixNormalizerInterface
{
    public function normalize(array $matrix, $kriterias): array
    {
        // TODO: Implement Z-Score normalization
        throw new \Exception('ZScoreNormalizer not yet implemented');
    }

    public function getName(): string
    {
        return 'Z-Score Normalization (Standardization)';
    }
}
