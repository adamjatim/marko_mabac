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
                'bobot_default' => 25.00,
                'keterangan' => 'Harga pembelian kendaraan baru. Semakin rendah harga semakin baik.',
                'is_active' => true,
            ],
            [
                'nama' => 'Konsumsi Bahan Bakar',
                'tipe' => 'cost',
                'bobot_default' => 20.00,
                'keterangan' => 'Konsumsi bahan bakar per kilometer (km/L). Semakin tinggi konsumsi semakin baik (lebih efisien).',
                'is_active' => true,
            ],
            [
                'nama' => 'Kapasitas Penumpang',
                'tipe' => 'benefit',
                'bobot_default' => 15.00,
                'keterangan' => 'Jumlah penumpang yang dapat ditampung. Semakin banyak penumpang semakin baik.',
                'is_active' => true,
            ],
            [
                'nama' => 'Fitur Keamanan',
                'tipe' => 'benefit',
                'bobot_default' => 20.00,
                'keterangan' => 'Fitur keselamatan kendaraan seperti airbag, ABS, ESC, dll. Semakin lengkap semakin baik.',
                'is_active' => true,
            ],
            [
                'nama' => 'Performa Mesin',
                'tipe' => 'benefit',
                'bobot_default' => 15.00,
                'keterangan' => 'Daya kendaraan (HP) dan performa akselerasi. Semakin tinggi semakin baik.',
                'is_active' => true,
            ],
            [
                'nama' => 'Kenyamanan Interior',
                'tipe' => 'benefit',
                'bobot_default' => 10.00,
                'keterangan' => 'Kenyamanan kursi, peredam suara, dan pendingin udara. Semakin nyaman semakin baik.',
                'is_active' => true,
            ],
            [
                'nama' => 'Teknologi Infotainment',
                'tipe' => 'benefit',
                'bobot_default' => 8.00,
                'keterangan' => 'Fitur sistem hiburan dan informasi seperti touchscreen, USB, Bluetooth, Android Auto, dll.',
                'is_active' => true,
            ],
            [
                'nama' => 'Kapasitas Trunk',
                'tipe' => 'benefit',
                'bobot_default' => 7.00,
                'keterangan' => 'Ruang bagasi kendaraan dalam satuan liter. Semakin besar semakin baik.',
                'is_active' => true,
            ],
        ];

        foreach ($kriterias as $kriteria) {
            Kriteria::create($kriteria);
        }
    }
}
