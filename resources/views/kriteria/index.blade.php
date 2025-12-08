@extends('layouts.app')

@section('title', 'Kriteria Penilaian')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Kriteria Penilaian Mobil</h1>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="px-6 py-3 text-left">Kriteria</th>
                        <th class="px-6 py-3 text-left">Tipe</th>
                        <th class="px-6 py-3 text-left">Bobot Default</th>
                        <th class="px-6 py-3 text-left">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kriterias as $kriteria)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $kriteria->nama }}</td>
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
                        <td class="px-6 py-4">{{ $kriteria->bobot_default }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            @if($kriteria->tipe === 'benefit')
                                Semakin tinggi semakin baik
                            @else
                                Semakin rendah semakin baik
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 p-6 bg-blue-50 rounded-lg border-l-4 border-blue-600">
            <h3 class="font-bold text-gray-800 mb-2">💡 Informasi:</h3>
            <p class="text-gray-700">Bobot default dapat disesuaikan saat melakukan perhitungan. Kriteria dengan tipe "Benefit" berarti nilai lebih tinggi lebih baik, sedangkan "Cost" berarti nilai lebih rendah lebih baik.</p>
        </div>

        <div class="mt-8">
            <a href="{{ route('perhitungan.index') }}" class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition inline-block">
                Mulai Perhitungan MABAC
            </a>
        </div>
    </div>
</div>
@endsection
