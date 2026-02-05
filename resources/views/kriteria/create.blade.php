@extends('layouts.app')

@section('title', 'Tambah Kriteria')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Tambah Kriteria Baru</h1>

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
        <form action="{{ route('kriteria.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Kriteria -->
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Kriteria <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    name="nama" 
                    id="nama"
                    value="{{ old('nama') }}"
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
                    <option value="benefit" {{ old('tipe') === 'benefit' ? 'selected' : '' }}>Benefit (Semakin tinggi semakin baik)</option>
                    <option value="cost" {{ old('tipe') === 'cost' ? 'selected' : '' }}>Cost (Semakin rendah semakin baik)</option>
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
                    value="{{ old('bobot_default') }}"
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
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('keterangan') }}</textarea>
                <p class="text-gray-500 text-xs mt-1">Penjelasan akan membantu pengguna memahami kriteria ini</p>
            </div>

            <!-- Status Aktif -->
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    id="is_active"
                    value="1"
                    {{ old('is_active') ? 'checked' : 'checked' }}
                    class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                <label for="is_active" class="ml-3 text-sm font-semibold text-gray-700">
                    Aktifkan kriteria ini dari awal
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t">
                <a href="{{ route('kriteria.index') }}" class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold text-center">
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                    Simpan Kriteria
                </button>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="mt-8 p-6 bg-blue-50 rounded-lg border-l-4 border-blue-600">
        <h3 class="font-bold text-gray-800 mb-2">💡 Tips:</h3>
        <ul class="text-gray-700 space-y-1 text-sm">
            <li>• Pastikan nama kriteria unik dan jelas</li>
            <li>• Pilih tipe yang sesuai untuk perhitungan akurat</li>
            <li>• Bobot default dapat diubah saat melakukan perhitungan</li>
            <li>• Tambahkan keterangan untuk memberikan konteks kepada pengguna</li>
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
