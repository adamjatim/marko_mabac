<?php

namespace App\Services;

/**
 * Helper service untuk test & debug pengaturan bobot
 */
class BobotKriteriaDebugService
{
    /**
     * Test database connection
     */
    public static function testDatabaseConnection(): array
    {
        try {
            $count = \App\Models\Kriteria::count();
            return [
                'success' => true,
                'message' => "Database OK, Total Kriteria: $count",
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Database Error: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Test migration table
     */
    public static function testBobotTable(): array
    {
        try {
            $schema = \DB::connection()->getSchemaBuilder();
            if (!$schema->hasTable('bobot_kriterias')) {
                return [
                    'success' => false,
                    'message' => "Table bobot_kriterias tidak ada. Jalankan: php artisan migrate",
                ];
            }

            $count = \App\Models\BobotKriteria::count();
            $columns = $schema->getColumnListing('bobot_kriterias');

            return [
                'success' => true,
                'message' => "Table OK",
                'data' => [
                    'total_records' => $count,
                    'columns' => $columns,
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Table Error: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Test active kriteria
     */
    public static function testActiveKriteria(): array
    {
        try {
            $kriterias = \App\Models\Kriteria::where('is_active', true)->get();

            if ($kriterias->count() === 0) {
                return [
                    'success' => false,
                    'message' => "Tidak ada kriteria aktif",
                ];
            }

            return [
                'success' => true,
                'message' => "Kriteria aktif ditemukan",
                'data' => [
                    'total' => $kriterias->count(),
                    'kriteria' => $kriterias->map(fn($k) => [
                        'id' => $k->id,
                        'nama' => $k->nama,
                        'bobot_default' => $k->bobot_default,
                    ])->toArray(),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Kriteria Error: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Test calculate bobot
     */
    public static function testCalculateBobot(): array
    {
        try {
            // Test dengan nilai dummy
            $nilaiInputs = [
                1 => 9,
                2 => 5,
                3 => 6,
                4 => 5,
                5 => 2,
                6 => 4,
                7 => 7,
            ];

            $hasil = \App\Models\BobotKriteria::hitungBobot($nilaiInputs);

            $totalBobot = 0;
            foreach ($hasil as $item) {
                $totalBobot += $item['hasil_bobot'];
            }

            return [
                'success' => true,
                'message' => "Hitungan OK",
                'data' => [
                    'total_bobot_calculated' => round($totalBobot, 4),
                    'expected' => 1.0,
                    'match' => abs($totalBobot - 1.0) < 0.001,
                    'sample_result' => array_slice($hasil, 0, 1),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Calculate Error: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Test view file
     */
    public static function testViewFile(): array
    {
        try {
            $viewPath = resource_path('views/admin/kriteria/pengaturan-bobot.blade.php');

            if (!file_exists($viewPath)) {
                return [
                    'success' => false,
                    'message' => "View file tidak ditemukan: $viewPath",
                ];
            }

            return [
                'success' => true,
                'message' => "View file OK",
                'data' => [
                    'path' => $viewPath,
                    'exists' => true,
                    'size' => filesize($viewPath) . ' bytes',
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "View Error: " . $e->getMessage(),
            ];
        }
    }

    /**
     * Run all tests
     */
    public static function runAllTests(): array
    {
        return [
            'database_connection' => self::testDatabaseConnection(),
            'bobot_table' => self::testBobotTable(),
            'active_kriteria' => self::testActiveKriteria(),
            'calculate_bobot' => self::testCalculateBobot(),
            'view_file' => self::testViewFile(),
        ];
    }
}
