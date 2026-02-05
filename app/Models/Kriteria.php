<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriterias';
    protected $fillable = ['nama', 'tipe', 'bobot_default', 'is_active', 'keterangan'];
    
    protected $casts = [
        'bobot_default' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
