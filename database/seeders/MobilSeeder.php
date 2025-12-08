<?php

namespace Database\Seeders;

use App\Models\Mobil;
use Illuminate\Database\Seeder;

class MobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mobils = [
            [
                'merk' => 'Toyota',
                'model' => 'Avanza',
                'tahun' => '2024',
                'tipe' => 'MPV',
                'harga_baru' => 180000000,
                'harga_jual_kembali' => 144000000,
                'fitur_keamanan' => 6,
                'fitur_kenyamanan' => 8,
                'jarak_tempuh' => 14.5,
                'kapasitas_mesin' => 1500,
                'pajak' => 1800000,
            ],
            [
                'merk' => 'Honda',
                'model' => 'City',
                'tahun' => '2024',
                'tipe' => 'City Car',
                'harga_baru' => 165000000,
                'harga_jual_kembali' => 132000000,
                'fitur_keamanan' => 5,
                'fitur_kenyamanan' => 7,
                'jarak_tempuh' => 16.2,
                'kapasitas_mesin' => 1200,
                'pajak' => 1650000,
            ],
            [
                'merk' => 'Suzuki',
                'model' => 'Ertiga',
                'tahun' => '2024',
                'tipe' => 'MPV',
                'harga_baru' => 170000000,
                'harga_jual_kembali' => 136000000,
                'fitur_keamanan' => 5,
                'fitur_kenyamanan' => 7,
                'jarak_tempuh' => 15.1,
                'kapasitas_mesin' => 1500,
                'pajak' => 1700000,
            ],
            [
                'merk' => 'Honda',
                'model' => 'Accord',
                'tahun' => '2024',
                'tipe' => 'Sedan',
                'harga_baru' => 520000000,
                'harga_jual_kembali' => 390000000,
                'fitur_keamanan' => 8,
                'fitur_kenyamanan' => 9,
                'jarak_tempuh' => 12.5,
                'kapasitas_mesin' => 1500,
                'pajak' => 4000000,
            ],
            [
                'merk' => 'Toyota',
                'model' => 'Corolla',
                'tahun' => '2024',
                'tipe' => 'Sedan',
                'harga_baru' => 385000000,
                'harga_jual_kembali' => 280000000,
                'fitur_keamanan' => 7,
                'fitur_kenyamanan' => 8,
                'jarak_tempuh' => 13.2,
                'kapasitas_mesin' => 1600,
                'pajak' => 3000000,
            ],
            [
                'merk' => 'Wuling',
                'model' => 'Air EV',
                'tahun' => '2024',
                'tipe' => 'Electric Car',
                'harga_baru' => 340000000,
                'harga_jual_kembali' => 255000000,
                'fitur_keamanan' => 6,
                'fitur_kenyamanan' => 8,
                'jarak_tempuh' => 0.0,
                'kapasitas_mesin' => 0,
                'pajak' => 0,
            ],
            [
                'merk' => 'Daihatsu',
                'model' => 'Rocky',
                'tahun' => '2024',
                'tipe' => 'Compact SUV',
                'harga_baru' => 225000000,
                'harga_jual_kembali' => 168750000,
                'fitur_keamanan' => 6,
                'fitur_kenyamanan' => 7,
                'jarak_tempuh' => 13.8,
                'kapasitas_mesin' => 1200,
                'pajak' => 2250000,
            ],
            [
                'merk' => 'Hyundai',
                'model' => 'Creta',
                'tahun' => '2024',
                'tipe' => 'Compact SUV',
                'harga_baru' => 245000000,
                'harga_jual_kembali' => 183750000,
                'fitur_keamanan' => 6,
                'fitur_kenyamanan' => 8,
                'jarak_tempuh' => 14.2,
                'kapasitas_mesin' => 1500,
                'pajak' => 2450000,
            ],
            [
                'merk' => 'BMW',
                'model' => 'X5',
                'tahun' => '2024',
                'tipe' => 'Premium SUV',
                'harga_baru' => 1200000000,
                'harga_jual_kembali' => 840000000,
                'fitur_keamanan' => 9,
                'fitur_kenyamanan' => 10,
                'jarak_tempuh' => 11.0,
                'kapasitas_mesin' => 3000,
                'pajak' => 12000000,
            ],
            [
                'merk' => 'Mercedes-Benz',
                'model' => 'C-Class',
                'tahun' => '2024',
                'tipe' => 'Premium SUV',
                'harga_baru' => 850000000,
                'harga_jual_kembali' => 595000000,
                'fitur_keamanan' => 9,
                'fitur_kenyamanan' => 10,
                'jarak_tempuh' => 10.5,
                'kapasitas_mesin' => 2000,
                'pajak' => 8500000,
            ],
        ];

        foreach ($mobils as $mobil) {
            Mobil::create($mobil);
        }
    }
}
