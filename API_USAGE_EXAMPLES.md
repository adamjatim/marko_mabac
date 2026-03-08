# API & Usage Examples - Pengaturan Bobot Dinamis

## 1. Menghitung Bobot dari Nilai Input

### Basic Usage
```php
use App\Models\BobotKriteria;

// Siapkan array nilai input (dalam controller atau service)
$nilaiInputs = [
    1 => 9,    // K1: Harga Baru
    2 => 5,    // K2: Harga Jual Kembali
    3 => 6,    // K3: Fitur Keamanan
    4 => 5,    // K4: Fitur Kenyamanan
    5 => 2,    // K5: Efisiensi BBM
    6 => 4,    // K6: Performa
    7 => 7,    // K7: Pajak Kendaraan
];

try {
    // Menghitung bobot
    $hasilHitung = BobotKriteria::hitungBobot($nilaiInputs);
    
    // Output:
    // [
    //     1 => ['nilai_input' => 9, 'nilai_penyebut' => 38, 'hasil_bobot' => 0.2368, 'adalah_default' => false],
    //     2 => ['nilai_input' => 5, 'nilai_penyebut' => 38, 'hasil_bobot' => 0.1316, 'adalah_default' => false],
    //     ...
    // ]
    
} catch (Exception $e) {
    // Handle error
    echo "Error: " . $e->getMessage();
}
```

## 2. Menyimpan Bobot ke Database

### Save After Calculation
```php
// Setelah hitungBobot() berhasil
$hasilHitung = BobotKriteria::hitungBobot($nilaiInputs);

// Simpan ke database
BobotKriteria::simpanBobot($hasilHitung);

// Sekarang bobot sudah tersimpan dan akan digunakan di perhitungan MABAC
```

### Save Directly
```php
// Atau simpan langsung dengan array terstruktur
$bobotData = [
    1 => ['nilai_input' => 9, 'nilai_penyebut' => 38, 'hasil_bobot' => 0.2368],
    2 => ['nilai_input' => 5, 'nilai_penyebut' => 38, 'hasil_bobot' => 0.1316],
    // ... kriteria lainnya
];

BobotKriteria::simpanBobot($bobotData);
```

## 3. Mengambil Bobot yang Tersimpan

### Get All Active Bobots
```php
use App\Models\BobotKriteria;

// Ambil semua bobot kriteria aktif
$bobots = BobotKriteria::getActiveBobots();

// Output: ['1' => 0.2368, '2' => 0.1316, '3' => 0.1579, ...]
```

### Get Specific Bobot
```php
// Ambil bobot untuk kriteria spesifik
$bobot = BobotKriteria::find($kriteria_id);

if ($bobot) {
    echo $bobot->hasil_bobot;  // 0.2368
    echo $bobot->nilai_input;  // 9
    echo $bobot->nilai_penyebut; // 38
}
```

## 4. Dalam Controller Perhitungan

### PerhitunganController Usage
```php
use App\Models\BobotKriteria;
use App\Models\Kriteria;

public function calculate(Request $request)
{
    $kriterias = Kriteria::where('is_active', true)->get();
    
    // Ambil bobot dari database
    $bobots = BobotKriteria::getActiveBobots();
    
    $weights = [];
    foreach ($kriterias as $kriteria) {
        if (isset($bobots[$kriteria->id])) {
            // Gunakan bobot dari database
            $weights[$kriteria->id] = (float) $bobots[$kriteria->id];
        } else {
            // Fallback ke bobot default
            $weights[$kriteria->id] = (float) $kriteria->bobot_default;
        }
    }
    
    // Lanjutkan perhitungan dengan $weights yang sudah ditentukan
    // ... rest of calculation
}
```

## 5. Error Handling

### Case 1: Ada Kriteria Kosong
```php
try {
    $nilaiInputs = [
        1 => 9,
        2 => 5,
        3 => null,  // ❌ Kosong
        4 => 5,
        5 => 2,
        6 => 4,
        7 => 7,
    ];
    
    $hasilHitung = BobotKriteria::hitungBobot($nilaiInputs);
    
} catch (Exception $e) {
    // Exception message:
    // "Kriteria yang kosong: Fitur Keamanan. Harap isi semua kriteria 
    //  atau kosongkan semuanya untuk menggunakan nilai default."
    
    echo $e->getMessage();
}
```

### Case 2: Nilai Input Invalid
```php
try {
    $nilaiInputs = [
        1 => 9,
        2 => 5,
        3 => 6,
        4 => 5,
        5 => -2,  // ❌ Nilai negatif
        6 => 4,
        7 => 7,
    ];
    
    $hasilHitung = BobotKriteria::hitungBobot($nilaiInputs);
    
} catch (Exception $e) {
    // Exception message:
    // "Nilai input harus lebih besar dari 0."
    
    echo $e->getMessage();
}
```

### Case 3: Semua Kosong (Valid)
```php
$nilaiInputs = [
    1 => null,
    2 => null,
    3 => null,
    4 => null,
    5 => null,
    6 => null,
    7 => null,
];

$hasilHitung = BobotKriteria::hitungBobot($nilaiInputs);

// Output:
// [
//     1 => ['nilai_input' => null, 'nilai_penyebut' => 1, 'hasil_bobot' => 0.22, 'adalah_default' => true],
//     2 => ['nilai_input' => null, 'nilai_penyebut' => 1, 'hasil_bobot' => 0.14, 'adalah_default' => true],
//     ...
// ]
```

## 6. Reset Bobot ke Default

### Clear All Bobots
```php
// Hapus semua data bobot dari database
BobotKriteria::truncate();

// Sekarang sistem akan kembali menggunakan bobot default
// untuk semua kriteria
```

### Delete Specific Bobot
```php
// Hapus bobot untuk kriteria tertentu
BobotKriteria::where('kriteria_id', 1)->delete();

// Kini Kriteria 1 akan menggunakan bobot default,
// sementara kriteria lain tetap menggunakan bobot yang tersimpan
```

## 7. Relationship Usage

### Through Kriteria Model
```php
use App\Models\Kriteria;

$kriteria = Kriteria::find(1);

// Ambil bobot untuk kriteria ini
$bobot = $kriteria->bobot(); // Returns BobotKriteria or null

if ($bobot) {
    echo "Bobot untuk " . $kriteria->nama . ": " . $bobot->hasil_bobot;
} else {
    echo "Menggunakan bobot default: " . $kriteria->bobot_default;
}
```

### Through BobotKriteria Model
```php
use App\Models\BobotKriteria;

$bobot = BobotKriteria::find(1);

// Ambil kriteria yang berhubungan
$kriteria = $bobot->kriteria;

echo $kriteria->nama; // "Harga Baru"
echo $bobot->hasil_bobot; // 0.2368
```

## 8. Complex Scenario - Weighted Average Perbandingan

```php
use App\Models\BobotKriteria;
use App\Models\Kriteria;
use App\Models\Mobil;

// Ambil 3 mobil terpilih
$mobils = Mobil::whereIn('id', [1, 2, 3])->get();
$kriterias = Kriteria::where('is_active', true)->get();

// Buat score dengan bobot dinamis
$scores = [];

foreach ($mobils as $mobil) {
    $totalScore = 0;
    
    foreach ($kriterias as $kriteria) {
        // Ambil bobot
        $bobot = $kriteria->bobot;
        $bobotValue = $bobot ? $bobot->hasil_bobot : $kriteria->bobot_default;
        
        // Ambil value mobil untuk kriteria ini
        $mobilValue = match ($kriteria->id) {
            1 => $mobil->harga_baru,
            2 => $mobil->harga_jual_kembali,
            3 => $mobil->fitur_keamanan,
            4 => $mobil->fitur_kenyamanan,
            5 => $mobil->efisiensi_bahan_bakar,
            6 => $mobil->performa,
            7 => $mobil->pajak,
        };
        
        // Calculate weighted value
        $totalScore += ($mobilValue * $bobotValue);
    }
    
    $scores[$mobil->id] = $totalScore;
}

// Sort by score descending
arsort($scores); // Highest score first

foreach ($scores as $mobilId => $score) {
    echo "Mobil ID $mobilId: Score $score\n";
}
```

## 9. Testing Example

```php
<?php

namespace Tests\Feature;

use App\Models\BobotKriteria;
use App\Models\Kriteria;
use Tests\TestCase;

class BobotKriteriaTest extends TestCase
{
    public function test_hitung_bobot_valid()
    {
        $nilaiInputs = [1 => 9, 2 => 5, 3 => 6, 4 => 5, 5 => 2, 6 => 4, 7 => 7];
        
        $hasil = BobotKriteria::hitungBobot($nilaiInputs);
        
        $this->assertEquals(0.2368, $hasil[1]['hasil_bobot']);
        $this->assertEquals(0.1316, $hasil[2]['hasil_bobot']);
    }
    
    public function test_hitung_bobot_semua_kosong()
    {
        $nilaiInputs = [
            1 => null, 2 => null, 3 => null, 
            4 => null, 5 => null, 6 => null, 7 => null
        ];
        
        $hasil = BobotKriteria::hitungBobot($nilaiInputs);
        
        // Harus menggunakan default
        $this->assertTrue($hasil[1]['adalah_default']);
    }
    
    public function test_hitung_bobot_sebagian_kosong_error()
    {
        $nilaiInputs = [1 => 9, 2 => 5, 3 => null, 4 => 5, 5 => 2, 6 => 4, 7 => 7];
        
        $this->expectException(\Exception::class);
        BobotKriteria::hitungBobot($nilaiInputs);
    }
    
    public function test_simpan_bobot()
    {
        $bobotData = [
            1 => ['nilai_input' => 9, 'nilai_penyebut' => 38, 'hasil_bobot' => 0.2368],
            2 => ['nilai_input' => 5, 'nilai_penyebut' => 38, 'hasil_bobot' => 0.1316],
            // ... dst
        ];
        
        BobotKriteria::simpanBobot($bobotData);
        
        $this->assertDatabaseHas('bobot_kriterias', [
            'kriteria_id' => 1,
            'hasil_bobot' => 0.2368
        ]);
    }
    
    public function test_get_active_bobots()
    {
        // Setup: simpan beberapa bobot
        BobotKriteria::create([
            'kriteria_id' => 1,
            'nilai_input' => 9,
            'nilai_penyebut' => 38,
            'hasil_bobot' => 0.2368,
        ]);
        
        $bobots = BobotKriteria::getActiveBobots();
        
        $this->assertEquals(0.2368, $bobots[1]);
    }
}
```

## 10. Migration & Setup

```php
// File: database/migrations/2026_03_08_000001_create_bobot_kriterias_table.php

Schema::create('bobot_kriterias', function (Blueprint $table) {
    $table->id();
    $table->foreignId('kriteria_id')->constrained('kriterias')->onDelete('cascade');
    $table->decimal('nilai_input', 8, 2)->nullable();
    $table->decimal('nilai_penyebut', 10, 2)->default(1);
    $table->decimal('hasil_bobot', 5, 4);
    $table->timestamps();
    
    $table->unique('kriteria_id');
});
```

---

**Quick Reference:**

| Method | Deskripsi | Return |
|--------|-----------|--------|
| `BobotKriteria::hitungBobot($array)` | Hitung bobot dari nilai input | `Array` |
| `BobotKriteria::simpanBobot($array)` | Simpan bobot ke database | `void` |
| `BobotKriteria::getActiveBobots()` | Ambil semua bobot aktif | `Array` |
| `BobotKriteria::truncate()` | Hapus semua bobot (reset) | `void` |
| `Kriteria->bobot()` | Ambil bobot relasi | `BobotKriteria\|null` |

---

Dokumentasi ini memberikan contoh lengkap untuk mengintegrasikan bobot dinamis ke dalam aplikasi Anda.
