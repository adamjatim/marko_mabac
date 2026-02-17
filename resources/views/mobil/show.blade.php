@extends('layouts.app')

@section('title', $mobil->merk . ' ' . $mobil->model)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="mb-8">
            <a href="{{ route('mobil.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">← Kembali ke Daftar</a>
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800">{{ $mobil->merk }} {{ $mobil->model }}</h1>
                    <p class="text-gray-600 mt-2">{{ $mobil->tahun }} | {{ $mobil->tipe }}</p>
                </div>
                
                <!-- Tombol Edit hanya untuk user yang terautentikasi (Admin) -->
                @if(auth()->check())
                    <a href="{{ route('admin.mobil.edit', $mobil) }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                        ✎️ Edit Mobil
                    </a>
                @endif
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-8 mb-8">
            @if($mobil->gambar)
                <img src="{{ $mobil->gambar }}" alt="{{ $mobil->merk }} {{ $mobil->model }}" class="w-full h-full object-cover rounded-lg shadow-md">
            @else
                <div class="bg-linear-to-r from-blue-500 to-blue-600 rounded-lg h-96 flex items-center justify-center text-white text-8xl">
                    🚗
                </div>
            @endif

            <div class="space-y-4">
                <div class="border-b pb-4">
                    <p class="text-gray-600">Harga Baru</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($mobil->harga_baru, 0, ',', '.') }}</p>
                </div>
                <div class="border-b pb-4">
                    <p class="text-gray-600">Harga Jual Kembali (Estimasi)</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($mobil->harga_jual_kembali, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Pajak Tahunan</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($mobil->pajak, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-bold text-gray-800 mb-4">⚙️ Spesifikasi Mesin</h3>
                <ul class="space-y-3">
                    <li class="flex justify-between">
                        <span class="text-gray-600">Performa</span>
                        <span class="font-semibold">{{ $mobil->performa }} HP</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-600">Efisiensi Bahan Bakar</span>
                        <span class="font-semibold">{{ $mobil->efisiensi_bahan_bakar }} km/l</span>
                    </li>
                </ul>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-bold text-gray-800 mb-4">🛡️ Fitur & Kenyamanan</h3>
                <ul class="space-y-3">
                    <li class="flex justify-between">
                        <span class="text-gray-600">Fitur Keamanan</span>
                        <span class="font-semibold">{{ $mobil->fitur_keamanan }} fitur</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-gray-600">Fitur Kenyamanan</span>
                        <span class="font-semibold">{{ $mobil->fitur_kenyamanan }} fitur</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('perhitungan.index') }}" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition">
                Gunakan dalam Perhitungan
            </a>
            <a href="{{ route('mobil.index') }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition">
                Lihat Mobil Lain
            </a>
        </div>
    </div>
</div>
@endsection
