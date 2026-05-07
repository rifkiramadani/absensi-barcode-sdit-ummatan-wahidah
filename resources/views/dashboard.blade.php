@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col gap-8">

    {{-- Welcome Message --}}
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-extrabold text-gray-800">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-sm font-medium text-gray-500">Rekapitulasi kehadiran siswa SDIT Ummatan Wahidah hari ini.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        {{-- Card Total Siswa --}}
        <div class="flex items-center gap-5 p-6 transition bg-white border border-gray-100 shadow-sm rounded-3xl hover:shadow-md">
            <div class="flex items-center justify-center text-2xl text-[#773DCE] rounded-2xl w-14 h-14 bg-purple-50">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="flex flex-col">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total Siswa</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $totalSiswa }} <span class="text-xs font-bold text-gray-400">Anak</span></h3>
            </div>
        </div>

        {{-- Card Total Kelas --}}
        <div class="flex items-center gap-5 p-6 transition bg-white border border-gray-100 shadow-sm rounded-3xl hover:shadow-md">
            <div class="flex items-center justify-center text-2xl text-emerald-600 rounded-2xl w-14 h-14 bg-emerald-50">
                <i class="fa-solid fa-school"></i>
            </div>
            <div class="flex flex-col">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total Kelas</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $totalKelas }} <span class="text-xs font-bold text-gray-400">Kelas</span></h3>
            </div>
        </div>

        {{-- Card Total Absensi --}}
        <div class="flex items-center gap-5 p-6 transition bg-white border border-gray-100 shadow-sm rounded-3xl hover:shadow-md">
            <div class="flex items-center justify-center text-2xl text-amber-600 rounded-2xl w-14 h-14 bg-amber-50">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            <div class="flex flex-col">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total Rekap</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $totalAbsensi }} <span class="text-xs font-bold text-gray-400">Data</span></h3>
            </div>
        </div>
    </div>

    {{-- Filter & Chart Grid --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- Filter Section --}}
        <div class="flex flex-col gap-6 lg:col-span-1">
            <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-3xl h-fit">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-extrabold text-gray-800">
                        <i class="fa-solid fa-magnifying-glass text-[#773DCE]"></i> Filter Data</h2>
                    @if(request('tanggal'))
                        <a href="{{ route('dashboard') }}" class="text-[10px] font-bold text-red-500 uppercase hover:underline">Reset</a>
                    @endif
                </div>

                <form method="GET" action="{{ route('dashboard') }}" class="space-y-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tanggal Spesifik</label>
                        <input type="date" name="tanggal" value="{{ $tanggalSelected }}"
                            class="w-full p-3.5 text-sm font-bold text-gray-700 border-gray-100 bg-gray-50 rounded-2xl focus:ring-2 focus:ring-[#773DCE] focus:bg-white outline-none transition">
                    </div>
                    <button type="submit" class="w-full bg-[#773DCE] text-white py-4 rounded-2xl font-bold text-sm shadow-lg shadow-purple-100 hover:bg-[#622eb1] transition transform active:scale-95">
                        <i class="mr-2 fa-solid fa-filter"></i> Terapkan
                    </button>
                </form>
            </div>
        </div>

        {{-- Chart Section --}}
        <div class="lg:col-span-2">
            <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-3xl">
                <div class="flex flex-col gap-1 mb-8">
                    <h2 class="text-lg font-extrabold text-gray-800">
                        <i class="fa-solid fa-chart-simple text-[#773DCE]"></i> Diagram Kehadiran</h2>
                    <p class="text-xs font-bold text-[#773DCE]">{{ \Carbon\Carbon::parse($tanggalSelected)->format('d F Y') }}</p>
                </div>

                <div class="w-full h-[320px]">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Telat', 'Pulang', 'Selesai'],
                datasets: [{
                    data: [
                        @json($chartData['hadir']),
                        @json($chartData['telat']),
                        @json($chartData['pulang']),
                        @json($chartData['selesai'])
                    ],
                    backgroundColor: ['#773DCE', '#F43F5E', '#3B82F6', '#94A3B8'],
                    borderWidth: 0,
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 30,
                            font: { size: 12, weight: 'bold' }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
