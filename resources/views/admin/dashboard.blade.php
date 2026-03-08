@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Admin Dashboard</h1>

    <div class="grid md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Mobil</p>
                    <p class="text-3xl font-bold text-gray-800">{{ count($mobils ?? []) }}</p>
                </div>
                <span class="text-4xl">🚗</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Kriteria</p>
                    <p class="text-3xl font-bold text-gray-800">7</p>
                </div>
                <span class="text-4xl">⚙️</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Pengguna</p>
                    <p class="text-3xl font-bold text-gray-800">{{ Auth::user()->name }}</p>
                </div>
                <span class="text-4xl">👤</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-orange-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Sistem</p>
                    <p class="text-2xl font-bold text-gray-800">MABAC</p>
                </div>
                <span class="text-4xl">📊</span>
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">⚡ Akses Cepat</h2>
            <div class="space-y-3">
                <a href="{{ route('admin.mobil.index') }}" class="block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition text-center font-semibold">
                    Kelola Data Mobil
                </a>
                <a href="{{ route('admin.kriteria.index') }}" class="block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition text-center font-semibold">
                    Kelola Kriteria
                </a>
                <a href="{{ route('admin.kriteria.pengaturan-bobot') }}" class="block bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition text-center font-semibold">
                    ⚙️ Pengaturan Bobot Kriteria
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">📌 Informasi Sistem</h2>
            <ul class="space-y-2 text-gray-700">
                <li><strong>Versi Sistem:</strong> 1.0</li>
                <li><strong>Metode:</strong> MABAC</li>
                <li><strong>Database:</strong> SQLite</li>
                <li><strong>Framework:</strong> Laravel 12</li>
            </ul>
        </div>
    </div>
</div>
@endsection
