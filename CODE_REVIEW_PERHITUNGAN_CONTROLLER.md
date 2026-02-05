# Code Review: PerhitunganController.php

## 📋 Ringkasan Analisis

File `PerhitunganController.php` memiliki **217 baris** dan menangani logika MABAC (Multi-Attributive Border Approximation area Comparison) secara komprehensif. Setelah review, **ditemukan 5 area kunci yang bisa di-refactor menjadi component/utility terpisah** untuk menghindari redundancy dan meningkatkan maintainability.

---

## 🔴 Area Redundancy & Improvement

### 1. **Decision Matrix Building (Lines 48-76)**
**Status:** ⚠️ HIGH PRIORITY REDUNDANCY

```php
// Menggunakan switch statement yang berulang untuk mapping
foreach ($mobils as $mobil) {
    $row = [];
    foreach ($kriterias as $kriteria) {
        switch ($kriteria->id) {
            case 1: $row[$kriteria->id] = $mobil->harga_baru; break;
            case 2: $row[$kriteria->id] = $mobil->harga_jual_kembali; break;
            // ... dan seterusnya
        }
    }
}
```

**Masalah:**
- Hard-coded switch statement tidak scalable
- Setiap kriteria baru memerlukan penambahan case baru
- Logika mapping terikat pada Controller
- Sulit untuk di-reuse di method lain

**Rekomendasi:** 
→ Buat **`MatrixBuilder` Service/Utility Class**

---

### 2. **Criteria Type Logic (Repeated 3x)**
**Status:** 🟠 MEDIUM PRIORITY REDUNDANCY

**Lokasi:**
- Line 141-146 (dalam `calculateBAA`)
- Line 166-171 (dalam `calculateQMatrix`)
- Berpotensi muncul di method lain

```php
if ($kriteria->tipe === 'benefit') {
    // Benefit logic
} else {
    // Cost logic
}
```

**Masalah:**
- Pattern ini muncul di multiple methods
- Sulit untuk modify business logic benefit/cost
- Tidak ada single source of truth untuk rule ini

**Rekomendasi:**
→ Buat **`CriteriaTypeHandler` atau `CriteriaEvaluator` Class**

---

### 3. **Normalization Logic (Lines 111-132)**
**Status:** 🟡 MEDIUM PRIORITY

```php
private function normalizeMatrix($matrix, $kriterias)
{
    // Min-Max normalization to 1-5 scale
    // 23 baris kode yang spesifik untuk satu algoritma
}
```

**Masalah:**
- Jika ada requirement untuk algoritma normalisasi lain (Z-score, dll), harus modify method ini
- Tidak testable secara independen dengan mudah
- Logika scaling hardcoded (1-5)

**Rekomendasi:**
→ Buat **`MatrixNormalizer` Interface dengan implementasi** (Strategy Pattern)

---

### 4. **Weighting Application (Lines 133-142)**
**Status:** 🟢 LOW-MEDIUM PRIORITY

```php
private function weightMatrix($normalized, $weights, $criteria_order)
{
    // Simple multiplication, tapi pattern serupa mungkin digunakan di tempat lain
}
```

**Masalah:**
- Jika ada proses weighting di tempat lain, akan duplikasi
- Bisa abstracted menjadi utility function

**Rekomendasi:**
→ Bisa di-combine dengan `MatrixCalculator` atau Matrix Utility

---

### 5. **BAA Calculation + Q Matrix Calculation (Lines 143-171)**
**Status:** 🔴 HIGH PRIORITY

```php
// Method calculateBAA() - 15 baris
// Method calculateQMatrix() - 25 baris
// Keduanya tightly coupled, sulit ditest separately
```

**Masalah:**
- Keduanya adalah core calculation, harus di-test dengan baik
- Q Matrix calculation bergantung pada BAA, sulit untuk isolate testing
- Logika criteria type juga muncul di sini

**Rekomendasi:**
→ Buat **`MABACCalculator` Class yang mengelola kedua logic**

---

### 6. **Scoring & Ranking (Lines 172-192)**
**Status:** 🟢 LOW PRIORITY

```php
private function calculateScores($mobils, $qMatrix)
{
    // Sorting dan ranking logic
}
```

**Masalah:**
- Kecil dan spesifik, tapi bisa di-isolate untuk testability

**Rekomendasi:**
→ Bisa di-combine dalam Result Formatter service

---

## ✅ Proposed Refactoring Structure

```
app/
├── Http/Controllers/
│   └── PerhitunganController.php (SLIM - hanya orchestrate)
│
├── Services/
│   ├── MABAC/
│   │   ├── MatrixBuilder.php (NEW)
│   │   ├── MatrixNormalizer.php (NEW - Interface)
│   │   │   ├── MinMaxNormalizer.php (NEW - Implementation)
│   │   │   └── ZScoreNormalizer.php (FUTURE)
│   │   ├── MABACCalculator.php (NEW)
│   │   ├── CriteriaTypeHandler.php (NEW)
│   │   └── ResultFormatter.php (NEW)
│   │
│   └── DecisionMatrixCalculator.php (NEW - Facade/Orchestrator)
│
└── Exceptions/
    └── MABACException.php (NEW)
```

---

## 📦 Refactoring Priority

| Priority | Component | Effort | Impact | Reusability |
|----------|-----------|--------|--------|------------|
| 1 | MatrixBuilder | 🟢 Low | 🔴 High | ⭐⭐⭐⭐⭐ |
| 2 | CriteriaTypeHandler | 🟢 Low | 🟠 Medium | ⭐⭐⭐⭐ |
| 3 | MABACCalculator | 🟠 Medium | 🔴 High | ⭐⭐⭐⭐ |
| 4 | MatrixNormalizer | 🟠 Medium | 🟠 Medium | ⭐⭐⭐ |
| 5 | ResultFormatter | 🟢 Low | 🟢 Low | ⭐⭐ |

---

## 🎯 Code Examples - Before & After

### Example 1: Decision Matrix Building

**BEFORE (Current - Hard-coded):**
```php
foreach ($mobils as $mobil) {
    $row = [];
    foreach ($kriterias as $kriteria) {
        switch ($kriteria->id) {
            case 1: $row[$kriteria->id] = $mobil->harga_baru; break;
            case 2: $row[$kriteria->id] = $mobil->harga_jual_kembali; break;
            // ... 5 more cases
        }
    }
    $matrix[$mobil->id] = $row;
}
```

**AFTER (With MatrixBuilder):**
```php
$matrixBuilder = app(MatrixBuilder::class);
$matrix = $matrixBuilder->build($mobils, $kriterias);
```

**Service Class:**
```php
// app/Services/MABAC/MatrixBuilder.php
namespace App\Services\MABAC;

class MatrixBuilder
{
    private const ATTRIBUTE_MAPPING = [
        1 => 'harga_baru',
        2 => 'harga_jual_kembali',
        3 => 'fitur_keamanan',
        4 => 'fitur_kenyamanan',
        5 => 'jarak_tempuh',
        6 => 'kapasitas_mesin',
        7 => 'pajak',
    ];

    public function build($mobils, $kriterias): array
    {
        $matrix = [];
        foreach ($mobils as $mobil) {
            $row = [];
            foreach ($kriterias as $kriteria) {
                $attribute = self::ATTRIBUTE_MAPPING[$kriteria->id] ?? null;
                if ($attribute) {
                    $row[$kriteria->id] = $mobil->{$attribute};
                }
            }
            $matrix[$mobil->id] = $row;
        }
        return $matrix;
    }
}
```

---

### Example 2: Criteria Type Handler

**BEFORE (Current - Repeated logic):**
```php
// In calculateBAA()
if ($kriteria->tipe === 'benefit') {
    $baa[$kriteria_id] = min($values);
} else {
    $baa[$kriteria_id] = max($values);
}

// In calculateQMatrix() - SAME LOGIC REPEATED
if ($kriteria->tipe === 'benefit') {
    $qMatrix[$mobil_id][$kriteria_id] = $row[$kriteria_id] - $baa[$kriteria_id];
} else {
    $qMatrix[$mobil_id][$kriteria_id] = $baa[$kriteria_id] - $row[$kriteria_id];
}
```

**AFTER (With CriteriaTypeHandler):**
```php
// app/Services/MABAC/CriteriaTypeHandler.php
namespace App\Services\MABAC;

enum CriteriaType: string
{
    case BENEFIT = 'benefit';
    case COST = 'cost';
}

class CriteriaTypeHandler
{
    public function isBenefit(string $type): bool
    {
        return $type === CriteriaType::BENEFIT->value;
    }

    public function calculateBAA(array $values, string $type): float
    {
        return $this->isBenefit($type) ? min($values) : max($values);
    }

    public function calculateQ(float $normalized, float $baa, string $type): float
    {
        return $this->isBenefit($type) 
            ? $normalized - $baa
            : $baa - $normalized;
    }
}
```

---

### Example 3: Refactored Controller

**AFTER (Clean & Slim):**
```php
namespace App\Http\Controllers;

use App\Services\MABAC\DecisionMatrixCalculator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PerhitunganController extends Controller
{
    public function __construct(
        private DecisionMatrixCalculator $calculator
    ) {}

    public function calculate(Request $request)
    {
        $request->validate([
            'mobil_ids' => 'required|array|min:2',
            'mobil_ids.*' => 'exists:mobils,id',
        ]);

        $kriterias = Kriteria::all();
        $mobils = Mobil::whereIn('id', $request->input('mobil_ids'))->get();
        $weights = $this->extractAndNormalizeWeights($request, $kriterias);

        try {
            $results = $this->calculator->calculate($mobils, $kriterias, $weights);
        } catch (MABACException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return view('perhitungan.hasil', [
            'results' => $results,
            'mobils' => $mobils,
            'kriterias' => $kriterias,
        ]);
    }

    private function extractAndNormalizeWeights(Request $request, $kriterias): array
    {
        $weights = [];
        foreach ($kriterias as $kriteria) {
            $weights[$kriteria->id] = (float) ($request->input("bobot_{$kriteria->id}") ?? $kriteria->bobot_default);
        }

        $sum = array_sum($weights);
        return $sum > 0 ? array_map(fn($w) => $w / $sum, $weights) : $weights;
    }
}
```

---

## 🧪 Testing Benefits

Dengan refactoring ini, testing menjadi lebih mudah:

```php
// test/Unit/Services/MABAC/MatrixBuilderTest.php
class MatrixBuilderTest extends TestCase
{
    public function test_builds_matrix_correctly()
    {
        $builder = new MatrixBuilder();
        $mobils = Mobil::factory(2)->create();
        $kriterias = Kriteria::all();

        $matrix = $builder->build($mobils, $kriterias);

        $this->assertCount(2, $matrix);
        $this->assertArrayHasKey($mobils[0]->id, $matrix);
    }
}

// test/Unit/Services/MABAC/CriteriaTypeHandlerTest.php
class CriteriaTypeHandlerTest extends TestCase
{
    public function test_benefit_baa_returns_minimum()
    {
        $handler = new CriteriaTypeHandler();
        $baa = $handler->calculateBAA([1, 5, 3], 'benefit');
        
        $this->assertEquals(1, $baa);
    }

    public function test_cost_baa_returns_maximum()
    {
        $handler = new CriteriaTypeHandler();
        $baa = $handler->calculateBAA([1, 5, 3], 'cost');
        
        $this->assertEquals(5, $baa);
    }
}
```

---

## 📊 Summary of Changes

| Aspek | Before | After |
|-------|--------|-------|
| Controller Lines | 217 | ~80-100 |
| Methods dalam Controller | 6 | 3 |
| Code Reusability | 🔴 Low | 🟢 High |
| Testability | 🟠 Medium | 🟢 High |
| Maintainability | 🟠 Medium | 🟢 High |
| Scalability | 🔴 Low | 🟢 High |

---

## 🚀 Implementation Steps

### Phase 1: Create Core Services (2-3 jam)
1. `MatrixBuilder.php` - Extract matrix building logic
2. `CriteriaTypeHandler.php` - Extract criteria type logic
3. Unit tests untuk kedua service

### Phase 2: Create Calculator Service (2 jam)
1. `MatrixNormalizer.php` - Extract normalization logic
2. `MABACCalculator.php` - Orchestrate calculation steps

### Phase 3: Create Facade/Orchestrator (1 jam)
1. `DecisionMatrixCalculator.php` - Main service facade
2. Create service binding di `AppServiceProvider.php`

### Phase 4: Refactor Controller (1 jam)
1. Update `PerhitunganController` untuk menggunakan services
2. Simplify methods dan improve request validation

### Phase 5: Testing (2-3 jam)
1. Tulis comprehensive unit tests
2. Integration tests untuk controller

**Total Estimasi: 8-10 jam**

---

## 💡 Additional Recommendations

1. **Configuration**: Pindahkan constant values (1-5 scale) ke config file
2. **Logging**: Tambah logging untuk debugging calculation flow
3. **Validation**: Add validation di service level, bukan hanya controller
4. **Error Handling**: Create custom exception classes
5. **Documentation**: Add PHPDoc untuk semua public methods
6. **Performance**: Cache normalization results jika ada repeated calculations

---

## 🎓 Design Patterns Applied

- **Service Layer Pattern** - Business logic di services, bukan controller
- **Strategy Pattern** - MatrixNormalizer interface untuk algoritma yang berbeda
- **Facade Pattern** - DecisionMatrixCalculator sebagai single entry point
- **Dependency Injection** - Services di-inject, bukan instantiate langsung
- **Single Responsibility** - Setiap service punya satu tujuan jelas
