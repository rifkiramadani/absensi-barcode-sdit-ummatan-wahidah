@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col gap-8">

    {{-- Welcome --}}
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-extrabold text-gray-800">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-sm font-medium text-gray-500">Rekapitulasi kehadiran SDIT Ummatan Wahidah — {{ \Carbon\Carbon::parse($tanggalSelected)->translatedFormat('d F Y') }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">

        <div class="flex items-center gap-4 p-5 transition bg-white border border-gray-100 shadow-sm rounded-3xl hover:shadow-md">
            <div class="flex items-center justify-center w-12 h-12 text-xl text-[#773DCE] bg-purple-50 rounded-2xl">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total Siswa</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $totalSiswa }}</h3>
            </div>
        </div>

        <div class="flex items-center gap-4 p-5 transition bg-white border border-gray-100 shadow-sm rounded-3xl hover:shadow-md">
            <div class="flex items-center justify-center w-12 h-12 text-xl text-emerald-600 bg-emerald-50 rounded-2xl">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total Guru</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $totalGuru }}</h3>
            </div>
        </div>

        <div class="flex items-center gap-4 p-5 transition bg-white border border-gray-100 shadow-sm rounded-3xl hover:shadow-md">
            <div class="flex items-center justify-center w-12 h-12 text-xl text-blue-600 bg-blue-50 rounded-2xl">
                <i class="fa-solid fa-school"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total Kelas</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $totalKelas }}</h3>
            </div>
        </div>

        <div class="flex items-center gap-4 p-5 transition bg-white border border-gray-100 shadow-sm rounded-3xl hover:shadow-md">
            <div class="flex items-center justify-center w-12 h-12 text-xl text-amber-600 bg-amber-50 rounded-2xl">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Rekap Hari Ini</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $totalAbsensiSiswa + $totalAbsensiGuru }}</h3>
            </div>
        </div>

    </div>

    {{-- Filter + Chart Grid --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Filter --}}
        <div class="lg:col-span-1">
            <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-3xl">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-base font-extrabold text-gray-800">
                        <i class="fa-solid fa-filter text-[#773DCE] mr-1"></i> Filter Tanggal
                    </h2>
                    @if(request('tanggal'))
                        <a href="{{ route('dashboard') }}" class="text-[10px] font-bold text-red-500 uppercase hover:underline">Reset</a>
                    @endif
                </div>
                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col gap-3">
                    <input type="date" name="tanggal" value="{{ $tanggalSelected }}"
                        class="w-full p-3.5 text-sm font-bold text-gray-700 border border-gray-100 bg-gray-50 rounded-2xl focus:ring-2 focus:ring-[#773DCE] focus:bg-white outline-none transition">
                    <button type="submit"
                        class="w-full bg-[#773DCE] text-white py-3.5 rounded-2xl font-bold text-sm shadow-lg shadow-purple-100 hover:bg-[#622eb1] transition active:scale-95">
                        <i class="mr-2 fa-solid fa-magnifying-glass"></i> Terapkan
                    </button>
                </form>

                {{-- Ringkasan Keterangan Hari Ini --}}
                <div class="pt-5 mt-5 border-t border-gray-50">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Keterangan Hari Ini</p>
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between px-3 py-2 bg-blue-50 rounded-xl">
                            <span class="text-xs font-bold text-blue-600"><i class="mr-1 fa-solid fa-file-circle-check"></i> Izin</span>
                            <span class="text-xs font-black text-blue-700">{{ $chartSiswa['izin'] + $chartGuru['izin'] }} orang</span>
                        </div>
                        <div class="flex items-center justify-between px-3 py-2 bg-orange-50 rounded-xl">
                            <span class="text-xs font-bold text-orange-600"><i class="mr-1 fa-solid fa-kit-medical"></i> Sakit</span>
                            <span class="text-xs font-black text-orange-700">{{ $chartSiswa['sakit'] + $chartGuru['sakit'] }} orang</span>
                        </div>
                        <div class="flex items-center justify-between px-3 py-2 bg-red-50 rounded-xl">
                            <span class="text-xs font-bold text-red-600"><i class="mr-1 fa-solid fa-circle-xmark"></i> Alpa</span>
                            <span class="text-xs font-black text-red-700">{{ $chartSiswa['alpa'] + $chartGuru['alpa'] }} orang</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="flex flex-col gap-6 lg:col-span-2">

            {{-- Chart Siswa --}}
            <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-3xl">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-extrabold text-gray-800">
                            <i class="fa-solid fa-users text-[#773DCE] mr-1"></i> Kehadiran Siswa
                        </h2>
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $totalAbsensiSiswa }} dari {{ $totalSiswa }} siswa tercatat</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-2.5 py-1 text-[10px] font-black text-green-600 bg-green-50 border border-green-100 rounded-lg">
                            {{ $chartSiswa['hadir'] }} Hadir
                        </span>
                        <span class="px-2.5 py-1 text-[10px] font-black text-amber-600 bg-amber-50 border border-amber-100 rounded-lg">
                            {{ $chartSiswa['telat'] }} Telat
                        </span>
                        <span class="px-2.5 py-1 text-[10px] font-black text-blue-600 bg-blue-50 border border-blue-100 rounded-lg">
                            {{ $chartSiswa['pulang'] }} Pulang
                        </span>
                    </div>
                </div>
                <div class="w-full h-[220px]">
                    <canvas id="chartSiswa"></canvas>
                </div>
            </div>

            {{-- Chart Guru --}}
            <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-3xl">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-extrabold text-gray-800">
                            <i class="mr-1 fa-solid fa-chalkboard-user text-emerald-600"></i> Kehadiran Guru
                        </h2>
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $totalAbsensiGuru }} dari {{ $totalGuru }} guru tercatat</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-2.5 py-1 text-[10px] font-black text-green-600 bg-green-50 border border-green-100 rounded-lg">
                            {{ $chartGuru['hadir'] }} Hadir
                        </span>
                        <span class="px-2.5 py-1 text-[10px] font-black text-amber-600 bg-amber-50 border border-amber-100 rounded-lg">
                            {{ $chartGuru['telat'] }} Telat
                        </span>
                        <span class="px-2.5 py-1 text-[10px] font-black text-blue-600 bg-blue-50 border border-blue-100 rounded-lg">
                            {{ $chartGuru['pulang'] }} Pulang
                        </span>
                    </div>
                </div>
                <div class="w-full h-[220px]">
                    <canvas id="chartGuru"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const buatChart = (id, data, warna) => {
        const ctx = document.getElementById(id).getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Telat', 'Pulang', 'Izin', 'Sakit', 'Alpa'],
                datasets: [{
                    data: data,
                    backgroundColor: warna,
                    borderWidth: 0,
                    hoverOffset: 16
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 16,
                            font: { size: 11, weight: 'bold' }
                        }
                    }
                }
            }
        });
    };

    // Chart Siswa
    buatChart('chartSiswa',
        [
            {{ $chartSiswa['hadir'] }},
            {{ $chartSiswa['telat'] }},
            {{ $chartSiswa['pulang'] }},
            {{ $chartSiswa['izin'] }},
            {{ $chartSiswa['sakit'] }},
            {{ $chartSiswa['alpa'] }}
        ],
        ['#773DCE', '#F59E0B', '#3B82F6', '#60A5FA', '#FB923C', '#F87171']
    );

    // Chart Guru
    buatChart('chartGuru',
        [
            {{ $chartGuru['hadir'] }},
            {{ $chartGuru['telat'] }},
            {{ $chartGuru['pulang'] }},
            {{ $chartGuru['izin'] }},
            {{ $chartGuru['sakit'] }},
            {{ $chartGuru['alpa'] }}
        ],
        ['#10B981', '#F59E0B', '#3B82F6', '#60A5FA', '#FB923C', '#F87171']
    );

});
</script>
@endsection
