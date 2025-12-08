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
                'bobot_default' => 0.15,
            ],
            [
                'nama' => 'Harga Jual Kembali',
                'tipe' => 'benefit',
                'bobot_default' => 0.15,
            ],
            [
                'nama' => 'Fitur Keamanan',
                'tipe' => 'benefit',
                'bobot_default' => 0.15,
            ],
            [
                'nama' => 'Fitur Kenyamanan',
                'tipe' => 'benefit',
                'bobot_default' => 0.15,
            ],
            [
                'nama' => 'Jarak Tempuh',
                'tipe' => 'benefit',
                'bobot_default' => 0.15,
            ],
            [
                'nama' => 'Kapasitas Mesin',
                'tipe' => 'benefit',
                'bobot_default' => 0.15,
            ],
            [
                'nama' => 'Pajak Kendaraan',
                'tipe' => 'cost',
                'bobot_default' => 0.10,
            ],
        ];

        foreach ($kriterias as $kriteria) {
            Kriteria::create($kriteria);
        }
    }
}
