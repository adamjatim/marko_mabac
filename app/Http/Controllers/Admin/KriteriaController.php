<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\BobotKriteria;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KriteriaController extends Controller
{
    public function index(): View
    {
        $kriterias = Kriteria::all();
        return view('admin.kriteria.index', ['kriterias' => $kriterias]);
    }

    public function edit(Kriteria $kriteria): View
    {
        return view('admin.kriteria.edit', ['kriteria' => $kriteria]);
    }

    public function update(Request $request, Kriteria $kriteria): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:benefit,cost',
            'bobot_default' => 'required|numeric|min:0|max:1',
        ]);

        $kriteria->update($validated);
        return redirect()->route('admin.kriteria.index')->with('success', 'Kriteria berhasil diubah');
    }

    /**
     * Tampilkan form pengaturan bobot kriteria
     */
    public function pengaturanBobot(): View
    {
        $kriterias = Kriteria::where('is_active', true)->get();

        // Ambil bobot yang sudah tersimpan atau tampilkan input kosong
        $bobotData = [];
        foreach ($kriterias as $kriteria) {
            $bobot = $kriteria->bobot;
            if ($bobot) {
                $bobotData[$kriteria->id] = [
                    'nilai_input' => $bobot->nilai_input,
                    'nilai_penyebut' => $bobot->nilai_penyebut ?? 1,
                    'hasil_bobot' => $bobot->hasil_bobot,
                    'adalah_default' => is_null($bobot->nilai_input),
                ];
            } else {
                $bobotData[$kriteria->id] = [
                    'nilai_input' => null,
                    'nilai_penyebut' => 1,
                    'hasil_bobot' => $kriteria->bobot_default,
                    'adalah_default' => true,
                ];
            }
        }

        return view('admin.kriteria.pengaturan-bobot', [
            'kriterias' => $kriterias,
            'bobotData' => $bobotData,
        ]);
    }

    /**
     * Hitung bobot dari nilai input yang dikirim
     */
    public function hitungBobot(Request $request): View
    {
        $kriterias = Kriteria::where('is_active', true)->get();

        // Siapkan array nilai input
        $nilaiInputs = $request->input('nilai_input', []);

        try {
            // Hitung bobot
            $hasilHitung = BobotKriteria::hitungBobot($nilaiInputs);

            // Konversi ke format yang sesuai untuk view
            $bobotData = [];
            foreach ($hasilHitung as $kriteria_id => $data) {
                $bobotData[$kriteria_id] = $data;
            }

            return view('admin.kriteria.pengaturan-bobot', [
                'kriterias' => $kriterias,
                'bobotData' => $bobotData,
                'isCalculated' => true,
                'message' => 'Perhitungan bobot berhasil!',
            ]);
        } catch (\Exception $e) {
            // Saat error, kembalikan dengan data kosong tapi struktur sama seperti pengaturanBobot()
            $bobotData = [];
            foreach ($kriterias as $kriteria) {
                $bobot = $kriteria->bobot;
                if ($bobot) {
                    $bobotData[$kriteria->id] = [
                        'nilai_input' => $bobot->nilai_input,
                        'nilai_penyebut' => $bobot->nilai_penyebut ?? 1,
                        'hasil_bobot' => $bobot->hasil_bobot,
                        'adalah_default' => is_null($bobot->nilai_input),
                    ];
                } else {
                    $bobotData[$kriteria->id] = [
                        'nilai_input' => null,
                        'nilai_penyebut' => 1,
                        'hasil_bobot' => $kriteria->bobot_default,
                        'adalah_default' => true,
                    ];
                }
            }

            return view('admin.kriteria.pengaturan-bobot', [
                'kriterias' => $kriterias,
                'bobotData' => $bobotData,
                'isCalculated' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Simpan bobot yang telah dihitung
     */
    public function simpanBobot(Request $request): RedirectResponse
    {
        $kriterias = Kriteria::where('is_active', true)->get();

        // Siapkan array nilai input
        $nilaiInputs = $request->input('nilai_input', []);

        try {
            // Hitung bobot
            $hasilHitung = BobotKriteria::hitungBobot($nilaiInputs);

            // Simpan bobot
            BobotKriteria::simpanBobot($hasilHitung);

            return redirect()->route('admin.kriteria.pengaturan-bobot')
                ->with('success', 'Pengaturan bobot kriteria berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->route('admin.kriteria.pengaturan-bobot')
                ->with('error', 'Gagal menyimpan bobot: ' . $e->getMessage());
        }
    }

    /**
     * Reset bobot ke nilai default
     */
    public function resetBobot(): RedirectResponse
    {
        BobotKriteria::truncate();

        return redirect()->route('admin.kriteria.pengaturan-bobot')
            ->with('success', 'Bobot kriteria telah direset ke nilai default!');
    }
}
