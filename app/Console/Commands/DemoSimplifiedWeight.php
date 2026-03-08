<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class DemoSimplifiedWeight extends Command
{
    protected $signature = 'demo:simple-weight';
    protected $description = 'Demo sistem bobot yang disederhanakan';

    public function handle()
    {
        $this->info('🎯 DEMO SISTEM BOBOT DINAMIS YANG DISEDERHANAKAN');
        $this->info('');
        
        $kriterias = Kriteria::where('is_active', true)->get();
        
        // Demo 1: Semua kosong
        $this->info('📋 Demo 1: Semua input KOSONG (gunakan default)');
        $request1 = new Request();
        $weights1 = $this->processWeights($request1, $kriterias);
        $this->showWeights($weights1, 'Default');
        $this->info('');
        
        // Demo 2: Input raw numbers 
        $this->info('📋 Demo 2: Input RAW NUMBERS (9,5,6,5,2,4,7)');
        $request2 = new Request();
        $request2->merge([
            'bobot_1' => '9',  'bobot_2' => '5',  'bobot_3' => '6',
            'bobot_4' => '5',  'bobot_5' => '2',  'bobot_6' => '4',  'bobot_7' => '7'
        ]);
        $weights2 = $this->processWeights($request2, $kriterias);
        $this->showWeights($weights2, 'Raw Numbers');
        $this->info('');
        
        // Demo 3: Input campuran
        $this->info('📋 Demo 3: Input CAMPURAN (sebagian diisi, sebagian kosong)');
        $request3 = new Request();
        $request3->merge([
            'bobot_1' => '0.30', 'bobot_2' => '',    'bobot_3' => '0.25',
            'bobot_4' => '',     'bobot_5' => '',    'bobot_6' => '0.15',  'bobot_7' => ''
        ]);
        $weights3 = $this->processWeights($request3, $kriterias);
        $this->showWeights($weights3, 'Mixed');
        $this->info('');
        
        $this->info('✨ KESIMPULAN: Sistem bisa menangani SEMUA skenario!');
        $this->info('✅ Kosongkan semua = default');
        $this->info('✅ Isi semua = normalisasi input');
        $this->info('✅ Isi sebagian = campuran input + default');
        $this->info('✅ Total selalu = 1.0000');
    }
    
    private function processWeights($request, $kriterias)
    {
        $weights = [];
        $inputWeights = [];
        $allEmpty = true;
        
        // Collect all weight inputs
        foreach ($kriterias as $kriteria) {
            $inputValue = $request->input('bobot_' . $kriteria->id);
            
            if ($inputValue !== null && $inputValue !== '') {
                $inputWeights[$kriteria->id] = (float) $inputValue;
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
        
        return $weights;
    }
    
    private function showWeights($weights, $type)
    {
        $kriterias = Kriteria::where('is_active', true)->get()->keyBy('id');
        $total = 0;
        
        foreach ($weights as $id => $weight) {
            $percentage = ($weight * 100);
            $this->info("K{$id} ({$kriterias[$id]->nama}): {$weight} ({$percentage}%)");
            $total += $weight;
        }
        
        $this->info("Total: {$total} ✓");
    }
}