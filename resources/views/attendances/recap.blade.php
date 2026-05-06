@extends('layouts.app')

@section('content')
    <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-3xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Rekapitulasi Absensi</h2>
            <p class="text-sm text-gray-500">Gunakan filter untuk hasil yang lebih spesifik</p>
        </div>

        @if (session('success'))
            <div class="p-4 mb-4 text-green-700 bg-green-100 border-l-4 border-green-500 rounded-r-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col gap-6 mb-8">
            <!-- Form Filter Kompleks -->
            <form action="{{ route('attendance.recap') }}" method="GET"
                class="flex flex-wrap items-end gap-4 p-5 rounded-2xl bg-gray-50">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">Periode</label>
                    <select name="filter" class="text-sm border-gray-200 rounded-xl focus:ring-blue-500">
                        <option value="daily" {{ request('filter') == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ request('filter') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        <option value="quarterly" {{ request('filter') == 'quarterly' ? 'selected' : '' }}>Triwulan</option>
                        <option value="yearly" {{ request('filter') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">Pilih Kelas</label>
                    <select name="school_class_id" class="text-sm border-gray-200 rounded-xl focus:ring-blue-500">
                        <option value="">Semua Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ request('school_class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="text-sm border-gray-200 rounded-xl focus:ring-blue-500">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="text-sm border-gray-200 rounded-xl focus:ring-blue-500">
                </div>

                <button type="submit"
                    class="px-6 py-2 text-sm font-bold text-white transition bg-blue-600 shadow-md rounded-xl hover:bg-blue-700 shadow-blue-200">
                    Terapkan Filter
                </button>

                <a href="{{ route('attendance.export', request()->all()) }}"
                    class="flex items-center gap-2 px-6 py-2 text-sm font-bold text-white transition bg-green-600 shadow-md rounded-xl hover:bg-green-700 shadow-green-200">
                    Export Excel
                </a>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="overflow-hidden border border-gray-100 rounded-2xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-bold text-gray-400 uppercase bg-gray-50">
                        <th class="p-4">Siswa</th>
                        <th class="p-4">Kelas</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4 text-center">Masuk / Pulang</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-100">
                    @forelse($attendances as $item)
                        <tr class="transition hover:bg-gray-50/50">
                            <td class="p-4">
                                <p class="font-bold text-gray-800">{{ $item->student->name }}</p>
                                <p class="text-xs text-gray-400">{{ $item->student->nisn }}</p>
                            </td>
                            <td class="p-4">{{ $item->student->schoolClass->name }}</td>
                            <td class="p-4">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                            <td class="p-4 font-mono text-center">
                                <span class="text-blue-600">{{ $item->check_in ?? '--:--' }}</span>
                                <span class="mx-1 text-gray-300">|</span>
                                <span class="text-orange-600">{{ $item->check_out ?? '--:--' }}</span>
                            </td>
                            <td class="p-4">
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-black uppercase
                                    {{ $item->status == 'Hadir' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('attendance.destroy', $item) }}" method="POST"
                                    onsubmit="return confirm('Hapus data absensi ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-red-400 transition hover:text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 italic text-center text-gray-400">Tidak ada data absensi
                                ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $attendances->links() }}
        </div>
    </div>
@endsection
