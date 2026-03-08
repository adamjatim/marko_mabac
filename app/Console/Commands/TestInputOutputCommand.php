<?php

namespace App\Console\Commands;

use App\Models\Kriteria;
use App\Models\BobotKriteria;
use Illuminate\Console\Command;

class TestInputOutputCommand extends Command
{
    protected $signature = 'bobot:test-input';
    protected $description = 'Test input dan output bobot untuk debugging';

    public function handle(): int
    {
        $this->info('=== DEBUGGING INPUT & OUTPUT BOBOT ===');

        // Test 1: Ambil semua kriteria aktif
        $this->info('\n1. Testing Kriteria Aktif:');
        $kriterias = Kriteria::where('is_active', true)->get();
        foreach ($kriterias as $k) {
            $this->line("ID: {$k->id}, Nama: {$k->nama}, Default: {$k->bobot_default}");
        }

        // Test 2: Test input array
        $this->info('\n2. Testing Input Array Format:');
        $testInputs = [
            1 => '9',
            2 => '5',
            3 => '6',
            4 => '5',
            5 => '2',
            6 => '4',
            7 => '7',
        ];

        $this->line('Test Inputs:');
        foreach ($testInputs as $id => $value) {
            $this->line("  K{$id}: {$value}");
        }

        // Test 3: Test hitungBobot function
        $this->info('\n3. Testing hitungBobot():');
        try {
            $hasil = BobotKriteria::hitungBobot($testInputs);
            $this->line('Hasil perhitungan:');
            $totalBobot = 0;
            foreach ($hasil as $kriteria_id => $data) {
                $this->line("K{$kriteria_id}: input={$data['nilai_input']}, penyebut={$data['nilai_penyebut']}, bobot={$data['hasil_bobot']}");
                $totalBobot += $data['hasil_bobot'];
            }
            
            $this->line("Total bobot: {$totalBobot}");
            
            if (abs($totalBobot - 1.0) < 0.001) {
                $this->info('✓ Total bobot = 1.0 (CORRECT)');
            } else {
                $this->error('✗ Total bobot != 1.0 (ERROR)');
            }
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }

        // Test 4: Test empty input (should use default)
        $this->info('\n4. Testing Empty Input (Default):');
        $emptyInputs = [
            1 => '',
            2 => '',
            3 => '',
            4 => '',
            5 => '',
            6 => '',
            7 => '',
        ];

        try {
            $hasilDefault = BobotKriteria::hitungBobot($emptyInputs);
            $this->line('Hasil default:');
            foreach ($hasilDefault as $kriteria_id => $data) {
                $isDefault = $data['adalah_default'] ? 'YES' : 'NO';
                $this->line("K{$kriteria_id}: default={$isDefault}, bobot={$data['hasil_bobot']}");
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }

        // Test 5: Test partial input (should error)
        $this->info('\n5. Testing Partial Input (Should Error):');
        $partialInputs = [
            1 => '9',
            2 => '5',
            3 => '', // kosong
            4 => '5',
            5 => '2',
            6 => '4',
            7 => '7',
        ];

        try {
            $hasilPartial = BobotKriteria::hitungBobot($partialInputs);
            $this->error('✗ Tidak seharusnya sampai sini - partial input harus error');
        } catch (\Exception $e) {
            $this->info('✓ Expected error: ' . $e->getMessage());
        }

        // Test 6: Test current saved bobot
        $this->info('\n6. Testing Current Saved Bobot:');
        $savedBobots = BobotKriteria::getActiveBobots();
        if (empty($savedBobots)) {
            $this->line('Tidak ada bobot tersimpan (menggunakan default)');
        } else {
            $this->line('Bobot tersimpan:');
            foreach ($savedBobots as $kriteria_id => $bobot) {
                $this->line("K{$kriteria_id}: {$bobot}");
            }
        }

        $this->info('\n=== DEBUG SELESAI ===');
        return Command::SUCCESS;
    }
}