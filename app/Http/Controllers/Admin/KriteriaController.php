<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
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
}
