@extends('layouts.app')

@section('title', 'Perhitungan MABAC')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">Perhitungan MABAC</h1>
        <p class="text-gray-600 mb-8">Atur bobot kriteria sesuai preferensi Anda. Sistem akan secara otomatis menormalisasi nilai agar total = 1.0</p>

        <!-- Contoh Penggunaan -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">📋 Contoh Penggunaan Bobot:</h3>
            <div class="grid md:grid-cols-3 gap-4 text-sm">
                <div class="bg-white p-3 rounded border">
                    <h4 class="font-semibold text-green-700 mb-2">✅ Semua Default</h4>
                    <p class="text-gray-600">Kosongkan semua field</p>
                    <code class="text-xs bg-gray-100 p-1 rounded block mt-1">0.22, 0.14, 0.16, 0.08, 0.18, 0.12, 0.10</code>
                </div>
                <div class="bg-white p-3 rounded border">
                    <h4 class="font-semibold text-green-700 mb-2">✅ Raw Numbers</h4>
                    <p class="text-gray-600">Gunakan angka sederhana</p>
                    <code class="text-xs bg-gray-100 p-1 rounded block mt-1">9, 5, 6, 5, 2, 4, 7</code>
                </div>
                <div class="bg-white p-3 rounded border">
                    <h4 class="font-semibold text-green-700 mb-2">✅ Desimal Langsung</h4>
                    <p class="text-gray-600">Gunakan nilai desimal</p>    
                    <code class="text-xs bg-gray-100 p-1 rounded block mt-1">0.25, 0.20, 0.15, 0.10, 0.12, 0.10, 0.08</code>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('perhitungan.calculate') }}" class="space-y-8">
            @csrf

            <div class="bg-blue-50 p-6 rounded-lg">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan Bobot Kriteria</h2>

                <div class="space-y-6">
                    @foreach($kriterias as $kriteria)
                    <div class="flex items-center justify-between gap-6 pb-4 border-b">
                        <div class="flex-1">
                            <label for="bobot_{{ $kriteria->id }}" class="block font-semibold text-gray-800">
                                {{ $kriteria->nama }}
                            </label>
                            <p class="text-sm text-gray-600 mt-1">
                                @if($kriteria->tipe === 'benefit')
                                    Semakin tinggi semakin baik |
                                @else
                                    Semakin rendah semakin baik |
                                @endif
                                Bobot Default: {{ $kriteria->bobot_default }}
                            </p>
                        </div>
                        <div class="w-32">
                            <input
                                type="number"
                                id="bobot_{{ $kriteria->id }}"
                                name="bobot_{{ $kriteria->id }}"
                                value="{{ $kriteria->bobot_default }}"
                                data-default="{{ $kriteria->bobot_default }}"
                                data-kriteria="{{ $kriteria->nama }}"
                                min="0"
                                step="0.01"
                                placeholder="Kosong = default ({{ $kriteria->bobot_default }})"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <strong>💡 Tips:</strong> 
                        Nilai bobot tidak harus dijumlahkan menjadi 1. Sistem akan secara otomatis menormalisasi nilai-nilai yang Anda masukkan.
                    </p>
                </div>
                
                <!-- Real-time Preview -->
                <div id="bobotPreview" class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg" style="display: none;">
                    <h4 class="font-semibold text-gray-800 mb-3">🔄 Preview Hasil Normalisasi:</h4>
                    <div id="previewContent" class="text-sm text-gray-700 font-mono space-y-1">
                        <!-- Preview content will be inserted here -->
                    </div>
                    <div class="mt-3 text-xs text-gray-500">
                        Total akan otomatis = 1.0000 setelah normalisasi
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="font-bold text-gray-800 mb-4">Pilih Mobil yang Akan Dianalisis</h3>
                <p class="text-sm text-gray-600 mb-6">Minimal pilih 2 mobil untuk melakukan perhitungan</p>

                <!-- Filter Section -->
                <div class="mb-6 p-4 bg-white border border-gray-300 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-4">🔍 Filter Mobil</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Merk Filter -->
                        <div>
                            <label for="filter_merk" class="block text-sm font-semibold text-gray-700 mb-2">
                                Merk
                            </label>
                            <select
                                id="filter_merk"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onchange="filterMobils()">
                                <option value="">-- Semua Merk --</option>
                                @foreach($merks as $merk)
                                <option value="{{ $merk }}">{{ $merk }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <label for="filter_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tipe
                            </label>
                            <select
                                id="filter_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onchange="filterMobils()">
                                <option value="">-- Semua Tipe --</option>
                                @foreach($types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
                        <p class="text-sm text-blue-800">💡 <strong>Catatan:</strong> Mobil yang sudah dipilih akan tetap ikut perhitungan meskipun tersembunyi oleh filter.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex gap-4 mb-4">
                        <button type="button" onclick="selectAllMobils()" class="text-sm bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            Pilih Semua
                        </button>
                        <button type="button" onclick="deselectAllMobils()" class="text-sm bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">
                            Batal Pilih Semua
                        </button>
                    </div>

                    <div id="mobils-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($mobils as $mobil)
                        <label class="mobil-item flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-100 cursor-pointer transition" data-merk="{{ $mobil->merk }}" data-type="{{ $mobil->tipe }}">
                            <input
                                type="checkbox"
                                name="mobil_ids[]"
                                value="{{ $mobil->id }}"
                                class="mobil-checkbox w-5 h-5 text-blue-600 rounded focus:ring-blue-500 cursor-pointer"
                                checked
                            >
                            <div class="ml-4 flex-1">
                                <div class="font-semibold text-gray-800">{{ $mobil->merk }} {{ $mobil->model }}</div>
                                <div class="text-sm text-gray-600">{{ $mobil->tipe }} | Tahun: {{ $mobil->tahun }}</div>
                                @if($mobil->gambar)
                                    <img src="{{ $mobil->gambar }}" alt="{{ $mobil->merk }}" class="mt-2 h-24 w-32 object-cover rounded">
                                @else
                                    <div class="mt-2 h-24 w-32 bg-gray-300 rounded flex items-center justify-center text-2xl">🚗</div>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <span id="selected-count">{{ count($mobils) }}</span> mobil dipilih
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold" onclick="return validateMobilSelection()">
                    Hitung Rekomendasi
                </button>
                <a href="{{ route('home') }}" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <div class="mt-12 bg-blue-50 border-l-4 border-blue-600 p-8 rounded">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Tentang Metode MABAC</h3>
        <p class="text-gray-700 mb-4">
            MABAC (Multi-Attributive Border Approximation Area Comparison) adalah metode pengambilan keputusan multi-kriteria yang membandingkan setiap alternatif dengan Border Approximation Area (BAA).
        </p>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>Normalisasi data ke skala 1-5</li>
            <li>Hitung matriks tertimbang</li>
            <li>Tentukan Border Approximation Area</li>
            <li>Hitung jarak setiap alternatif dari BAA</li>
            <li>Urutkan berdasarkan skor tertinggi</li>
        </ul>
    </div>
</div>

<script>
    // Filter mobils by merk and type
    function filterMobils() {
        const selectedMerk = document.getElementById('filter_merk').value;
        const selectedType = document.getElementById('filter_type').value;

        const mobilItems = document.querySelectorAll('.mobil-item');
        mobilItems.forEach(item => {
            const merk = item.getAttribute('data-merk');
            const type = item.getAttribute('data-type');

            const merkMatch = !selectedMerk || merk === selectedMerk;
            const typeMatch = !selectedType || type === selectedType;

            if (merkMatch && typeMatch) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Update selected count
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.mobil-checkbox:checked').length;
        document.getElementById('selected-count').textContent = checked;
    }

    // Select all visible mobils
    function selectAllMobils() {
        document.querySelectorAll('.mobil-item').forEach(item => {
            if (item.style.display !== 'none') {
                const checkbox = item.querySelector('.mobil-checkbox');
                checkbox.checked = true;
            }
        });
        updateSelectedCount();
    }

    // Deselect all visible mobils
    function deselectAllMobils() {
        document.querySelectorAll('.mobil-item').forEach(item => {
            if (item.style.display !== 'none') {
                const checkbox = item.querySelector('.mobil-checkbox');
                checkbox.checked = false;
            }
        });
        updateSelectedCount();
    }

    // Validate minimum selection before submit
    function validateMobilSelection() {
        const checked = document.querySelectorAll('.mobil-checkbox:checked').length;
        if (checked < 2) {
            alert('Minimal pilih 2 mobil untuk melakukan perhitungan MABAC');
            return false;
        }
        return true;
    }

    // Update count on checkbox change
    document.querySelectorAll('.mobil-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Initialize count on page load
    document.addEventListener('DOMContentLoaded', updateSelectedCount);
    
    // Real-time weight preview
    function updateWeightPreview() {
        const weightInputs = document.querySelectorAll('input[name^="bobot_"]');
        const preview = document.getElementById('bobotPreview');
        const previewContent = document.getElementById('previewContent');
        
        let weights = [];
        let hasAnyInput = false;
        let allEmpty = true;
        
        // Collect input values
        weightInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            const defaultValue = parseFloat(input.getAttribute('data-default') || 0.1);
            
            if (input.value.trim() !== '') {
                allEmpty = false;
            }
            
            weights.push({
                id: input.name,
                label: input.closest('.flex').querySelector('label').textContent.trim(),
                input: input.value.trim() === '' ? '' : value,
                default: defaultValue,
                hasInput: input.value.trim() !== ''
            });
            
            if (value > 0) hasAnyInput = true;
        });
        
        if (!hasAnyInput && allEmpty) {
            preview.style.display = 'none';
            return;
        }
        
        // Calculate normalized weights
        let totalWeight = 0;
        weights.forEach(w => {
            const useValue = w.hasInput ? w.input : w.default;
            totalWeight += useValue;
        });
        
        // Show preview
        preview.style.display = 'block';
        previewContent.innerHTML = '';
        
        weights.forEach(w => {
            const useValue = w.hasInput ? w.input : w.default;
            const normalized = totalWeight > 0 ? (useValue / totalWeight) : 0;
            const percentage = (normalized * 100).toFixed(2);
            
            const source = w.hasInput ? '(input)' : '(default)';
            const div = document.createElement('div');
            div.innerHTML = `${w.label}: ${useValue} → ${normalized.toFixed(4)} (${percentage}%) ${source}`;
            previewContent.appendChild(div);
        });
        
        const totalDiv = document.createElement('div');
        totalDiv.innerHTML = `<strong>Total sebelum normalisasi: ${totalWeight.toFixed(4)} → 1.0000</strong>`;
        totalDiv.style.marginTop = '10px';
        totalDiv.style.paddingTop = '8px';
        totalDiv.style.borderTop = '1px solid #d1d5db';
        totalDiv.style.color = '#059669';
        previewContent.appendChild(totalDiv);
    }
    
    // Initialize weight preview
    document.addEventListener('DOMContentLoaded', function() {
        const weightInputs = document.querySelectorAll('input[name^="bobot_"]');
        
        // Store default values
        weightInputs.forEach(input => {
            input.setAttribute('data-default', input.value);
        });
        
        // Add change and input listeners
        weightInputs.forEach(input => {
            input.addEventListener('input', updateWeightPreview);
            input.addEventListener('change', updateWeightPreview);
        });
        
        // Initial preview
        updateWeightPreview();
    });
</script>
@endsection
