@extends('layouts.app')

@section('title', 'Kelola Data Mobil')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800">Kelola Data Mobil</h1>
        <a href="{{ route('admin.mobil.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
            + Tambah Mobil Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="px-6 py-4 text-left">Gambar</th>
                        <th class="px-6 py-4 text-left">Mobil</th>
                        <th class="px-6 py-4 text-left">Tipe</th>
                        <th class="px-6 py-4 text-right">Harga Baru</th>
                        <th class="px-6 py-4 text-right">Jarak Tempuh</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mobils as $mobil)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            @if($mobil->gambar)
                                <img src="{{ $mobil->gambar }}" alt="{{ $mobil->merk }}" class="h-12 w-16 object-cover rounded">
                            @else
                                <span class="text-2xl">🚗</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $mobil->merk }} {{ $mobil->model }}</div>
                            <div class="text-sm text-gray-600">{{ $mobil->tahun }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm">{{ $mobil->tipe }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold">
                            Rp {{ number_format($mobil->harga_baru, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right">{{ $mobil->efisiensi_bahan_bakar }} km/l</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.mobil.edit', $mobil) }}" class="text-blue-600 hover:text-blue-800 mr-4">Edit</a>
                            <form method="POST" action="{{ route('admin.mobil.destroy', $mobil) }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-600">
                            Belum ada data mobil. <a href="{{ route('admin.mobil.create') }}" class="text-blue-600 hover:text-blue-800">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
