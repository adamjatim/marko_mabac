@extends('layouts.app')

@section('title', 'Kriteria Penilaian')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Manajemen Kriteria Penilaian Mobil</h1>
        @auth
        <a href="{{ route('kriteria.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
            + Tambah Kriteria
        </a>
        @else
        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
            Login untuk Mengelola
        </a>
        @endauth
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-600 p-4 mb-6 rounded">
            <p class="text-red-800 font-semibold">Terjadi kesalahan:</p>
            <ul class="text-red-700 mt-2">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-600 p-4 mb-6 rounded">
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-blue-50 rounded-lg p-6 border-l-4 border-blue-600">
            <p class="text-blue-800 text-sm font-semibold">Total Kriteria</p>
            <p class="text-3xl font-bold text-blue-900">{{ $totalCount }}</p>
        </div>
        <div class="bg-green-50 rounded-lg p-6 border-l-4 border-green-600">
            <p class="text-green-800 text-sm font-semibold">Kriteria Aktif</p>
            <p class="text-3xl font-bold text-green-900">{{ $aktifCount }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="px-6 py-3 text-left">Nama Kriteria</th>
                        <th class="px-6 py-3 text-left">Tipe</th>
                        <th class="px-6 py-3 text-left">Bobot Default</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Keterangan</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kriterias as $kriteria)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-800">
                            <a href="{{ route('kriteria.show', $kriteria) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                {{ $kriteria->nama }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                @if($kriteria->tipe === 'benefit')
                                    bg-green-100 text-green-800
                                @else
                                    bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($kriteria->tipe) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $kriteria->bobot_default }}%</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('kriteria.toggleActive', $kriteria) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                @auth
                                <button type="submit" class="inline-block px-3 py-1 rounded-full text-sm font-semibold transition
                                    @if($kriteria->is_active)
                                        bg-green-100 text-green-800 hover:bg-yellow-100
                                    @else
                                        bg-gray-100 text-gray-800 hover:bg-green-100
                                    @endif">
                                    {{ $kriteria->is_active ? '✓ Aktif' : '✗ Nonaktif' }}
                                </button>
                                @else
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                    @if($kriteria->is_active)
                                        bg-green-100 text-green-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $kriteria->is_active ? '✓ Aktif' : '✗ Nonaktif' }}
                                </span>
                                @endauth
                            </form>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            {{ $kriteria->keterangan ? Str::limit($kriteria->keterangan, 50) : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @auth
                            <a href="{{ route('kriteria.edit', $kriteria) }}" class="text-blue-600 hover:text-blue-900 font-semibold mr-4">Edit</a>
                            <form action="{{ route('kriteria.destroy', $kriteria) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kriteria ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                            </form>
                            @else
                            <span class="text-gray-400 text-sm">Akses terbatas</span>
                            @endauth
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Belum ada kriteria. 
                            @auth
                            <a href="{{ route('kriteria.create') }}" class="text-blue-600 hover:text-blue-900 font-semibold">Tambah sekarang</a>
                            @else
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-900 font-semibold">Login untuk menambah</a>
                            @endauth
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Box -->
    <div class="mt-8 p-6 bg-blue-50 rounded-lg border-l-4 border-blue-600">
        <h3 class="font-bold text-gray-800 mb-2">💡 Panduan Penggunaan:</h3>
        <ul class="text-gray-700 space-y-1">
            <li>• <strong>Benefit:</strong> Nilai lebih tinggi lebih baik (misal: fitur keamanan)</li>
            <li>• <strong>Cost:</strong> Nilai lebih rendah lebih baik (misal: harga)</li>
            <li>• Klik status untuk mengaktifkan/menonaktifkan kriteria tanpa menghapusnya</li>
            <li>• Bobot default dapat disesuaikan saat melakukan perhitungan</li>
        </ul>
    </div>

    <div class="mt-8">
        <a href="{{ route('perhitungan.index') }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition inline-block">
            ← Kembali ke Perhitungan
        </a>
    </div>
</div>
@endsection
