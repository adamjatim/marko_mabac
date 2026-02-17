<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kriterias = [
            [
            'nama' => 'Harga Baru',
            'tipe' => 'cost',
            'bobot_default' => 0.22,
            'keterangan' => 'Harga pembelian kendaraan baru. Semakin rendah harga semakin baik.',
            'is_active' => true,
            ],
            [
            'nama' => 'Harga Jual Kembali',
            'tipe' => 'benefit',
            'bobot_default' => 0.14,
            'keterangan' => 'Nilai jual kembali kendaraan. Semakin tinggi nilai jual kembali semakin baik.',
            'is_active' => true,
            ],
            [
            'nama' => 'Fitur Keamanan',
            'tipe' => 'benefit',
            'bobot_default' => 0.16,
            'keterangan' => 'Fitur keselamatan kendaraan seperti airbag, ABS, ESC, dll. Semakin lengkap semakin baik.',
            'is_active' => true,
            ],
            [
            'nama' => 'Fitur Kenyamanan',
            'tipe' => 'benefit',
            'bobot_default' => 0.08,
            'keterangan' => 'Kenyamanan kursi, peredam suara, dan pendingin udara. Semakin nyaman semakin baik.',
            'is_active' => true,
            ],
            [
            'nama' => 'Efisiensi Bahan Bakar',
            'tipe' => 'benefit',
            'bobot_default' => 0.18,
            'keterangan' => 'Konsumsi bahan bakar per kilometer (km/L). Semakin tinggi konsumsi semakin baik (lebih efisien).',
            'is_active' => true,
            ],
            [
            'nama' => 'Performa',
            'tipe' => 'benefit',
            'bobot_default' => 0.12,
            'keterangan' => 'Daya kendaraan (HP) dan performa akselerasi. Semakin tinggi semakin baik.',
            'is_active' => true,
            ],
            [
            'nama' => 'Pajak Kendaraan',
            'tipe' => 'cost',
            'bobot_default' => 0.10,
            'keterangan' => 'Besaran pajak kendaraan tahunan. Semakin rendah semakin baik.',
            'is_active' => true,
            ],
        ];

        foreach ($kriterias as $kriteria) {
            Kriteria::create($kriteria);
        }
    }
}
