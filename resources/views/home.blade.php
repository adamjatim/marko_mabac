@extends('layouts.app')

@section('title', 'Beranda - SPK Pemilihan Mobil')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold text-gray-800 mb-4">Sistem Pendukung Keputusan Pemilihan Mobil</h1>
        <p class="text-xl text-gray-600 mb-8">Menggunakan Metode MABAC (Multi-Attributive Border Approximation Area Comparison)</p>
        <div class="flex gap-4 justify-center">
            <a href="{{ route('mobil.index') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700">
                Lihat Daftar Mobil
            </a>
            <a href="{{ route('perhitungan.index') }}" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700">
                Mulai Perhitungan
            </a>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-8 mt-16">
        <div class="bg-white p-8 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="text-xl font-bold text-blue-600 mb-4">📊 Lihat Data Mobil</h3>
            <p class="text-gray-600">Jelajahi koleksi lengkap mobil dengan spesifikasi dan harga terkini.</p>
        </div>
        <div class="bg-white p-8 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="text-xl font-bold text-green-600 mb-4">🎯 Perhitungan MABAC</h3>
            <p class="text-gray-600">Dapatkan rekomendasi mobil terbaik dengan metode analisis keputusan terstruktur.</p>
        </div>
        <div class="bg-white p-8 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="text-xl font-bold text-purple-600 mb-4">⚙️ Kelola Kriteria</h3>
            <p class="text-gray-600">Sesuaikan bobot kriteria sesuai preferensi dan kebutuhan Anda.</p>
        </div>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-600 p-8 mt-16 rounded">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Tentang Metode MABAC</h2>
        <p class="text-gray-700">MABAC adalah metode pengambilan keputusan multi-kriteria yang membandingkan alternatif berdasarkan jarak dari Border Approximation Area. Metode ini efektif untuk membantu pengambilan keputusan yang kompleks dengan banyak kriteria.</p>
    </div>
</div>
@endsection
