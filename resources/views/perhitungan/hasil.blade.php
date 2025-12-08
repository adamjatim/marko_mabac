@extends('layouts.app')

@section('title', 'Hasil Perhitungan MABAC')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-2">Hasil Perhitungan MABAC</h1>
    <p class="text-gray-600 mb-8">Berikut adalah peringkat mobil berdasarkan analisis MABAC</p>
    
    <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-blue-800"><strong>📊 Analisis didasarkan pada {{ count($results) }} mobil yang dipilih</strong></p>
        <p class="text-sm text-blue-700 mt-2">Untuk mengubah mobil yang dianalisis, <a href="{{ route('perhitungan.index') }}" class="underline hover:text-blue-900">klik di sini</a></p>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-linear-to-r from-blue-600 to-blue-700 text-white">
                        <th class="px-6 py-4 text-center font-bold">Peringkat</th>
                        <th class="px-6 py-4 text-left font-bold">Mobil</th>
                        <th class="px-6 py-4 text-left font-bold">Spesifikasi</th>
                        <th class="px-6 py-4 text-right font-bold">Skor MABAC</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                    <tr class="border-b hover:bg-gray-50 transition @if($result['rank'] === 1) bg-yellow-50 @endif">
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center justify-center">
                                @if($result['rank'] === 1)
                                    <span class="text-3xl">🥇</span>
                                @elseif($result['rank'] === 2)
                                    <span class="text-3xl">🥈</span>
                                @elseif($result['rank'] === 3)
                                    <span class="text-3xl">🥉</span>
                                @else
                                    <span class="inline-block bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">
                                        {{ $result['rank'] }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="mb-3">
                                @if($result['mobil']->gambar)
                                    <img src="{{ $result['mobil']->gambar }}" alt="{{ $result['mobil']->merk }}" class="h-20 w-32 object-cover rounded-lg border border-gray-300">
                                @else
                                    <div class="h-20 w-32 bg-gray-300 rounded-lg flex items-center justify-center text-2xl border border-gray-300">🚗</div>
                                @endif
                            </div>
                            <div class="font-semibold text-gray-800 text-lg">
                                {{ $result['mobil']->merk }} {{ $result['mobil']->model }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $result['mobil']->tahun }} | {{ $result['mobil']->tipe }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm space-y-1">
                                <p><strong>Harga:</strong> Rp {{ number_format($result['mobil']->harga_baru, 0, ',', '.') }}</p>
                                <p><strong>Jarak Tempuh:</strong> {{ $result['mobil']->jarak_tempuh }} km/l</p>
                                <p><strong>Fitur:</strong> K:{{ $result['mobil']->fitur_keamanan }} | Ny:{{ $result['mobil']->fitur_kenyamanan }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-3xl font-bold text-green-600">
                                {{ number_format($result['score'], 2) }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                @if($result['rank'] === 1)
                                    (Tertinggi)
                                @elseif($result['rank'] === count($results))
                                    (Terendah)
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-600">
                            Tidak ada data hasil perhitungan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 grid md:grid-cols-2 gap-6">
        <div class="bg-green-50 border-l-4 border-green-600 p-6 rounded">
            <h3 class="text-lg font-bold text-gray-800 mb-3">🏆 Rekomendasi Terbaik</h3>
            @php $top = $results[0]; @endphp
            
            @if($top['mobil']->gambar)
                <img src="{{ $top['mobil']->gambar }}" alt="{{ $top['mobil']->merk }}" class="w-full h-40 object-cover rounded-lg mb-4 border border-green-300">
            @else
                <div class="w-full h-40 bg-gray-300 rounded-lg flex items-center justify-center text-5xl mb-4 border border-green-300">🚗</div>
            @endif
            
            <div>
                <p class="font-semibold text-xl text-gray-800">{{ $top['mobil']->merk }} {{ $top['mobil']->model }}</p>
                <p class="text-sm text-gray-600 mb-2">{{ $top['mobil']->tahun }} | {{ $top['mobil']->tipe }}</p>
                <p class="text-gray-600 mt-2">
                    Mobil ini memiliki skor MABAC tertinggi ({{ number_format($top['score'], 2) }}) berdasarkan kriteria yang Anda tentukan.
                </p>
            </div>
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-600 p-6 rounded">
            <h3 class="text-lg font-bold text-gray-800 mb-3">💡 Catatan Penting</h3>
            <p class="text-gray-600 text-sm">
                Hasil ini berdasarkan metode MABAC dengan bobot yang Anda tentukan. Semakin tinggi skor, semakin sesuai dengan preferensi Anda. Untuk hasil yang berbeda, ubah bobot kriteria dan lakukan perhitungan ulang.
            </p>
        </div>
    </div>

    <div class="mt-8 flex gap-4">
        <a href="{{ route('perhitungan.index') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
            Hitung Ulang
        </a>
        <a href="{{ route('mobil.index') }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition">
            Lihat Daftar Mobil
        </a>
    </div>
</div>
@endsection
