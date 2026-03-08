<?php

namespace App\Console\Commands;

use App\Services\BobotKriteriaDebugService;
use Illuminate\Console\Command;

class BobotDebugCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bobot:debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug & test pengaturan bobot kriteria';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('====== DEBUG PENGATURAN BOBOT KRITERIA ======');
        $this->newLine();

        $results = BobotKriteriaDebugService::runAllTests();

        foreach ($results as $testName => $result) {
            $status = $result['success'] ? '✓ PASS' : '✗ FAIL';
            $color = $result['success'] ? 'info' : 'error';
            
            $this->line("<fg=$color>$status</> - <options=bold>$testName</>", verbosity: 2);
            $this->line("  {$result['message']}");
            
            if (isset($result['data'])) {
                foreach ($result['data'] as $key => $value) {
                    if (is_array($value)) {
                        $this->line("  - $key: " . json_encode($value), verbosity: 2);
                    } else {
                        $this->line("  - $key: $value", verbosity: 2);
                    }
                }
            }
            
            $this->newLine();
        }

        // Summary
        $allPass = collect($results)->every(fn($r) => $r['success']);
        
        if ($allPass) {
            $this->info('====== SEMUA TEST BERHASIL ✓ ======');
            return Command::SUCCESS;
        } else {
            $this->error('====== ADA YANG GAGAL ✗ ======');
            return Command::FAILURE;
        }
    }
}
