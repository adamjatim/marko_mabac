@extends('layouts.app')

@section('title', 'Edit Kriteria')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Edit Kriteria: {{ $kriteria->nama }}</h1>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-600 p-4 mb-6 rounded">
            <p class="text-red-800 font-semibold">Terjadi kesalahan validasi:</p>
            <ul class="text-red-700 mt-2">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-8">
        <form action="{{ route('kriteria.update', $kriteria) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Kriteria -->
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Kriteria <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    name="nama" 
                    id="nama"
                    value="{{ old('nama', $kriteria->nama) }}"
                    placeholder="Contoh: Harga Baru, Fitur Keamanan"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
                <p class="text-gray-500 text-xs mt-1">Masukkan nama kriteria yang unik dan deskriptif</p>
            </div>

            <!-- Tipe Kriteria -->
            <div>
                <label for="tipe" class="block text-sm font-semibold text-gray-700 mb-2">
                    Tipe Kriteria <span class="text-red-600">*</span>
                </label>
                <select 
                    name="tipe" 
                    id="tipe"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                    onchange="updateKeterangan()">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="benefit" {{ old('tipe', $kriteria->tipe) === 'benefit' ? 'selected' : '' }}>Benefit (Semakin tinggi semakin baik)</option>
                    <option value="cost" {{ old('tipe', $kriteria->tipe) === 'cost' ? 'selected' : '' }}>Cost (Semakin rendah semakin baik)</option>
                </select>
                <p class="text-gray-500 text-xs mt-1">
                    <strong>Benefit:</strong> Contoh - Fitur keamanan, kenyamanan, performa<br>
                    <strong>Cost:</strong> Contoh - Harga, konsumsi bahan bakar
                </p>
            </div>

            <!-- Bobot Default -->
            <div>
                <label for="bobot_default" class="block text-sm font-semibold text-gray-700 mb-2">
                    Bobot Default (%) <span class="text-red-600">*</span>
                </label>
                <input 
                    type="number" 
                    name="bobot_default" 
                    id="bobot_default"
                    value="{{ old('bobot_default', $kriteria->bobot_default) }}"
                    min="0" 
                    max="100" 
                    step="0.01"
                    placeholder="Contoh: 20.5"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
                <p class="text-gray-500 text-xs mt-1">Bobot default akan digunakan saat perhitungan (0-100)</p>
            </div>

            <!-- Keterangan -->
            <div>
                <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">
                    Keterangan / Deskripsi
                </label>
                <textarea 
                    name="keterangan" 
                    id="keterangan"
                    rows="4"
                    placeholder="Masukkan penjelasan atau catatan tentang kriteria ini (opsional)"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('keterangan', $kriteria->keterangan) }}</textarea>
                <p class="text-gray-500 text-xs mt-1">Penjelasan akan membantu pengguna memahami kriteria ini</p>
            </div>

            <!-- Status Aktif -->
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    id="is_active"
                    value="1"
                    {{ old('is_active', $kriteria->is_active) ? 'checked' : '' }}
                    class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                <label for="is_active" class="ml-3 text-sm font-semibold text-gray-700">
                    Kriteria aktif
                </label>
            </div>

            <!-- Info Alert -->
            <div class="p-4 bg-yellow-50 border-l-4 border-yellow-600 rounded">
                <p class="text-yellow-800 text-sm">
                    <strong>⚠️ Catatan:</strong> Mengubah data kriteria ini akan mempengaruhi perhitungan yang menggunakan kriteria ini.
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t">
                <a href="{{ route('kriteria.index') }}" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold text-center">
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <!-- Delete Option -->
        <div class="mt-8 pt-8 border-t">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Zona Berbahaya</h3>
            <form action="{{ route('kriteria.destroy', $kriteria) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria ini? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                    🗑️ Hapus Kriteria
                </button>
            </form>
            <p class="text-red-600 text-xs mt-2">Menghapus kriteria akan menghilangkan data ini secara permanen</p>
        </div>
    </div>

    <!-- Info Box -->
    <div class="mt-8 p-6 bg-blue-50 rounded-lg border-l-4 border-blue-600">
        <h3 class="font-bold text-gray-800 mb-2">💡 Tips Editing:</h3>
        <ul class="text-gray-700 space-y-1 text-sm">
            <li>• Hindari mengubah nama kriteria yang sudah digunakan dalam perhitungan</li>
            <li>• Perubahan bobot hanya mempengaruhi perhitungan baru</li>
            <li>• Nonaktifkan kriteria jika tidak ingin menggunakannya tanpa menghapusnya</li>
        </ul>
    </div>
</div>

<script>
function updateKeterangan() {
    const tipe = document.getElementById('tipe').value;
    const keterangan = document.getElementById('keterangan');
    
    if (tipe === 'benefit') {
        keterangan.placeholder = 'Contoh: Semakin baik fitur keamanan, semakin tinggi skornya...';
    } else if (tipe === 'cost') {
        keterangan.placeholder = 'Contoh: Semakin rendah harga, semakin tinggi skornya...';
    }
}
</script>
@endsection
