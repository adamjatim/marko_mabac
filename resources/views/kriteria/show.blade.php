@extends('layouts.app')

@section('title', 'Detail Kriteria: ' . $kriteria->nama)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Header dengan tombol kembali dan edit (jika auth) -->
        <div class="mb-8">
            <a href="{{ route('kriteria.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">← Kembali ke Daftar</a>
            
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800">{{ $kriteria->nama }}</h1>
                    <p class="text-gray-600 mt-2">Detail Kriteria Penilaian</p>
                </div>
                
                <!-- Tombol Edit hanya untuk user yang terautentikasi (Admin) -->
                @if(auth()->check())
                <a href="{{ route('kriteria.edit', $kriteria) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                    ✎️ Edit Kriteria
                </a>
                @endif
            </div>
        </div>

        <!-- Konten Detail Kriteria -->
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <!-- Info Tipe -->
            <div class="border-b pb-4">
                <p class="text-gray-600 text-sm font-semibold mb-2">Tipe Kriteria</p>
                <div class="flex items-center gap-2">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($kriteria->tipe === 'benefit')
                            bg-green-100 text-green-800
                        @else
                            bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($kriteria->tipe) }}
                    </span>
                    <span class="text-gray-600 text-sm">
                        @if($kriteria->tipe === 'benefit')
                            (Semakin tinggi semakin baik)
                        @else
                            (Semakin rendah semakin baik)
                        @endif
                    </span>
                </div>
            </div>

            <!-- Info Status -->
            <div class="border-b pb-4">
                <p class="text-gray-600 text-sm font-semibold mb-2">Status</p>
                <span class="px-4 py-2 rounded-full text-sm font-semibold inline-block
                    @if($kriteria->is_active)
                        bg-green-100 text-green-800
                    @else
                        bg-gray-100 text-gray-800
                    @endif">
                    {{ $kriteria->is_active ? '✓ Aktif' : '✗ Nonaktif' }}
                </span>
            </div>
        </div>

        <!-- Bobot Default -->
        <div class="bg-blue-50 rounded-lg p-6 mb-8 border-l-4 border-blue-600">
            <p class="text-blue-800 text-sm font-semibold mb-2">Bobot Default</p>
            <p class="text-3xl font-bold text-blue-900">{{ $kriteria->bobot_default }}%</p>
            <p class="text-blue-700 text-xs mt-2">Bobot ini digunakan sebagai nilai default dalam perhitungan</p>
        </div>

        <!-- Keterangan -->
        @if($kriteria->keterangan)
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4">📝 Keterangan</h2>
            <div class="bg-gray-50 rounded-lg p-6 border-l-4 border-gray-300">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $kriteria->keterangan }}</p>
            </div>
        </div>
        @endif

        <!-- Metadata -->
        <div class="border-t pt-6 text-sm text-gray-600">
            <p><strong>Dibuat:</strong> {{ $kriteria->created_at->format('d M Y \p\u\k\u\l H:i') }}</p>
            <p><strong>Terakhir diubah:</strong> {{ $kriteria->updated_at->format('d M Y \p\u\k\u\l H:i') }}</p>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex gap-4 mt-8">
            <a href="{{ route('kriteria.index') }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition">
                Lihat Semua Kriteria
            </a>

            <!-- Tombol Delete hanya untuk user yang terautentikasi (Admin) -->
            @if(auth()->check())
            <form method="POST" action="{{ route('kriteria.destroy', $kriteria) }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kriteria ini? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-lg hover:bg-red-700 transition">
                    🗑️ Hapus Kriteria
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
