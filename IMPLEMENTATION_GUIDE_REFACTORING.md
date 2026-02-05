# Implementasi Refactoring Guide

## 📋 Daftar Service Classes yang Telah Dibuat

Saya telah membuat 4 service files utama yang siap untuk diintegrasikan:

### 1. **MatrixBuilder.php**
- **Lokasi:** `app/Services/MABAC/MatrixBuilder.php`
- **Fungsi:** Membangun decision matrix dari Mobil dan Kriteria
- **Ganti dari:** Switch statement di line 48-76 dari original controller
- **Keuntungan:**
  - Centralized mapping untuk semua kriteria
  - Mudah untuk menambah kriteria baru
  - Testable secara independen

### 2. **CriteriaTypeHandler.php**
- **Lokasi:** `app/Services/MABAC/CriteriaTypeHandler.php`
- **Fungsi:** Menangani logika benefit/cost type
- **Ganti dari:** If-else statements yang terulang di line 141-146 dan line 166-171
- **Keuntungan:**
  - Single source of truth untuk type logic
  - Mudah untuk modify business rules
  - Consistent behavior di semua tempat

### 3. **MatrixNormalizer.php** (Interface + Implementation)
- **Lokasi:** `app/Services/MABAC/MatrixNormalizer.php`
- **Komponen:**
  - `MatrixNormalizerInterface` - Interface untuk normalisasi algorithm
  - `MinMaxNormalizer` - Implementation untuk min-max scaling (current method)
  - `ZScoreNormalizer` - Template untuk future implementation
- **Ganti dari:** Method `normalizeMatrix()` di line 111-132
- **Keuntungan:**
  - Strategy Pattern - bisa switch algorithm tanpa ubah kode lain
  - Extendable untuk normalisasi method lain
  - Easier testing

### 4. **MABACCalculator.php**
- **Lokasi:** `app/Services/MABAC/MABACCalculator.php`
- **Fungsi:** Mengorkestra semua step perhitungan MABAC
- **Ganti dari:** Methods di line 103-192 dari original controller
- **Keuntungan:**
  - Koordinasi semua steps dalam urutan benar
  - Validation terintegrasi
  - Debug report tersedia
  - Exception handling proper

### 5. **REFACTORED_PerhitunganController.php**
- **Lokasi:** `app/Services/MABAC/REFACTORED_PerhitunganController.php` (temporary)
- **Note:** Copy ke file asli setelah testing
- **Ukuran:** ~100 baris vs ~217 baris original
- **Perubahan:**
  - Semua business logic di-move ke services
  - Controller hanya untuk HTTP handling dan orchestration
  - Cleaner, lebih maintainable

---

## 🚀 Implementation Steps

### Step 1: Create Service Directory
```bash
mkdir -p app/Services/MABAC
```

### Step 2: Copy Service Files
Semua files sudah dibuat di workspace, siap di-copy:
- MatrixBuilder.php
- CriteriaTypeHandler.php
- MatrixNormalizer.php
- MABACCalculator.php

### Step 3: Update AppServiceProvider (Register Services)
Tambahkan ke `app/Providers/AppServiceProvider.php`:

```php
<?php

namespace App\Providers;

use App\Services\MABAC\MABACCalculator;
use App\Services\MABAC\MatrixBuilder;
use App\Services\MABAC\MatrixNormalizerInterface;
use App\Services\MABAC\MinMaxNormalizer;
use App\Services\MABAC\CriteriaTypeHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register normalizer
        $this->app->singleton(MatrixNormalizerInterface::class, MinMaxNormalizer::class);

        // Register calculator
        $this->app->singleton(MABACCalculator::class, function ($app) {
            return new MABACCalculator(
                $app->make(MatrixBuilder::class),
                $app->make(MatrixNormalizerInterface::class),
                $app->make(CriteriaTypeHandler::class)
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
```

### Step 4: Update Original PerhitunganController
Replace content dengan code dari `REFACTORED_PerhitunganController.php`

### Step 5: Test Everything
```bash
php artisan test
```

---

## 🧪 Unit Tests

### Test MatrixBuilder

```php
// tests/Unit/Services/MABAC/MatrixBuilderTest.php

namespace Tests\Unit\Services\MABAC;

use App\Models\Kriteria;
use App\Models\Mobil;
use App\Services\MABAC\MatrixBuilder;
use Tests\TestCase;

class MatrixBuilderTest extends TestCase
{
    public function test_builds_matrix_with_correct_dimensions()
    {
        $mobils = Mobil::factory(2)->create();
        $kriterias = Kriteria::all();

        $builder = new MatrixBuilder();
        $matrix = $builder->build($mobils, $kriterias);

        // Should have 2 mobils
        $this->assertCount(2, $matrix);

        // Each mobil should have all criteria
        foreach ($matrix as $mobilId => $row) {
            $this->assertCount($kriterias->count(), $row);
        }
    }

    public function test_maps_attributes_correctly()
    {
        $mobil = Mobil::factory()->create([
            'harga_baru' => 300000000,
            'harga_jual_kembali' => 250000000,
        ]);
        $kriterias = Kriteria::all();

        $builder = new MatrixBuilder();
        $matrix = $builder->build(collect([$mobil]), $kriterias);

        // Verify mappings
        $this->assertEquals(300000000, $matrix[$mobil->id][1]); // Harga Baru
        $this->assertEquals(250000000, $matrix[$mobil->id][2]); // Harga Jual Kembali
    }

    public function test_throws_exception_for_unknown_criteria()
    {
        $this->expectException(\InvalidArgumentException::class);

        $builder = new MatrixBuilder();
        $builder->getAttributeValue(new Mobil(), 999); // Non-existent criteria
    }
}
```

### Test CriteriaTypeHandler

```php
// tests/Unit/Services/MABAC/CriteriaTypeHandlerTest.php

namespace Tests\Unit\Services\MABAC;

use App\Services\MABAC\CriteriaTypeHandler;
use Tests\TestCase;

class CriteriaTypeHandlerTest extends TestCase
{
    private CriteriaTypeHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->handler = new CriteriaTypeHandler();
    }

    public function test_benefit_type_returns_minimum_for_baa()
    {
        $values = [1, 5, 3, 2, 4];
        $baa = $this->handler->calculateBAA($values, 'benefit');

        $this->assertEquals(1, $baa);
    }

    public function test_cost_type_returns_maximum_for_baa()
    {
        $values = [1, 5, 3, 2, 4];
        $baa = $this->handler->calculateBAA($values, 'cost');

        $this->assertEquals(5, $baa);
    }

    public function test_benefit_q_value_calculation()
    {
        $q = $this->handler->calculateQ(4.5, 3.2, 'benefit');
        $this->assertEquals(1.3, $q);
    }

    public function test_cost_q_value_calculation()
    {
        $q = $this->handler->calculateQ(250, 350, 'cost');
        $this->assertEquals(100, $q);
    }

    public function test_validates_criteria_type()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->handler->validate('invalid_type');
    }
}
```

### Test MABACCalculator

```php
// tests/Unit/Services/MABAC/MABACCalculatorTest.php

namespace Tests\Unit\Services\MABAC;

use App\Models\Kriteria;
use App\Models\Mobil;
use App\Services\MABAC\MABACCalculator;
use Tests\TestCase;

class MABACCalculatorTest extends TestCase
{
    public function test_calculate_returns_ranked_results()
    {
        $mobils = Mobil::factory(3)->create();
        $kriterias = Kriteria::all();
        
        $weights = [];
        foreach ($kriterias as $kriteria) {
            $weights[$kriteria->id] = 1 / $kriterias->count();
        }

        $calculator = app(MABACCalculator::class);
        $results = $calculator->calculate($mobils, $kriterias, $weights);

        // Should return 3 results
        $this->assertCount(3, $results);

        // Should have rank field
        foreach ($results as $result) {
            $this->assertArrayHasKey('rank', $result);
            $this->assertArrayHasKey('score', $result);
            $this->assertArrayHasKey('mobil', $result);
        }

        // Ranks should be 1, 2, 3
        $this->assertEquals(1, $results[0]['rank']);
        $this->assertEquals(2, $results[1]['rank']);
        $this->assertEquals(3, $results[2]['rank']);
    }

    public function test_validate_minimum_two_mobils()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimum 2 mobils required');

        $mobils = Mobil::factory(1)->get();
        $kriterias = Kriteria::all();
        $weights = [1 => 1.0];

        $calculator = app(MABACCalculator::class);
        $calculator->calculate($mobils, $kriterias, $weights);
    }

    public function test_validate_weights_sum_to_one()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Weights must sum to 1');

        $mobils = Mobil::factory(2)->get();
        $kriterias = Kriteria::all();
        $weights = [1 => 0.5, 2 => 0.3]; // Sums to 0.8, not 1.0

        $calculator = app(MABACCalculator::class);
        $calculator->calculate($mobils, $kriterias, $weights);
    }

    public function test_get_detailed_report()
    {
        $mobils = Mobil::factory(2)->get();
        $kriterias = Kriteria::all();
        $weights = [];
        foreach ($kriterias as $k) {
            $weights[$k->id] = 1 / $kriterias->count();
        }

        $calculator = app(MABACCalculator::class);
        $report = $calculator->getDetailedReport($mobils, $kriterias, $weights);

        // Should have all intermediate steps
        $this->assertArrayHasKey('matrix', $report);
        $this->assertArrayHasKey('normalized', $report);
        $this->assertArrayHasKey('weighted', $report);
        $this->assertArrayHasKey('baa', $report);
        $this->assertArrayHasKey('qMatrix', $report);
        $this->assertArrayHasKey('results', $report);
    }
}
```

---

## 🔧 Usage in Controller (After Refactoring)

```php
// app/Http/Controllers/PerhitunganController.php

use App\Services\MABAC\MABACCalculator;

class PerhitunganController extends Controller
{
    public function __construct(private MABACCalculator $calculator) {}

    public function calculate(Request $request)
    {
        $validated = $request->validate([...]);
        
        $mobils = Mobil::whereIn('id', $validated['mobil_ids'])->get();
        $kriterias = Kriteria::all();
        $weights = $this->extractWeights($request, $kriterias);

        try {
            // One line to calculate everything!
            $results = $this->calculator->calculate($mobils, $kriterias, $weights);

            return view('perhitungan.hasil', [
                'results' => $results,
                'mobils' => $mobils,
                'kriterias' => $kriterias,
            ]);
        } catch (MABACException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
```

---

## 📊 Code Quality Improvements

### Before Refactoring
```
File size: 217 lines
Methods: 6 (some 25+ lines)
Cyclomatic Complexity: High
Testability: Medium
Reusability: Low
```

### After Refactoring
```
File size: ~100 lines
Methods: 5 (max 15 lines each)
Cyclomatic Complexity: Low
Testability: High
Reusability: High
```

---

## 🎯 Next Steps

1. **Copy service files** ke workspace
2. **Update AppServiceProvider** dengan service bindings
3. **Replace PerhitunganController** dengan refactored version
4. **Run tests** untuk ensure everything works
5. **Update routes** jika perlu (optional)

---

## 📝 Notes

- Semua files sudah diformat dengan PHPDoc yang lengkap
- Exception handling sudah integrated
- Service dependency injection sudah configured
- Backward compatible dengan existing views
- Tidak ada breaking changes untuk API/routes

---

## ✅ Checklist Implementasi

- [ ] Copy MatrixBuilder.php ke app/Services/MABAC/
- [ ] Copy CriteriaTypeHandler.php ke app/Services/MABAC/
- [ ] Copy MatrixNormalizer.php ke app/Services/MABAC/
- [ ] Copy MABACCalculator.php ke app/Services/MABAC/
- [ ] Update AppServiceProvider.php
- [ ] Update PerhitunganController.php
- [ ] Create unit tests
- [ ] Run php artisan test
- [ ] Test manual dari browser
- [ ] Commit changes ke git

