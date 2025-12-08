@extends('layouts.app')

@section('title', 'Edit Kriteria')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Edit Kriteria</h1>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('admin.kriteria.update', $kriteria) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="block font-semibold text-gray-800 mb-2">Nama Kriteria</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ $kriteria->nama }}"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                @error('nama') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="tipe" class="block font-semibold text-gray-800 mb-2">Tipe Kriteria</label>
                <select
                    id="tipe"
                    name="tipe"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="" disabled>Pilih tipe kriteria</option>
                    <option value="benefit" @selected($kriteria->tipe === 'benefit')>Benefit (Semakin tinggi semakin baik)</option>
                    <option value="cost" @selected($kriteria->tipe === 'cost')>Cost (Semakin rendah semakin baik)</option>
                </select>
                @error('tipe') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                <p class="text-gray-600 text-sm mt-2">
                    <strong>Benefit:</strong> Semakin tinggi nilainya semakin baik (contoh: Fitur Keamanan, Jarak Tempuh)
                    <br>
                    <strong>Cost:</strong> Semakin rendah nilainya semakin baik (contoh: Harga, Pajak)
                </p>
            </div>

            <div>
                <label for="bobot_default" class="block font-semibold text-gray-800 mb-2">Bobot Default</label>
                <input
                    type="number"
                    id="bobot_default"
                    name="bobot_default"
                    value="{{ $kriteria->bobot_default }}"
                    min="0"
                    max="1"
                    step="0.01"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                @error('bobot_default') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                <p class="text-gray-600 text-sm mt-2">Masukkan nilai antara 0 dan 1 (contoh: 0.15 untuk 15%)</p>
            </div>

            <div class="p-6 bg-yellow-50 rounded-lg border-l-4 border-yellow-600">
                <h3 class="font-bold text-gray-800 mb-3">💡 Tips Pengeditan</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li>✓ Anda dapat mengubah nama kriteria sesuai kebutuhan</li>
                    <li>✓ Ubah tipe kriteria antara Benefit dan Cost</li>
                    <li>✓ Sesuaikan bobot default untuk prioritas</li>
                    <li>⚠️ Perubahan akan berpengaruh pada perhitungan berikutnya</li>
                </ul>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                    Update Kriteria
                </button>
                <a href="{{ route('admin.kriteria.index') }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
