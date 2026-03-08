@extends('layouts.app')

@section('title', 'Pengaturan Bobot Kriteria')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-2">Pengaturan Bobot Kriteria</h1>
    <p class="text-gray-600 mb-8">Atur nilai bobot untuk setiap kriteria. Nilai akan dihitung secara otomatis dengan formula w = L / Total(L)</p>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <strong>Error:</strong>
            <ul class="list-disc ml-5 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <!-- Penjelasan -->
        <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">📋 Panduan Pengaturan Bobot</h3>
            
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div class="bg-white p-4 rounded border border-green-300">
                    <h4 class="font-semibold text-green-700 mb-2">✅ Opsi 1: Bobot Default</h4>
                    <p class="text-sm text-gray-700 mb-2">Kosongkan <strong>SEMUA</strong> field input</p>
                    <div class="text-xs text-gray-600 bg-gray-50 p-2 rounded">
                        Sistem otomatis menggunakan bobot default:<br>
                        Harga Baru: 0.22, Harga Jual: 0.14, dll.
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded border border-green-300">
                    <h4 class="font-semibold text-green-700 mb-2">✅ Opsi 2: Input Custom</h4>
                    <p class="text-sm text-gray-700 mb-2">Isi <strong>SEMUA</strong> field dengan angka</p>
                    <div class="text-xs text-gray-600 bg-gray-50 p-2 rounded">
                        Contoh: 9,5,6,5,2,4,7<br>
                        → Auto normalisasi: 9/38=0.2368, dst.
                    </div>
                </div>
            </div>
            
            <div class="grid md:grid-cols-2 gap-4">
                <div class="bg-red-50 p-3 rounded border border-red-300">
                    <h4 class="font-semibold text-red-700 mb-2">❌ Error: Sebagian Kosong</h4>
                    <p class="text-xs text-red-600">Tidak boleh ada field yang kosong jika input custom</p>
                </div>
                <div class="bg-yellow-50 p-3 rounded border border-yellow-300">
                    <h4 class="font-semibold text-yellow-700 mb-2">🔢 Formula: w = L / Σ(L)</h4>
                    <p class="text-xs text-yellow-600">Nilai akan dinormalisasi otomatis total = 1.0</p>
                </div>
            </div>
        </div>

        <form id="formBobot" method="POST" action="{{ route('admin.kriteria.hitung-bobot') }}">
            @csrf
            
            <div class="overflow-x-auto mb-8">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="px-4 py-3 text-left border">Kode</th>
                            <th class="px-4 py-3 text-left border">Nama Kriteria</th>
                            <th class="px-4 py-3 text-left border">Bobot Default</th>
                            <th class="px-4 py-3 text-center border">Nilai Input (L)</th>
                            <th class="px-4 py-3 text-center border" colspan="1">Perhitungan Bobot (w)</th>
                            <th class="px-4 py-3 text-center border">Hasil Bobot (Desimal)</th>
                        </tr>
                    </thead>
                    <tbody id="tableBobot">
                        @php
                            $totalNilaiInput = 0;
                            $kriteriaCount = count($kriterias);
                            
                            // Hitung total nilai input jika sudah ada data
                            foreach ($bobotData as $kriteria_id => $data) {
                                if ($data['nilai_input'] !== null) {
                                    $totalNilaiInput += $data['nilai_input'];
                                }
                            }
                        @endphp

                        @foreach($kriterias as $index => $kriteria)
                        @php
                            $bobotItem = $bobotData[$kriteria->id] ?? null;
                            $nilaiInput = $bobotItem['nilai_input'] ?? '';
                            $hasilBobot = $bobotItem['hasil_bobot'] ?? 0;
                            $totalPenyebut = $bobotItem['nilai_penyebut'] ?? 1;
                            $isDefault = $bobotItem['adalah_default'] ?? false;
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 border text-center font-semibold bg-gray-100">K{{ $index + 1 }}</td>
                            <td class="px-4 py-3 border font-medium text-gray-800">{{ $kriteria->nama }}</td>
                            <td class="px-4 py-3 border text-center">
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded">
                                    {{ number_format($kriteria->bobot_default, 4) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border text-center">
                                <input 
                                    type="number" 
                                    name="nilai_input[{{ $kriteria->id }}]" 
                                    class="form-input text-center w-full border border-gray-300 rounded px-2 py-2"
                                    step="0.01"
                                    min="0.01"
                                    value="{{ $nilaiInput }}"
                                    placeholder="Contoh: 9 atau 0.22"
                                    data-kriteria-id="{{ $kriteria->id }}"
                                    title="Masukkan angka mentah (contoh: 9) atau desimal (contoh: 0.22). Sistem akan normalisasi otomatis.">
                            </td>
                            <td class="px-4 py-3 border text-center">
                                <span class="inline-block bg-gray-100 px-3 py-2 rounded min-w-[100px]" 
                                      data-formula="{{ $kriteria->id }}">
                                    {{ $nilaiInput !== '' && $totalPenyebut > 0 ? ($nilaiInput . ' / ' . $totalPenyebut) : '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border text-center">
                                <input 
                                    type="text" 
                                    class="form-input text-center w-full border border-gray-300 rounded px-2 py-2 bg-yellow-50 font-mono"
                                    value="{{ $isDefault ? $hasilBobot . ' (Default)' : number_format($hasilBobot, 4) }}"
                                    data-original="{{ $isDefault ? $hasilBobot . ' (Default)' : number_format($hasilBobot, 4) }}"
                                    readonly>
                            </td>
                        </tr>
                        @endforeach

                        <!-- Row untuk Total -->
                        <tr class="bg-blue-50 font-bold">
                            <td colspan="3" class="px-4 py-3 border text-right">TOTAL</td>
                            <td class="px-4 py-3 border text-center">
                                <span id="totalNilaiInput" class="inline-block bg-blue-100 px-3 py-2 rounded">
                                    {{ $totalNilaiInput > 0 ? $totalNilaiInput : '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border text-center">-</td>
                            <td class="px-4 py-3 border text-center">
                                <span id="totalHasilBobot" class="inline-block bg-blue-100 px-3 py-2 rounded">
                                    -
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-4 justify-end">
                <a href="{{ route('admin.kriteria.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                    Batal
                </a>
                <form method="POST" action="{{ route('admin.kriteria.reset-bobot') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition" 
                            onclick="return confirm('Apakah Anda yakin ingin mereset semua bobot ke nilai default?')">
                        Reset ke Default
                    </button>
                </form>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Hitung Bobot
                </button>
            </div>
        </form>
    </div>

    <!-- Hasil Perhitungan (jika sudah dihitung) -->
    @if(isset($isCalculated) && $isCalculated)
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-green-900">✓ Perhitungan Berhasil</h3>
            <p class="text-green-800 text-sm mt-1">Semua nilai tervalidasi. Total bobot = 1.0000</p>
        </div>

        <div class="overflow-x-auto mb-8">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-green-600 text-white">
                        <th class="px-4 py-3 text-left border">Kode</th>
                        <th class="px-4 py-3 text-left border">Nama Kriteria</th>
                        <th class="px-4 py-3 text-center border">Nilai Input (L)</th>
                        <th class="px-4 py-3 text-center border">Perhitungan Bobot</th>
                        <th class="px-4 py-3 text-center border">Hasil Bobot (Desimal)</th>
                        <th class="px-4 py-3 text-center border">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalBobot = 0;
                    @endphp
                    @foreach($kriterias as $index => $kriteria)
                    @php
                        $data = $bobotData[$kriteria->id] ?? null;
                        $nilaiInput = $data['nilai_input'] ?? 0;
                        $hasilBobot = $data['hasil_bobot'] ?? 0;
                        $totalPenyebut = $data['nilai_penyebut'] ?? 1;
                        $totalBobot += $hasilBobot;
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 border text-center font-semibold bg-gray-100">K{{ $index + 1 }}</td>
                        <td class="px-4 py-3 border font-medium">{{ $kriteria->nama }}</td>
                        <td class="px-4 py-3 border text-center font-semibold">{{ $nilaiInput }}</td>
                        <td class="px-4 py-3 border text-center font-mono text-sm">{{ $nilaiInput }} / {{ $totalPenyebut }}</td>
                        <td class="px-4 py-3 border text-center font-mono text-lg font-bold text-green-700">
                            {{ number_format($hasilBobot, 4) }}
                        </td>
                        <td class="px-4 py-3 border text-center font-semibold">
                            {{ number_format($hasilBobot * 100, 2) }}%
                        </td>
                    </tr>
                    @endforeach

                    <tr class="bg-green-100 font-bold">
                        <td colspan="3" class="px-4 py-3 border text-right">TOTAL</td>
                        <td class="px-4 py-3 border text-center">{{ $bobotData[array_key_first((array)$bobotData)]['nilai_penyebut'] ?? 1 }}</td>
                        <td class="px-4 py-3 border text-center text-lg text-green-700">
                            {{ number_format($totalBobot, 4) }}
                        </td>
                        <td class="px-4 py-3 border text-center">100%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex gap-4 justify-end">
            <a href="{{ route('admin.kriteria.pengaturan-bobot') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                Ubah Lagi
            </a>
            <form method="POST" action="{{ route('admin.kriteria.simpan-bobot') }}" style="display: inline;">
                @csrf
                @foreach($bobotData as $kriteria_id => $data)
                    @if($data['nilai_input'] !== null)
                        <input type="hidden" name="nilai_input[{{ $kriteria_id }}]" value="{{ $data['nilai_input'] }}">
                    @endif
                @endforeach
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                    Simpan Pengaturan Bobot
                </button>
            </form>
        </div>
    </div>
    @elseif(isset($isCalculated) && !$isCalculated)
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="text-lg font-semibold text-red-900">✗ Perhitungan Gagal</h3>
            <p class="text-red-800 mt-2">{{ isset($error) ? $error : 'Terjadi kesalahan dalam perhitungan bobot.' }}</p>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formBobot = document.getElementById('formBobot');
    const inputs = document.querySelectorAll('input[name^="nilai_input"]');
    
    inputs.forEach(input => {
        input.addEventListener('change', updateCalculation);
    });

    function updateCalculation() {
        let totalInput = 0;
        let emptyCount = 0;
        let filledCount = 0;
        const values = [];

        inputs.forEach(input => {
            const val = input.value.trim();
            if (val === '') {
                emptyCount++;
            } else {
                filledCount++;
                const numVal = parseFloat(val);
                if (!isNaN(numVal) && numVal > 0) {
                    totalInput += numVal;
                    values.push(numVal);
                }
            }
        });

        // Update formula dan hasil bobot untuk setiap baris
        inputs.forEach((input, index) => {
            const val = input.value.trim();
            const formulaSpan = document.querySelector(`[data-formula="${input.dataset.kriteriaId}"]`);
            const hasilBobotInput = input.closest('tr').querySelector('input[readonly]');
            
            if (val === '') {
                // Kosong
                if (emptyCount === inputs.length) {
                    // Semua kosong - gunakan default
                    formulaSpan.textContent = '-';
                    // Reset hasil bobot ke nilai default jika ada
                    if (hasilBobotInput) {
                        const originalValue = hasilBobotInput.getAttribute('data-original') || hasilBobotInput.value;
                        hasilBobotInput.value = originalValue;
                    }
                } else if (filledCount > 0) {
                    // Ada yang diisi, ini error
                    formulaSpan.textContent = '❌ Ada yg kosong';
                    formulaSpan.style.color = 'red';
                    if (hasilBobotInput) {
                        hasilBobotInput.value = '❌ Error';
                    }
                }
            } else {
                const numVal = parseFloat(val);
                if (filledCount === inputs.length && totalInput > 0) {
                    // Semua terisi - hitung dan tampilkan hasil
                    formulaSpan.textContent = `${val} / ${totalInput}`;
                    formulaSpan.style.color = 'black';
                    
                    // Hitung bobot aktual dan update field hasil
                    if (hasilBobotInput && numVal > 0) {
                        const hasilBobot = numVal / totalInput;
                        hasilBobotInput.value = hasilBobot.toFixed(4);
                    }
                } else {
                    // Ada yang kosong
                    formulaSpan.textContent = '❌ Ada yg kosong';
                    formulaSpan.style.color = 'red';
                    if (hasilBobotInput) {
                        hasilBobotInput.value = '❌ Error';
                    }
                }
            }
        });

        // Update total
        const totalDisplay = document.getElementById('totalNilaiInput');
        const totalHasil = document.getElementById('totalHasilBobot');
        
        if (emptyCount === inputs.length) {
            totalDisplay.textContent = '-';
            totalHasil.textContent = '-';
        } else if (filledCount === inputs.length && totalInput > 0) {
            totalDisplay.textContent = totalInput;
            totalHasil.textContent = '1.0000';
        } else if (filledCount > 0 && filledCount < inputs.length) {
            totalDisplay.textContent = '❌ Error';
            totalHasil.textContent = '❌ Ada kriteria kosong';
        }
    }
});
</script>

@endsection
