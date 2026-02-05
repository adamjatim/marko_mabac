<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KriteriaController extends Controller
{
    // Display list of kriteria with filtering
    public function index(): View
    {
        $kriterias = Kriteria::orderBy('created_at', 'desc')->get();
        $aktifCount = Kriteria::where('is_active', true)->count();
        $totalCount = Kriteria::count();
        
        return view('kriteria.index', [
            'kriterias' => $kriterias,
            'aktifCount' => $aktifCount,
            'totalCount' => $totalCount
        ]);
    }

    // Show create form
    public function create(): View
    {
        return view('kriteria.create');
    }

    // Store new kriteria
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kriterias,nama',
            'tipe' => 'required|in:benefit,cost',
            'bobot_default' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Kriteria::create($validated);

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan');
    }

    // Show edit form
    public function edit(Kriteria $kriteria): View
    {
        return view('kriteria.edit', ['kriteria' => $kriteria]);
    }

    // Update kriteria
    public function update(Request $request, Kriteria $kriteria): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kriterias,nama,' . $kriteria->id,
            'tipe' => 'required|in:benefit,cost',
            'bobot_default' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $kriteria->update($validated);

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui');
    }

    // Delete kriteria
    public function destroy(Kriteria $kriteria): RedirectResponse
    {
        $kriteria->delete();

        return redirect()->route('kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus');
    }

    // Toggle active status (via AJAX or form)
    public function toggleActive(Kriteria $kriteria): RedirectResponse
    {
        $kriteria->update(['is_active' => !$kriteria->is_active]);

        return redirect()->route('kriteria.index')
            ->with('success', 'Status kriteria berhasil diubah');
    }
}

