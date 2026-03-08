<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerhitunganController extends Controller
{
    public function index(): View
    {
        $mobils = Mobil::all();
        $kriterias = Kriteria::where('is_active', true)->get();

        // Get distinct merks and types for filtering
        $merks = Mobil::distinct()->pluck('merk')->sort()->toArray();
        $types = Mobil::distinct()->pluck('tipe')->sort()->toArray();

        return view('perhitungan.index', [
            'mobils' => $mobils,
            'kriterias' => $kriterias,
            'merks' => $merks,
            'types' => $types,
        ]);
    }

    public function calculate(Request $request)
    {
        $kriterias = Kriteria::where('is_active', true)->get();

        // Get selected mobil IDs
        $selected_mobil_ids = $request->input('mobil_ids', []);

        // Validate minimum selection
        if (count($selected_mobil_ids) < 2) {
            return redirect()->route('perhitungan.index')
                ->with('error', 'Minimal pilih 2 mobil untuk melakukan perhitungan MABAC');
        }

        // Get only selected mobils
        $mobils = Mobil::whereIn('id', $selected_mobil_ids)->get();

        // Extract and normalize weights from request
        $weights = [];
        $inputWeights = [];
        $allEmpty = true;
        $rawUserInputs = []; // Store what user actually typed
        
        // Collect all weight inputs
        foreach ($kriterias as $kriteria) {
            $inputValue = $request->input('bobot_' . $kriteria->id);
            
            if ($inputValue !== null && $inputValue !== '') {
                $inputWeights[$kriteria->id] = (float) $inputValue;
                $rawUserInputs[$kriteria->id] = $inputValue; // Store original input
                $allEmpty = false;
            }
        }
        
        // Determine weights based on input
        if ($allEmpty) {
            // Use default weights if all inputs are empty
            foreach ($kriterias as $kriteria) {
                $weights[$kriteria->id] = (float) $kriteria->bobot_default;
            }
        } else {
            // Check if all criteria have values
            $hasPartialInput = count($inputWeights) < count($kriterias);
            
            if ($hasPartialInput) {
                // Some criteria are empty - fill with defaults
                foreach ($kriterias as $kriteria) {
                    if (isset($inputWeights[$kriteria->id])) {
                        $weights[$kriteria->id] = $inputWeights[$kriteria->id];
                    } else {
                        $weights[$kriteria->id] = (float) $kriteria->bobot_default;
                    }
                }
            } else {
                // All criteria have values - use input
                $weights = $inputWeights;
            }
        }
        
        // Normalize weights so they sum to 1.0
        $totalWeight = array_sum($weights);
        if ($totalWeight > 0) {
            foreach ($weights as $id => $weight) {
                $weights[$id] = round($weight / $totalWeight, 4);
            }
        }
        
        // DEBUG: Show what weights are being used
        $debugInfo = [
            'input_received' => [],
            'final_weights' => $weights,
            'total_before_norm' => $totalWeight,
            'total_after_norm' => array_sum($weights),
            'empty_count' => 0,
            'filled_count' => 0
        ];
        
        foreach ($kriterias as $kriteria) {
            $inputValue = $request->input('bobot_' . $kriteria->id);
            $debugInfo['input_received'][$kriteria->id] = [
                'name' => $kriteria->nama,
                'input' => $inputValue,
                'default' => $kriteria->bobot_default,
                'final_weight' => $weights[$kriteria->id]
            ];
            
            if ($inputValue === null || $inputValue === '') {
                $debugInfo['empty_count']++;
            } else {
                $debugInfo['filled_count']++;
            }
        }
        
        // If debug mode, show weights info  
        if ($request->has('debug')) {
            return response()->json($debugInfo);
        }

        // Build decision matrix (mobils x criteria)
        $matrix = [];
        $criteria_order = $kriterias->pluck('id')->toArray();

        foreach ($mobils as $mobil) {
            $row = [];
            foreach ($kriterias as $kriteria) {
                switch ($kriteria->id) {
                    case 1: // Harga Baru
                        $row[$kriteria->id] = $mobil->harga_baru;
                        break;
                    case 2: // Harga Jual Kembali
                        $row[$kriteria->id] = $mobil->harga_jual_kembali;
                        break;
                    case 3: // Fitur Keamanan
                        $row[$kriteria->id] = $mobil->fitur_keamanan;
                        break;
                    case 4: // Fitur Kenyamanan
                        $row[$kriteria->id] = $mobil->fitur_kenyamanan;
                        break;
                    // case 5: // Jarak Tempuh
                    //     $row[$kriteria->id] = $mobil->jarak_tempuh;
                    //     break;
                    // case 6: // Kapasitas Mesin
                    //     $row[$kriteria->id] = $mobil->kapasitas_mesin;
                    //     break;
                    case 5: // Efisiensi Bahan Bakar
                        $row[$kriteria->id] = $mobil->efisiensi_bahan_bakar;
                        break;
                    case 6: // Performa (Horse Power/HP)
                        $row[$kriteria->id] = $mobil->performa;
                        break;
                    case 7: // Pajak
                        $row[$kriteria->id] = $mobil->pajak;
                        break;
                }
            }
            $matrix[$mobil->id] = $row;
        }

        // Step 1: Normalize matrix (min-max to 1-5 scale)
        $normalization = $this->normalizeMatrix($matrix, $kriterias);
        $normalized = $normalization['normalized'];
        $min_max_values = $normalization['min_max'];

        // Step 2: Calculate weighted matrix
        $weighted = $this->weightMatrix($normalized, $weights, $criteria_order);

        // Step 3: Calculate Border Approximation Area
        $baa = $this->calculateBAA($weighted, $kriterias, $criteria_order);

        // Step 4: Calculate Q matrix (distance from BAA)
        $qMatrix = $this->calculateQMatrix($weighted, $baa, $kriterias, $criteria_order);

        // Step 5: Calculate scores and rank
        $results = $this->calculateScores($mobils, $qMatrix);

        return view('perhitungan.hasil', [
            'results' => $results,
            'mobils' => $mobils,
            'kriterias' => $kriterias,
            'weights' => $weights,
            'matrix' => $matrix,
            'normalized' => $normalized,
            'weighted' => $weighted,
            'baa' => $baa,
            'qMatrix' => $qMatrix,
            'min_max_values' => $min_max_values,
            'weightInfo' => [
                'totalInputBeforeNorm' => $totalWeight ?? 1,
                'usedDefault' => $allEmpty ?? false,
                'partialInput' => isset($inputWeights) && !$allEmpty && count($inputWeights) < count($kriterias),
                'allCustom' => isset($inputWeights) && !$allEmpty && count($inputWeights) == count($kriterias),
                'inputCount' => isset($inputWeights) ? count($inputWeights) : 0,
                'userInputs' => $inputWeights ?? [],
                'rawInputs' => $rawUserInputs ?? []
            ]
        ]);
    }

    private function normalizeMatrix($matrix, $kriterias)
    {
        $normalized = [];
        $min_vals = [];
        $max_vals = [];

        // Find min and max for each criteria
        foreach ($kriterias as $kriteria) {
            $values = array_column($matrix, $kriteria->id);
            $min_vals[$kriteria->id] = min($values);
            $max_vals[$kriteria->id] = max($values);
        }

        // Normalize using min-max to 1-5 scale
        // Formula:
        // - Benefit: t_ij = ((x_ij - x_min_j) / (x_max_j - x_min_j)) * 4 + 1
        // - Cost:    t_ij = ((x_max_j - x_ij) / (x_max_j - x_min_j)) * 4 + 1
        foreach ($matrix as $mobil_id => $row) {
            $normalized[$mobil_id] = [];
            foreach ($kriterias as $kriteria) {
                $val = $row[$kriteria->id];
                $min = $min_vals[$kriteria->id];
                $max = $max_vals[$kriteria->id];

                if ($max == $min) {
                    $normalized_val = 3; // middle value (1 + 4/2)
                } else {
                    if ($kriteria->tipe === 'benefit') {
                        // Benefit: semakin tinggi semakin baik
                        $normalized_val = (($val - $min) / ($max - $min)) * 4 + 1;
                    } else {
                        // Cost: semakin rendah semakin baik
                        $normalized_val = (($max - $val) / ($max - $min)) * 4 + 1;
                    }
                }

                $normalized[$mobil_id][$kriteria->id] = round($normalized_val, 4);
            }
        }

        return [
            'normalized' => $normalized,
            'min_max' => [
                'min' => $min_vals,
                'max' => $max_vals,
            ]
        ];
    }

    private function weightMatrix($normalized, $weights, $criteria_order)
    {
        $weighted = [];
        foreach ($normalized as $mobil_id => $row) {
            $weighted[$mobil_id] = [];
            foreach ($criteria_order as $kriteria_id) {
                $weighted[$mobil_id][$kriteria_id] = round($row[$kriteria_id] * $weights[$kriteria_id], 4);
            }
        }
        return $weighted;
    }

    private function calculateBAA($weighted, $kriterias, $criteria_order)
    {
        $baa = [];
        foreach ($criteria_order as $kriteria_id) {
            $values = array_column($weighted, $kriteria_id);

            // BAA = Rata-rata tertimbang dari setiap kriteria (regardless of benefit/cost type)
            // Formula: B_j = (1/m) × Σ(v_ij)
            $baa[$kriteria_id] = round(array_sum($values) / count($values), 4);
        }
        return $baa;
    }

    private function calculateQMatrix($weighted, $baa, $kriterias, $criteria_order)
    {
        $qMatrix = [];
        foreach ($weighted as $mobil_id => $row) {
            $qMatrix[$mobil_id] = [];
            foreach ($criteria_order as $kriteria_id) {
                // Q_ij = v_ij - B_j (sama untuk benefit dan cost)
                // Formula ini berlaku untuk semua tipe kriteria
                $qMatrix[$mobil_id][$kriteria_id] = round($row[$kriteria_id] - $baa[$kriteria_id], 4);
            }
        }
        return $qMatrix;
    }

    private function calculateScores($mobils, $qMatrix)
    {
        $scores = [];
        foreach ($mobils as $mobil) {
            $score = array_sum($qMatrix[$mobil->id] ?? []);
            $scores[] = [
                'mobil' => $mobil,
                'score' => round((float) $score, 4),
            ];
        }

        // Sort by score descending (HIGHEST SCORE FIRST)
        usort($scores, function($a, $b) {
            if ($b['score'] == $a['score']) {
                return 0;
            }
            return ($b['score'] > $a['score']) ? 1 : -1;
        });

        // Add rank based on sorted position
        foreach ($scores as $key => &$item) {
            $item['rank'] = $key + 1;
        }

        return $scores;
    }
}
