<?php

// Debug script untuk test input dan output
// Run dengan: php artisan tinker < debug_input.php

use App\Models\Kriteria;
use App\Models\BobotKriteria;

echo "=== DEBUGGING INPUT & OUTPUT BOBOT ===\n";

// Test 1: Ambil semua kriteria aktif
echo "\n1. Testing Kriteria Aktif:\n";
$kriterias = Kriteria::where('is_active', true)->get();
foreach ($kriterias as $k) {
    echo "ID: {$k->id}, Nama: {$k->nama}, Default: {$k->bobot_default}\n";
}

// Test 2: Test input array
echo "\n2. Testing Input Array Format:\n";
$testInputs = [
    1 => '9',
    2 => '5',
    3 => '6',
    4 => '5',
    5 => '2',
    6 => '4',
    7 => '7',
];

echo "Test Inputs:\n";
var_dump($testInputs);

// Test 3: Test hitungBobot function
echo "\n3. Testing hitungBobot():\n";
try {
    $hasil = BobotKriteria::hitungBobot($testInputs);
    echo "Hasil perhitungan:\n";
    foreach ($hasil as $kriteria_id => $data) {
        echo "K{$kriteria_id}: input={$data['nilai_input']}, penyebut={$data['nilai_penyebut']}, bobot={$data['hasil_bobot']}\n";
    }
    
    // Test total
    $totalBobot = array_sum(array_column($hasil, 'hasil_bobot'));
    echo "Total bobot: {$totalBobot}\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Test 4: Test empty input (should use default)
echo "\n4. Testing Empty Input (Default):\n";
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
    echo "Hasil default:\n";
    foreach ($hasilDefault as $kriteria_id => $data) {
        echo "K{$kriteria_id}: default={$data['adalah_default']}, bobot={$data['hasil_bobot']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Test 5: Test partial input (should error)
echo "\n5. Testing Partial Input (Should Error):\n";
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
    echo "Tidak seharusnya sampai sini\n";
} catch (Exception $e) {
    echo "Expected error: " . $e->getMessage() . "\n";
}

echo "\n=== DEBUG SELESAI ===\n";