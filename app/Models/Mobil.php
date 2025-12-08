<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    protected $table = 'mobils';
    protected $fillable = [
        'merk',
        'model',
        'tahun',
        'tipe',
        'harga_baru',
        'harga_jual_kembali',
        'fitur_keamanan',
        'fitur_kenyamanan',
        'jarak_tempuh',
        'kapasitas_mesin',
        'pajak',
        'gambar',
    ];

    protected $casts = [
        'harga_baru' => 'decimal:2',
        'harga_jual_kembali' => 'decimal:2',
        'jarak_tempuh' => 'decimal:2',
        'pajak' => 'decimal:2',
    ];
}
