<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kriteria extends Model
{
    protected $table = 'kriterias';
    protected $fillable = ['nama', 'tipe', 'bobot_default', 'is_active', 'keterangan'];

    protected $casts = [
        'bobot_default' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship dengan BobotKriteria
     */
    public function bobot(): HasOne
    {
        return $this->hasOne(BobotKriteria::class);
    }
}
