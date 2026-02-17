@extends('layouts.app')

@section('title', 'Tambah Mobil Baru')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Tambah Mobil Baru</h1>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('admin.mobil.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="merk" class="block font-semibold text-gray-800 mb-2">Merek</label>
                    <input type="text" id="merk" name="merk" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Toyota">
                    @error('merk') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="model" class="block font-semibold text-gray-800 mb-2">Model</label>
                    <input type="text" id="model" name="model" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Avanza">
                    @error('model') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="tahun" class="block font-semibold text-gray-800 mb-2">Tahun</label>
                    <input type="text" id="tahun" name="tahun" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 2024">
                    @error('tahun') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="tipe" class="block font-semibold text-gray-800 mb-2">Tipe</label>
                    <select id="tipe" name="tipe" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Tipe</option>
                        <option value="City Car">City Car</option>
                        <option value="Sedan">Sedan</option>
                        <option value="MPV">MPV</option>
                        <option value="Compact SUV">Compact SUV</option>
                        <option value="Premium SUV">Premium SUV</option>
                        <option value="Electric Car">Electric Car</option>
                    </select>
                    @error('tipe') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="harga_baru" class="block font-semibold text-gray-800 mb-2">Harga Baru (Rp)</label>
                    <input type="number" id="harga_baru" name="harga_baru" required step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 180000000">
                    @error('harga_baru') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="harga_jual_kembali" class="block font-semibold text-gray-800 mb-2">Harga Jual Kembali (Rp)</label>
                    <input type="number" id="harga_jual_kembali" name="harga_jual_kembali" required step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('harga_jual_kembali') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="fitur_keamanan" class="block font-semibold text-gray-800 mb-2">Fitur Keamanan (jumlah)</label>
                    <input type="number" id="fitur_keamanan" name="fitur_keamanan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 6">
                    @error('fitur_keamanan') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="fitur_kenyamanan" class="block font-semibold text-gray-800 mb-2">Fitur Kenyamanan (jumlah)</label>
                    <input type="number" id="fitur_kenyamanan" name="fitur_kenyamanan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 8">
                    @error('fitur_kenyamanan') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="efisiensi_bahan_bakar" class="block font-semibold text-gray-800 mb-2">Efisiensi Bahan Bakar (km/l)</label>
                    <input type="number" id="efisiensi_bahan_bakar" name="efisiensi_bahan_bakar" required step="0.1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 14.5">
                    @error('efisiensi_bahan_bakar') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="performa" class="block font-semibold text-gray-800 mb-2">Performa (Horse Power/HP)</label>
                    <input type="number" id="performa" name="performa" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 1500">
                    @error('performa') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="pajak" class="block font-semibold text-gray-800 mb-2">Pajak Tahunan (Rp)</label>
                <input type="number" id="pajak" name="pajak" required step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('pajak') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="gambar" class="block font-semibold text-gray-800 mb-2">Gambar Mobil</label>
                <div id="gambarPreview" class="mb-4 hidden">
                    <img id="previewImage" src="" alt="Preview" class="h-40 object-cover rounded-lg border-2 border-blue-500 shadow-lg">
                    <p class="text-sm text-gray-600 mt-2">Preview gambar yang akan diupload</p>
                </div>
                <input type="file" id="gambar" name="gambar" accept="image/jpeg,image/png,image/jpg,image/gif" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-gray-600 text-sm mt-2">Format: JPG, PNG, GIF. Max: 5MB</p>
                @error('gambar') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                    Simpan Mobil
                </button>
                <a href="{{ route('admin.mobil.index') }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Image preview untuk create form
    document.getElementById('gambar').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('gambarPreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('gambarPreview').classList.add('hidden');
        }
    });
</script>
@endsection
