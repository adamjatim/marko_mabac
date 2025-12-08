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
        $kriterias = Kriteria::all();
        return view('perhitungan.index', ['mobils' => $mobils, 'kriterias' => $kriterias]);
    }

    public function calculate(Request $request)
    {
        $kriterias = Kriteria::all();
        
        // Get selected mobil IDs
        $selected_mobil_ids = $request->input('mobil_ids', []);
        
        // Validate minimum selection
        if (count($selected_mobil_ids) < 2) {
            return redirect()->route('perhitungan.index')
                ->with('error', 'Minimal pilih 2 mobil untuk melakukan perhitungan MABAC');
        }
        
        // Get only selected mobils
        $mobils = Mobil::whereIn('id', $selected_mobil_ids)->get();

        // Get weights from request or use defaults
        $weights = [];
        foreach ($kriterias as $kriteria) {
            $key = 'bobot_' . $kriteria->id;
            $weights[$kriteria->id] = (float) ($request->input($key) ?? $kriteria->bobot_default);
        }

        // Normalize weights to sum = 1
        $weightSum = array_sum($weights);
        if ($weightSum > 0) {
            $weights = array_map(fn($w) => $w / $weightSum, $weights);
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
                    case 5: // Jarak Tempuh
                        $row[$kriteria->id] = $mobil->jarak_tempuh;
                        break;
                    case 6: // Kapasitas Mesin
                        $row[$kriteria->id] = $mobil->kapasitas_mesin;
                        break;
                    case 7: // Pajak
                        $row[$kriteria->id] = $mobil->pajak;
                        break;
                }
            }
            $matrix[$mobil->id] = $row;
        }

        // Step 1: Normalize matrix (min-max to 1-5 scale)
        $normalized = $this->normalizeMatrix($matrix, $kriterias);

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
        foreach ($matrix as $mobil_id => $row) {
            $normalized[$mobil_id] = [];
            foreach ($kriterias as $kriteria) {
                $val = $row[$kriteria->id];
                $min = $min_vals[$kriteria->id];
                $max = $max_vals[$kriteria->id];

                if ($max == $min) {
                    $normalized_val = 3; // middle value
                } else {
                    // Normalize to 0-1
                    $normalized_val = ($val - $min) / ($max - $min);
                    // Scale to 1-5
                    $normalized_val = 1 + ($normalized_val * 4);
                }

                $normalized[$mobil_id][$kriteria->id] = $normalized_val;
            }
        }

        return $normalized;
    }

    private function weightMatrix($normalized, $weights, $criteria_order)
    {
        $weighted = [];
        foreach ($normalized as $mobil_id => $row) {
            $weighted[$mobil_id] = [];
            foreach ($criteria_order as $kriteria_id) {
                $weighted[$mobil_id][$kriteria_id] = $row[$kriteria_id] * $weights[$kriteria_id];
            }
        }
        return $weighted;
    }

    private function calculateBAA($weighted, $kriterias, $criteria_order)
    {
        $baa = [];
        foreach ($criteria_order as $kriteria_id) {
            $kriteria = $kriterias->find($kriteria_id);
            $values = array_column($weighted, $kriteria_id);

            if ($kriteria->tipe === 'benefit') {
                // For benefit: BAA is the minimum
                $baa[$kriteria_id] = min($values);
            } else {
                // For cost: BAA is the maximum
                $baa[$kriteria_id] = max($values);
            }
        }
        return $baa;
    }

    private function calculateQMatrix($weighted, $baa, $kriterias, $criteria_order)
    {
        $qMatrix = [];
        foreach ($weighted as $mobil_id => $row) {
            $qMatrix[$mobil_id] = [];
            foreach ($criteria_order as $kriteria_id) {
                $kriteria = $kriterias->find($kriteria_id);
                if ($kriteria->tipe === 'benefit') {
                    // For benefit: Qi+ = normalized - BAA
                    $qMatrix[$mobil_id][$kriteria_id] = $row[$kriteria_id] - $baa[$kriteria_id];
                } else {
                    // For cost: Qi- = BAA - normalized
                    $qMatrix[$mobil_id][$kriteria_id] = $baa[$kriteria_id] - $row[$kriteria_id];
                }
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
                'score' => (float) $score, // Ensure numeric type for proper sorting
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
