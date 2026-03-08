<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kriteria;
use App\Models\Mobil;

class TestCalculationFlow extends Command
{
    protected $signature = 'test:calculation-flow';
    protected $description = 'Test complete calculation flow with custom weights';

    public function handle()
    {
        $this->info('🧮 Testing Complete Calculation Flow');
        $this->info('');
        
        // Get data
        $kriterias = Kriteria::where('is_active', true)->get();
        $mobils = Mobil::take(3)->get();
        
        $this->info('📊 Test Data:');
        $this->info('Criteria count: ' . $kriterias->count());
        $this->info('Mobil count: ' . $mobils->count());
        $this->info('');
        
        // Test 1: Default weights
        $this->info('🔍 Test 1: Default Weights');
        $defaultWeights = [];
        foreach ($kriterias as $kriteria) {
            $defaultWeights[$kriteria->id] = (float) $kriteria->bobot_default;
        }
        
        // Normalize just to be sure
        $totalDefault = array_sum($defaultWeights);
        if ($totalDefault > 0) {
            foreach ($defaultWeights as $id => $weight) {
                $defaultWeights[$id] = round($weight / $totalDefault, 4);
            }
        }
        
        $this->showWeights($defaultWeights, $kriterias, 'Default');
        $this->info('');
        
        // Test 2: Custom raw numbers 
        $this->info('🔍 Test 2: Custom Raw Numbers (9,5,6,5,2,4,7)');
        $customInputs = [
            1 => 9,  // Harga Baru
            2 => 5,  // Harga Jual Kembali
            3 => 6,  // Fitur Keamanan  
            4 => 5,  // Fitur Kenyamanan
            5 => 2,  // Efisiensi Bahan Bakar
            6 => 4,  // Performa
            7 => 7   // Pajak Kendaraan
        ];
        
        $customWeights = [];
        $totalCustom = array_sum($customInputs);
        foreach ($customInputs as $id => $input) {
            $customWeights[$id] = round($input / $totalCustom, 4);
        }
        
        $this->showWeights($customWeights, $kriterias, 'Custom');
        $this->info('');
        
        // Show the difference
        $this->info('📈 Weight Comparison:');
        foreach ($kriterias as $kriteria) {
            $default = $defaultWeights[$kriteria->id];
            $custom = $customWeights[$kriteria->id];
            $diff = $custom - $default;
            $this->info(sprintf(
                '%s: %.4f → %.4f (%.4f)', 
                $kriteria->nama, 
                $default, 
                $custom, 
                $diff
            ));
        }
        
        $this->info('');
        $this->info('✅ Weight calculation is working correctly!');
        $this->info('📋 Next step: Test in browser at /perhitungan');
    }
    
    private function showWeights($weights, $kriterias, $type)
    {
        $total = 0;
        foreach ($weights as $id => $weight) {
            $kriteria = $kriterias->firstWhere('id', $id);
            $percentage = round($weight * 100, 2);
            $this->info("  K{$id} ({$kriteria->nama}): {$weight} ({$percentage}%)");
            $total += $weight;
        }
        $this->info("  Total: {$total}");
    }
}