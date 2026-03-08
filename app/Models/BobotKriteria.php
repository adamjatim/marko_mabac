<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BobotKriteria extends Model
{
    protected $table = 'bobot_kriterias';
    protected $fillable = ['kriteria_id', 'nilai_input', 'nilai_penyebut', 'hasil_bobot'];

    protected $casts = [
        'nilai_input' => 'float',
        'nilai_penyebut' => 'float',
        'hasil_bobot' => 'float',
    ];

    /**
     * Relationship dengan Kriteria
     */
    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class);
    }

    /**
     * Static method untuk menghitung bobot otomatis dari nilai input
     * Jika semua kosong, gunakan default bobot dari kriteria
     */
    public static function hitungBobot(array $nilaiInputs): array
    {
        $kriterias = Kriteria::where('is_active', true)->get();
        $result = [];

        // Cek apakah semua kosong
        $semuaKosong = collect($nilaiInputs)->every(fn($val) => is_null($val) || $val === '');

        if ($semuaKosong) {
            // Gunakan bobot default
            foreach ($kriterias as $kriteria) {
                $result[$kriteria->id] = [
                    'nilai_input' => null,
                    'nilai_penyebut' => 1,
                    'hasil_bobot' => $kriteria->bobot_default,
                    'adalah_default' => true,
                ];
            }
            return $result;
        }

        // Cek apakah ada yang kosong (sebagian)
        $adaKosong = false;
        $kosongList = [];
        foreach ($kriterias as $kriteria) {
            if (!isset($nilaiInputs[$kriteria->id]) || is_null($nilaiInputs[$kriteria->id]) || $nilaiInputs[$kriteria->id] === '') {
                $adaKosong = true;
                $kosongList[] = $kriteria->nama;
            }
        }

        if ($adaKosong) {
            throw new \Exception('Kriteria yang kosong: ' . implode(', ', $kosongList) . '. Harap isi semua kriteria atau kosongkan semuanya untuk menggunakan nilai default.');
        }

        // Hitung total nilai input
        $totalNilai = 0;
        foreach ($nilaiInputs as $kriteria_id => $nilai) {
            $nilai = (float) $nilai;
            if ($nilai <= 0) {
                throw new \Exception('Nilai input harus lebih besar dari 0.');
            }
            $totalNilai += $nilai;
        }

        // Hitung bobot untuk setiap kriteria
        foreach ($kriterias as $kriteria) {
            $nilaiInput = (float) ($nilaiInputs[$kriteria->id] ?? 0);
            $hasilBobot = $nilaiInput / $totalNilai;

            $result[$kriteria->id] = [
                'nilai_input' => $nilaiInput,
                'nilai_penyebut' => $totalNilai,
                'hasil_bobot' => round($hasilBobot, 4),
                'adalah_default' => false,
            ];
        }

        return $result;
    }

    /**
     * Simpan hasil perhitungan bobot
     */
    public static function simpanBobot(array $hasilHitung): void
    {
        foreach ($hasilHitung as $kriteria_id => $data) {
            self::updateOrCreate(
                ['kriteria_id' => $kriteria_id],
                [
                    'nilai_input' => $data['nilai_input'],
                    'nilai_penyebut' => $data['nilai_penyebut'],
                    'hasil_bobot' => $data['hasil_bobot'],
                ]
            );
        }
    }

    /**
     * Ambil semua bobot yang aktif
     */
    public static function getActiveBobots(): array
    {
        $bobots = [];
        $rows = self::with('kriteria')
            ->whereHas('kriteria', fn($q) => $q->where('is_active', true))
            ->get();

        foreach ($rows as $row) {
            $bobots[$row->kriteria_id] = $row->hasil_bobot;
        }

        return $bobots;
    }
}
