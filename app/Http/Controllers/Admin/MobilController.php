<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class MobilController extends Controller
{
    public function index(): View
    {
        $mobils = Mobil::all();
        return view('admin.mobil.index', ['mobils' => $mobils]);
    }

    public function create(): View
    {
        return view('admin.mobil.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'merk' => 'required|string',
            'model' => 'required|string',
            'tahun' => 'required|string',
            'tipe' => 'required|string',
            'harga_baru' => 'required|numeric',
            'harga_jual_kembali' => 'required|numeric',
            'fitur_keamanan' => 'required|integer',
            'fitur_kenyamanan' => 'required|integer',
            // 'jarak_tempuh' => 'required|numeric',
            'efisiensi_bahan_bakar' => 'required|numeric',
            // 'kapasitas_mesin' => 'required|integer',
            'performa' => 'required|integer',
            'pajak' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('mobils', $filename, 'public');
            $validated['gambar'] = '/storage/' . $path;
        }

        Mobil::create($validated);
        return redirect()->route('admin.mobil.index')->with('success', 'Mobil berhasil ditambahkan');
    }

    public function edit(Mobil $mobil): View
    {
        return view('admin.mobil.edit', ['mobil' => $mobil]);
    }

    public function update(Request $request, Mobil $mobil): RedirectResponse
    {
        $validated = $request->validate([
            'merk' => 'required|string',
            'model' => 'required|string',
            'tahun' => 'required|string',
            'tipe' => 'required|string',
            'harga_baru' => 'required|numeric',
            'harga_jual_kembali' => 'required|numeric',
            'fitur_keamanan' => 'required|integer',
            'fitur_kenyamanan' => 'required|integer',
            // 'jarak_tempuh' => 'required|numeric',
            'efisiensi_bahan_bakar' => 'required|numeric',
            // 'kapasitas_mesin' => 'required|integer',
            'performa' => 'required|integer',
            'pajak' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($mobil->gambar && file_exists(public_path($mobil->gambar))) {
                unlink(public_path($mobil->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('mobils', $filename, 'public');
            $validated['gambar'] = '/storage/' . $path;
        }

        $mobil->update($validated);
        return redirect()->route('admin.mobil.index')->with('success', 'Mobil berhasil diubah');
    }

    public function destroy(Mobil $mobil): RedirectResponse
    {
        // Delete image if exists
        if ($mobil->gambar && file_exists(public_path($mobil->gambar))) {
            unlink(public_path($mobil->gambar));
        }

        $mobil->delete();
        return redirect()->route('admin.mobil.index')->with('success', 'Mobil berhasil dihapus');
    }
}

