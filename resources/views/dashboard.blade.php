@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col gap-8">

    {{-- Welcome Message --}}
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-sm text-gray-500">Berikut adalah ringkasan rekapitulasi data absensi sekolah.</p>
    </div>

    {{-- Stats Cards (JUMLAH SISWA, KELAS, REKAP) --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="bg-white p-6 rounded-2xl border border-[#EEEEEE] shadow-sm flex items-center gap-5">
            <div class="flex items-center justify-center text-2xl text-blue-600 rounded-full w-14 h-14 bg-blue-50">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="flex flex-col">
                <p class="text-xs text-[#7F8190] font-semibold uppercase tracking-wider">Total Siswa</p>
                <h3 class="mt-1 text-2xl font-bold text-gray-800">{{ $totalSiswa }} <span class="text-sm font-normal text-gray-500">Anak</span></h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-[#EEEEEE] shadow-sm flex items-center gap-5">
            <div class="flex items-center justify-center text-2xl text-green-600 rounded-full w-14 h-14 bg-green-50">
                <i class="fa-solid fa-school"></i>
            </div>
            <div class="flex flex-col">
                <p class="text-xs text-[#7F8190] font-semibold uppercase tracking-wider">Total Kelas</p>
                <h3 class="mt-1 text-2xl font-bold text-gray-800">{{ $totalKelas }} <span class="text-sm font-normal text-gray-500">Kelas</span></h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-[#EEEEEE] shadow-sm flex items-center gap-5">
            <div class="flex items-center justify-center text-2xl rounded-full w-14 h-14 bg-amber-50 text-amber-600">
                <i class="fa-solid fa-clipboard-user"></i>
            </div>
            <div class="flex flex-col">
                <p class="text-xs text-[#7F8190] font-semibold uppercase tracking-wider">Total Absen Terdata</p>
                <h3 class="mt-1 text-2xl font-bold text-gray-800">{{ $totalAbsensi }} <span class="text-sm font-normal text-gray-500">Rekap</span></h3>
            </div>
        </div>
    </div>

    {{-- Filter Per Tanggal --}}
    <div class="bg-white p-6 rounded-2xl border border-[#EEEEEE] shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="flex items-center gap-2 text-lg font-bold text-gray-800">🔍 Filter Tanggal</h2>
            @if(request('tanggal'))
                <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-red-500 hover:underline">Reset Hari Ini</a>
            @endif
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col items-end gap-4 sm:flex-row">
            <div class="flex flex-col flex-grow gap-2">
                <label class="text-xs font-bold text-gray-600 uppercase">Pilih Tanggal Spesifik</label>
                <input type="date" name="tanggal" value="{{ $tanggalSelected }}" class="w-full p-3 text-sm text-gray-700 border border-gray-200 bg-gray-50 rounded-xl focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit" class="w-full sm:w-auto bg-[#2B82FE] text-white px-8 py-3 rounded-xl font-semibold text-sm hover:bg-blue-700 transition duration-300 shadow-lg shadow-blue-100">
                Terapkan Filter
            </button>
        </form>
    </div>

    {{-- Chart Section --}}
    <div class="bg-white p-6 rounded-2xl border border-[#EEEEEE] shadow-sm w-full">
        <div class="flex flex-col gap-1 mb-6">
            <h2 class="text-lg font-bold text-gray-800">📊 Diagram Status Kehadiran</h2>
            <p class="text-xs text-gray-400">Data Tanggal: {{ \Carbon\Carbon::parse($tanggalSelected)->format('d M Y') }}</p>
        </div>

        <div class="w-full h-[350px] relative">
            <canvas id="attendanceChart"></canvas>
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
                labels: ['Hadir', 'Telat', 'Sudah Pulang', 'Selesai'],
                datasets: [{
                    data: [
                        @json($chartData['hadir']),
                        @json($chartData['telat']),
                        @json($chartData['pulang']),
                        @json($chartData['selesai'])
                    ],
                    backgroundColor: ['#22c55e', '#ef4444', '#3b82f6', '#6b7280'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endsection
