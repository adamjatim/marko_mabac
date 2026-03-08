<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\PerhitunganController;

class TestBrowserFlow extends Command
{
    protected $signature = 'test:browser-flow';
    protected $description = 'Test browser form submission flow';

    public function handle()
    {
        $this->info('🌐 Testing Browser Form Submission Flow');
        $this->info('');

        // Test dengan debug mode
        $this->info('📝 Testing calculation with custom weights:');
        $this->info('Harga Baru: 9, Harga Jual: 5, Fitur Keamanan: 6, dst.');
        $this->info('');

        $this->info('💡 To test in browser, go to:');
        $this->info('http://your-domain/perhitungan');
        $this->info('');
        $this->info('📋 Test steps:');
        $this->info('1. Select at least 2 mobils');
        $this->info('2. Fill weight inputs: 9, 5, 6, 5, 2, 4, 7');
        $this->info('3. Click "Mulai Perhitungan"');
        $this->info('4. Check "Bobot Kriteria" tab in results');
        $this->info('');
        $this->info('🔧 Debug URL (shows weight calculation):');
        $this->info('http://your-domain/perhitungan?debug=1');
        $this->info('(Add &debug=1 to any calculation POST request)');
        $this->info('');
        $this->info('✅ Weight system is ready for testing!');
    }
}
