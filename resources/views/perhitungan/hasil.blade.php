@extends('layouts.app')

@section('title', 'Hasil Perhitungan MABAC')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-2">Hasil Perhitungan MABAC</h1>
    <p class="text-gray-600 mb-4">Detail alur perhitungan dan peringkat mobil berdasarkan analisis MABAC</p>
    
    <!-- Weight Info -->
    @if(isset($weightInfo))
    <div class="mb-6 p-4 rounded-lg border {{ $weightInfo['usedDefault'] ? 'bg-blue-50 border-blue-200' : ($weightInfo['allCustom'] ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200') }}">
        <div class="flex items-center gap-2">
            @if($weightInfo['usedDefault'])
                <span class="text-2xl">⚖️</span>
                <span class="font-semibold text-blue-900">Menggunakan Bobot Default</span>
                <span class="text-sm text-blue-700">(Semua field bobot kosong)</span>
            @elseif($weightInfo['allCustom'])
                <span class="text-2xl">🎯</span>
                <span class="font-semibold text-green-900">Menggunakan Bobot Custom</span>
                <span class="text-sm text-green-700">(Semua kriteria diisi, total input: {{ number_format($weightInfo['totalInputBeforeNorm'], 2) }} → dinormalisasi ke 1.0000)</span>
            @else
                <span class="text-2xl">🔄</span>
                <span class="font-semibold text-yellow-900">Menggunakan Bobot Campuran</span>
                <span class="text-sm text-yellow-700">({{ $weightInfo['inputCount'] }}/{{ count($kriterias) }} kriteria diisi + default untuk yang kosong)</span>
            @endif
        </div>
    </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="mb-8 border-b border-gray-200">
        <div class="flex gap-4 overflow-x-auto">
            <button onclick="showTab('hasil')" class="tab-btn active px-6 py-3 font-semibold text-blue-600 border-b-2 border-blue-600 whitespace-nowrap">📊 Hasil Akhir</button>
            <button onclick="showTab('weights')" class="tab-btn px-6 py-3 font-semibold text-gray-600 border-b-2 border-transparent whitespace-nowrap">⚖️ Bobot Kriteria</button>
            <button onclick="showTab('matrix')" class="tab-btn px-6 py-3 font-semibold text-gray-600 border-b-2 border-transparent whitespace-nowrap">📋 Matriks Awal</button>
            <button onclick="showTab('normalized')" class="tab-btn px-6 py-3 font-semibold text-gray-600 border-b-2 border-transparent whitespace-nowrap">📈 Normalisasi</button>
            <button onclick="showTab('weighted')" class="tab-btn px-6 py-3 font-semibold text-gray-600 border-b-2 border-transparent whitespace-nowrap">✖️ Pembobotan</button>
            <button onclick="showTab('baa')" class="tab-btn px-6 py-3 font-semibold text-gray-600 border-b-2 border-transparent whitespace-nowrap">🎯 BAA</button>
            <button onclick="showTab('qmatrix')" class="tab-btn px-6 py-3 font-semibold text-gray-600 border-b-2 border-transparent whitespace-nowrap">📍 Q Matrix</button>
        </div>
    </div>

    <!-- TAB 1: HASIL AKHIR -->
    <div id="hasil" class="tab-content flex flex-col">
        <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-blue-800"><strong>📊 Analisis didasarkan pada {{ count($results) }} mobil yang dipilih dan {{ count($kriterias) }} kriteria aktif</strong></p>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
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
                                    <p><strong>Efisiensi Bahan Bakar:</strong> {{ $result['mobil']->efisiensi_bahan_bakar }} km/l</p>
                                    <p><strong>Fitur:</strong> K:{{ $result['mobil']->fitur_keamanan }} | Ny:{{ $result['mobil']->fitur_kenyamanan }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-3xl font-bold text-green-600">
                                    {{ number_format($result['score'], 4) }}
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
                        Mobil ini memiliki skor MABAC tertinggi ({{ number_format($top['score'], 4) }}) berdasarkan kriteria yang Anda tentukan.
                    </p>
                </div>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-600 p-6 rounded">
                <h3 class="text-lg font-bold text-gray-800 mb-3">💡 Catatan Penting</h3>
                <p class="text-gray-600 text-sm mb-3">
                    Hasil ini berdasarkan metode MABAC dengan bobot yang Anda tentukan. Semakin tinggi skor, semakin sesuai dengan preferensi Anda.
                </p>
                <p class="text-gray-600 text-sm">
                    Lihat tab lain untuk memahami detail alur perhitungan dari awal hingga akhir.
                </p>
            </div>
        </div>
    </div>

    <!-- TAB 2: BOBOT KRITERIA -->
    <div id="weights" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="px-6 py-4 text-left">Kriteria</th>
                            <th class="px-6 py-4 text-center">Tipe</th>
                            <th class="px-6 py-4 text-right">Bobot Input</th>
                            <th class="px-6 py-4 text-right">Bobot Ternormalisasi</th>
                            <th class="px-6 py-4 text-left">Penjelasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalWeight = array_sum($weights); @endphp
                        @foreach($kriterias as $kriteria)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $kriteria->nama }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($kriteria->tipe === 'benefit')
                                        bg-green-100 text-green-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($kriteria->tipe) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono">
                                @if(isset($weightInfo['rawInputs'][$kriteria->id]))
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded">{{ $weightInfo['rawInputs'][$kriteria->id] }}</span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ number_format($kriteria->bobot_default, 3) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-blue-600">{{ number_format($weights[$kriteria->id], 4) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if(isset($weightInfo['rawInputs'][$kriteria->id]))
                                    {{ $weightInfo['rawInputs'][$kriteria->id] }} ÷ {{ number_format($weightInfo['totalInputBeforeNorm'], 2) }} = {{ number_format($weights[$kriteria->id], 4) }}
                                    <span class="text-green-600 font-semibold">(Custom)</span>
                                @else
                                    {{ $kriteria->bobot_default }} ÷ {{ number_format($weightInfo['totalInputBeforeNorm'], 2) }} = {{ number_format($weights[$kriteria->id], 4) }}
                                    <span class="text-gray-500">(Default)</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-yellow-800 text-sm"><strong>📌 Catatan:</strong> Bobot ternormalisasi adalah bobot input dibagi dengan total semua bobot sehingga jumlahnya sama dengan 1.0</p>
        </div>
    </div>

    <!-- TAB 3: MATRIKS AWAL -->
    <div id="matrix" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="w-full min-w-max">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="px-6 py-4 text-left font-bold">Mobil</th>
                        @foreach($kriterias as $kriteria)
                        <th class="px-6 py-4 text-center font-bold ">
                            <div class="whitespace-nowrap">{{ $kriteria->nama }}</div>
                            <div class="text-xs font-normal">({{ $kriteria->tipe === 'benefit' ? 'B' : 'C' }})</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($mobils as $mobil)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $mobil->merk }} {{ $mobil->model }}</td>
                        @foreach($kriterias as $kriteria)
                        <td class="px-6 py-4 text-center font-mono">
                            {{ number_format($matrix[$mobil->id][$kriteria->id], 2, ',', '.') }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach

                    <!-- Row: N. Max -->
                    <tr class="bg-green-100 border-t-2 border-green-600 font-bold">
                        <td class="px-6 py-4 font-bold text-green-800">N. Max</td>
                        @foreach($kriterias as $kriteria)
                        <td class="px-6 py-4 text-center font-mono text-green-800">
                            {{ number_format($min_max_values['max'][$kriteria->id], 2, ',', '.') }}
                        </td>
                        @endforeach
                    </tr>

                    <!-- Row: N. Min -->
                    <tr class="bg-red-100 border-b-2 border-red-600 font-bold">
                        <td class="px-6 py-4 font-bold text-red-800">N. Min</td>
                        @foreach($kriterias as $kriteria)
                        <td class="px-6 py-4 text-center font-mono text-red-800">
                            {{ number_format($min_max_values['min'][$kriteria->id], 2, ',', '.') }}
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-yellow-800 text-sm"><strong>📌 Catatan:</strong>
                <br>• Matriks keputusan awal yang berisikan data nilai kriteria masing-masing mobil
                <br>• <strong>N. Max:</strong> Nilai maksimum untuk setiap kriteria (digunakan dalam normalisasi)
                <br>• <strong>N. Min:</strong> Nilai minimum untuk setiap kriteria (digunakan dalam normalisasi)
                <br>• Indikator: <strong>B =</strong> Benefit (semakin tinggi semakin baik), <strong>C =</strong> Cost (semakin rendah semakin baik)
            </p>
        </div>
    </div>

    <!-- TAB 4: NORMALISASI -->
    <div id="normalized" class="tab-content hidden ">
        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="w-auto">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="px-6 py-4 text-left font-bold">Mobil</th>
                        @foreach($kriterias as $kriteria)
                        <th class="px-6 py-4 text-center font-bold">
                            <div class="whitespace-nowrap">{{ substr($kriteria->nama, 0, 15) }}</div>
                            <div class="text-xs font-normal">({{ $kriteria->tipe === 'benefit' ? 'B' : 'C' }})</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($mobils as $mobil)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $mobil->merk }} {{ $mobil->model }}</td>
                        @foreach($kriterias as $kriteria)
                        <td class="px-6 py-4 text-center font-mono text-blue-600 font-semibold">
                            {{ number_format($normalized[$mobil->id][$kriteria->id], 4) }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 space-y-3">
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-blue-800 text-sm font-semibold mb-2">📐 Formula Normalisasi Min-Max (Rentang 1-5):</p>
                <div class="bg-white p-3 rounded border border-blue-300 text-sm font-mono space-y-2">
                    <p><span class="text-green-700"><strong>Benefit</strong> (semakin tinggi semakin baik):</span><br>t<sub>ij</sub> = ((X<sub>ij</sub> - X<sub>min</sub>) / (X<sub>max</sub> - X<sub>min</sub>)) × 4 + 1</p>
                    <p><span class="text-red-700"><strong>Cost</strong> (semakin rendah semakin baik):</span><br>t<sub>ij</sub> = ((X<sub>max</sub> - X<sub>ij</sub>) / (X<sub>max</sub> - X<sub>min</sub>)) × 4 + 1</p>
                </div>
            </div>
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-yellow-800 text-sm"><strong>📌 Catatan:</strong>
                    <br>• Normalisasi mengubah nilai mentah ke skala 1-5
                    <br>• Indikator: <strong>B =</strong> Benefit, <strong>C =</strong> Cost
                    <br>• Semua kriteria akan memiliki skala yang sama setelah normalisasi
                </p>
            </div>
        </div>
    </div>

    <!-- TAB 5: PEMBOBOTAN -->
    <div id="weighted" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="w-full min-w-max">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="px-6 py-4 text-left font-bold">Mobil</th>
                        @foreach($kriterias as $kriteria)
                        <th class="px-6 py-4 text-center font-bold">
                            <div>{{ substr($kriteria->nama, 0, 15) }}</div>
                            <div class="text-xs font-normal">{{ $kriteria->tipe === 'benefit' ? 'B' : 'C' }} • w={{ number_format($weights[$kriteria->id], 4) }}</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($mobils as $mobil)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $mobil->merk }} {{ $mobil->model }}</td>
                        @foreach($kriterias as $kriteria)
                        <td class="px-6 py-4 text-center font-mono text-green-600 font-semibold">
                            {{ number_format($weighted[$mobil->id][$kriteria->id], 4) }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 space-y-3">
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 text-sm font-semibold mb-2">✖️ Rumus Pembobotan (Matriks V):</p>
                <div class="bg-white p-3 rounded border border-green-300 text-sm font-mono">
                    <p>V<sub>ij</sub> = Nilai Normalisasi<sub>ij</sub> × W<sub>j</sub> (bobot kriteria)</p>
                </div>
            </div>
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-yellow-800 text-sm"><strong>📌 Catatan:</strong>
                    <br>• Nilai terbobot = nilai normalisasi × bobot ternormalisasi kriteria
                    <br>• Indikator: <strong>B =</strong> Benefit, <strong>C =</strong> Cost
                    <br>• Ini adalah hasil sebelum dibandingkan dengan BAA
                </p>
            </div>
        </div>
    </div>

    <!-- TAB 6: BAA (Border Approximation Area) -->
    <div id="baa" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Border Approximation Area (BAA / G)</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-purple-600 text-white">
                            <th class="px-6 py-4 text-left">Kriteria</th>
                            <th class="px-6 py-4 text-center">Tipe</th>
                            <th class="px-6 py-4 text-left">Proses Perhitungan</th>
                            <th class="px-6 py-4 text-right">Hasil BAA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriterias as $kriteria)
                        @php
                            $weighted_values = array_column($weighted, $kriteria->id);
                            $values_str = implode(' + ', array_map(function($v) { return number_format($v, 4); }, $weighted_values));
                            $sum = array_sum($weighted_values);
                            $count = count($weighted_values);
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ $kriteria->nama }} (K{{ $loop->index + 1 }})
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    @if($kriteria->tipe === 'benefit')
                                        bg-green-100 text-green-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif">
                                    {{ $kriteria->tipe === 'benefit' ? 'Benefit' : 'Cost' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-left font-mono text-sm">
                                <div class="bg-gray-50 p-3 rounded border border-gray-300">
                                    <p>({{ $values_str }}) / {{ $count }}</p>
                                    <p class="mt-2 text-gray-600">= {{ number_format($sum, 4) }} / {{ $count }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-mono font-bold text-purple-600 text-lg">
                                    {{ number_format($baa[$kriteria->id], 4) }}
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6 space-y-3">
            <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
                <p class="text-purple-800 text-sm font-semibold mb-2">🎯 Rumus Border Approximation Area:</p>
                <div class="bg-white p-3 rounded border border-purple-300 text-sm font-mono">
                    <p>B<sub>j</sub> = (1/m) × Σ(V<sub>ij</sub>)</p>
                    <p class="text-xs text-gray-600 mt-2">BAA adalah RATA-RATA tertimbang dari setiap kriteria (berlaku untuk semua tipe)</p>
                </div>
            </div>
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-yellow-800 text-sm"><strong>📌 Catatan:</strong>
                    <br>• BAA = Border Approximation Area / Zona Batas / Garis Pembanding
                    <br>• Ini adalah RATA-RATA dari nilai terbobot pada setiap kriteria
                    <br>• Digunakan sebagai referensi untuk menentukan apakah alternatif berada di atas atau di bawah rata-rata
                </p>
            </div>
        </div>
    </div>

    <!-- TAB 7: Q MATRIX -->
    <div id="qmatrix" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
            <table class="w-full min-w-max">
                <thead>
                    <tr class="bg-orange-600 text-white">
                        <th class="px-6 py-4 text-left font-bold">Mobil</th>
                        @foreach($kriterias as $kriteria)
                        <th class="px-6 py-4 text-center font-bold">
                            <div>{{ substr($kriteria->nama, 0, 15) }}</div>
                            <div class="text-xs font-normal">(ID: {{ $kriteria->id }})</div>
                        </th>
                        @endforeach
                        <th class="px-6 py-4 text-center font-bold bg-orange-700">Total Skor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mobils as $mobil)
                    @php $score = array_sum($qMatrix[$mobil->id]); @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $mobil->merk }} {{ $mobil->model }}</td>
                        @foreach($kriterias as $kriteria)
                        <td class="px-6 py-4 text-center font-mono text-orange-600 font-semibold">
                            {{ number_format($qMatrix[$mobil->id][$kriteria->id], 4) }}
                        </td>
                        @endforeach
                        <td class="px-6 py-4 text-center font-mono font-bold text-orange-700 bg-orange-50">
                            {{ number_format($score, 4) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 space-y-3">
            <div class="p-4 bg-orange-50 border border-orange-200 rounded-lg">
                <p class="text-orange-800 text-sm font-semibold mb-2">📍 Rumus Matriks Kedekatan (Q Matrix):</p>
                <div class="bg-white p-3 rounded border border-orange-300 text-sm font-mono">
                    <p>Q<sub>ij</sub> = V<sub>ij</sub> - B<sub>j</sub></p>
                    <p class="text-xs text-gray-600 mt-2">(Formula SAMA untuk semua tipe kriteria, baik benefit maupun cost)</p>
                </div>
            </div>
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-yellow-800 text-sm"><strong>📌 Catatan:</strong>
                    <br>• Q Matrix = Matriks Kedekatan (jarak dari BAA)
                    <br>• Nilai Q positif: alternatif lebih unggul dari rata-rata pada kriteria tersebut
                    <br>• Nilai Q negatif: alternatif lebih buruk dari rata-rata pada kriteria tersebut
                    <br>• <strong>Total Skor</strong> = jumlah semua nilai Q untuk setiap mobil → digunakan untuk ranking final
                </p>
            </div>
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

<script>
function showTab(tabName) {
    // Hide all tabs
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => content.classList.add('hidden'));
    contents.forEach(content => content.classList.remove('flex'));
    contents.forEach(content => content.classList.remove('flex-col'));

    // Remove active class from all buttons
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => {
        btn.classList.remove('text-blue-600', 'border-blue-600');
        btn.classList.add('text-gray-600', 'border-transparent');
    });

    // Show selected tab
    document.getElementById(tabName).classList.remove('hidden');
    document.getElementById(tabName).classList.add('flex');
    document.getElementById(tabName).classList.add('flex-col');

    // Add active class to clicked button
    event.target.classList.remove('text-gray-600', 'border-transparent');
    event.target.classList.add('text-blue-600', 'border-blue-600');
}
</script>
@endsection
