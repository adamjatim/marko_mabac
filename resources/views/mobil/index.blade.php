@extends('layouts.app')

@section('title', 'Daftar Mobil')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Daftar Mobil Tersedia</h1>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($mobils as $mobil)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            @if($mobil->gambar)
                <img src="{{ $mobil->gambar }}" alt="{{ $mobil->merk }} {{ $mobil->model }}" class="w-full h-48 object-cover">
            @else
                <div class="bg-linear-to-r from-blue-500 to-blue-600 h-48 flex items-center justify-center">
                    <span class="text-white text-5xl">🚗</span>
                </div>
            @endif
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $mobil->merk }} {{ $mobil->model }}</h2>
                <p class="text-gray-600 mb-4">
                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded text-sm">{{ $mobil->tipe }}</span>
                    <span class="ml-2 text-gray-500">{{ $mobil->tahun }}</span>
                </p>

                <div class="space-y-2 mb-4 text-sm text-gray-600">
                    <p><strong>Harga Baru:</strong> Rp {{ number_format($mobil->harga_baru, 0, ',', '.') }}</p>
                    <p><strong>Jarak Tempuh:</strong> {{ $mobil->jarak_tempuh }} km/l</p>
                    <p><strong>Fitur Keamanan:</strong> {{ $mobil->fitur_keamanan }}</p>
                </div>

                <a href="{{ route('mobil.show', $mobil) }}" class="block bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700 transition">
                    Lihat Detail
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
