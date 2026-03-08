<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DebugBobotInput extends Command
{
    protected $signature = 'debug:bobot-input {--test-input=}';
    protected $description = 'Debug input data for bobot calculation';

    public function handle()
    {
        $testInputParam = $this->option('test-input');

        if ($testInputParam) {
            // Test dengan data dari parameter
            $testInput = json_decode($testInputParam, true);
            $this->info('Testing with parameter input: ' . json_encode($testInput));
        } else {
            // Test dengan data default
            $testInput = [
                1 => '10',
                2 => '20',
                3 => '30',
                4 => '15',
                5 => '25',
                6 => '35',
                7 => '40'
            ];
            $this->info('Testing with default input: ' . json_encode($testInput));
        }

        // Simulate what happens in the controller
        $this->info('');
        $this->info('=== Simulating Controller Input Handling ===');

        // Simulate request->input('nilai_input', [])
        $nilaiInputs = $testInput;
        $this->info('nilaiInputs from request: ' . json_encode($nilaiInputs));

        // Test the calculation
        try {
            $result = \App\Models\BobotKriteria::hitungBobot($nilaiInputs);

            $this->info('');
            $this->info('=== Calculation Results ===');
            $total = 0;
            foreach ($result as $id => $data) {
                $this->info(sprintf(
                    'K%d: input=%s, total=%s, hasil=%.4f (%.2f%%)',
                    $id,
                    $data['nilai_input'],
                    $data['nilai_penyebut'],
                    $data['hasil_bobot'],
                    $data['hasil_bobot'] * 100
                ));
                $total += $data['hasil_bobot'];
            }

            $this->info('');
            $this->info('Total hasil bobot: ' . $total);

            if (abs($total - 1.0) < 0.0001) {
                $this->info('✓ Total is correct (very close to 1.0)');
            } else {
                $this->error('✗ Total is incorrect! Should be 1.0');
            }
        } catch (\Exception $e) {
            $this->error('Calculation failed: ' . $e->getMessage());
        }
    }
}
