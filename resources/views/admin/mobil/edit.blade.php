@extends('layouts.app')

@section('title', 'Edit Mobil')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Edit Mobil</h1>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <form method="POST" action="{{ route('admin.mobil.update', $mobil) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="merk" class="block font-semibold text-gray-800 mb-2">Merek</label>
                    <input type="text" id="merk" name="merk" value="{{ $mobil->merk }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('merk') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="model" class="block font-semibold text-gray-800 mb-2">Model</label>
                    <input type="text" id="model" name="model" value="{{ $mobil->model }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('model') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="tahun" class="block font-semibold text-gray-800 mb-2">Tahun</label>
                    <input type="text" id="tahun" name="tahun" value="{{ $mobil->tahun }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('tahun') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="tipe" class="block font-semibold text-gray-800 mb-2">Tipe</label>
                    <select id="tipe" name="tipe" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="City Car" @if($mobil->tipe === 'City Car') selected @endif>City Car</option>
                        <option value="Sedan" @if($mobil->tipe === 'Sedan') selected @endif>Sedan</option>
                        <option value="MPV" @if($mobil->tipe === 'MPV') selected @endif>MPV</option>
                        <option value="Compact SUV" @if($mobil->tipe === 'Compact SUV') selected @endif>Compact SUV</option>
                        <option value="Premium SUV" @if($mobil->tipe === 'Premium SUV') selected @endif>Premium SUV</option>
                        <option value="Electric Car" @if($mobil->tipe === 'Electric Car') selected @endif>Electric Car</option>
                    </select>
                    @error('tipe') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="harga_baru" class="block font-semibold text-gray-800 mb-2">Harga Baru (Rp)</label>
                    <input type="number" id="harga_baru" name="harga_baru" value="{{ $mobil->harga_baru }}" required step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('harga_baru') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="harga_jual_kembali" class="block font-semibold text-gray-800 mb-2">Harga Jual Kembali (Rp)</label>
                    <input type="number" id="harga_jual_kembali" name="harga_jual_kembali" value="{{ $mobil->harga_jual_kembali }}" required step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('harga_jual_kembali') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="fitur_keamanan" class="block font-semibold text-gray-800 mb-2">Fitur Keamanan</label>
                    <input type="number" id="fitur_keamanan" name="fitur_keamanan" value="{{ $mobil->fitur_keamanan }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('fitur_keamanan') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="fitur_kenyamanan" class="block font-semibold text-gray-800 mb-2">Fitur Kenyamanan</label>
                    <input type="number" id="fitur_kenyamanan" name="fitur_kenyamanan" value="{{ $mobil->fitur_kenyamanan }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('fitur_kenyamanan') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="efisiensi_bahan_bakar" class="block font-semibold text-gray-800 mb-2">Efisiensi Bahan Bakar (km/l)</label>
                    <input type="number" id="efisiensi_bahan_bakar" name="efisiensi_bahan_bakar" value="{{ $mobil->efisiensi_bahan_bakar }}" required step="0.1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('efisiensi_bahan_bakar') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="performa" class="block font-semibold text-gray-800 mb-2">Performa (Horse Power/HP)</label>
                    <input type="number" id="performa" name="performa" value="{{ $mobil->performa }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('performa') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="pajak" class="block font-semibold text-gray-800 mb-2">Pajak Tahunan (Rp)</label>
                <input type="number" id="pajak" name="pajak" value="{{ $mobil->pajak }}" required step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('pajak') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="gambar" class="block font-semibold text-gray-800 mb-2">Gambar Mobil</label>

                <div id="currentGambarDiv" class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-300">
                    <p class="text-sm font-semibold text-gray-700 mb-2">📸 Gambar Saat Ini:</p>
                    @if($mobil->gambar)
                        <img src="{{ $mobil->gambar }}" alt="{{ $mobil->merk }} {{ $mobil->model }}" class="h-40 object-cover rounded-lg border-2 border-gray-300">
                    @else
                        <div class="h-40 bg-gray-300 rounded-lg flex items-center justify-center text-4xl border-2 border-gray-300">🚗</div>
                    @endif
                </div>

                <div id="gambarPreview" class="mb-4 hidden p-4 bg-blue-50 rounded-lg border border-blue-300">
                    <p class="text-sm font-semibold text-blue-700 mb-2">✨ Preview Gambar Baru:</p>
                    <img id="previewImage" src="" alt="Preview" class="h-40 object-cover rounded-lg border-2 border-blue-500">
                </div>

                <input type="file" id="gambar" name="gambar" accept="image/jpeg,image/png,image/jpg,image/gif" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-gray-600 text-sm mt-2">Format: JPG, PNG, GIF. Max: 5MB (Kosongi jika tidak ingin ubah)</p>
                @error('gambar') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                    Update Mobil
                </button>
                <a href="{{ route('admin.mobil.index') }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Image preview untuk edit form
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
