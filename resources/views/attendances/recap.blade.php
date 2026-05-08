@extends('layouts.app')

@section('title', 'Rekapitulasi Absensi')

@section('content')
    <div class="p-8 bg-white border border-gray-100 shadow-sm rounded-[2rem]">
        <div class="flex flex-col justify-between gap-4 mb-8 md:flex-row md:items-center">
            <div>
                <h2 class="text-2xl font-black text-gray-800">Rekapitulasi Absensi</h2>
                <p class="text-sm font-medium text-gray-400">Pantau dan ekspor data kehadiran siswa secara periodik</p>
            </div>
            <div class="p-3 bg-purple-50 rounded-2xl text-[#773DCE]">
                <i class="text-2xl fa-solid fa-file-invoice"></i>
            </div>
        </div>

        @if (session('success'))
            <div class="flex p-4 mb-6 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50" role="alert">
                <i class="mt-0.5 mr-3 fa-solid fa-circle-check text-lg"></i>
                <div><span class="font-bold">Berhasil!</span> {{ session('success') }}</div>
            </div>
        @endif

        {{-- Section Filter --}}
        <div class="mb-8">
            <form action="{{ route('attendance.recap') }}" method="GET"
                class="grid grid-cols-1 gap-4 p-6 border border-gray-100 md:grid-cols-2 lg:flex lg:items-end rounded-2xl bg-gray-50">

                {{-- Tambahan Input Search --}}
                <div class="flex flex-col flex-1 gap-2 lg:min-w-[200px]">
                    <label class="text-[10px] font-black text-[#773DCE] uppercase tracking-widest ml-1">Cari Siswa</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                            <i class="text-xs fa-solid fa-magnifying-glass"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / NISN..."
                            class="w-full pl-9 text-sm border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] transition-all">
                    </div>
                </div>

                <div class="flex flex-col flex-1 gap-2">
                    <label class="text-[10px] font-black text-[#773DCE] uppercase tracking-widest ml-1">Periode</label>
                    <select name="filter" class="w-full text-sm border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] transition-all">
                        <option value="daily" {{ request('filter') == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ request('filter') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        <option value="yearly" {{ request('filter') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>

                <div class="flex flex-col flex-1 gap-2">
                    <label class="text-[10px] font-black text-[#773DCE] uppercase tracking-widest ml-1">Kelas</label>
                    <select name="school_class_id" class="w-full text-sm border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] transition-all">
                        <option value="">Semua Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" {{ request('school_class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Input Tanggal --}}
                <div class="flex flex-col flex-1 gap-2">
                    <label class="text-[10px] font-black text-[#773DCE] uppercase tracking-widest ml-1">Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full text-sm border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] transition-all">
                </div>

                <div class="flex flex-col flex-1 gap-2">
                    <label class="text-[10px] font-black text-[#773DCE] uppercase tracking-widest ml-1">Sampai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full text-sm border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] transition-all">
                </div>

                <div class="flex gap-2 lg:flex-none">
                    <button type="submit"
                        class="flex-1 lg:flex-none px-6 py-2.5 text-sm font-bold text-white transition bg-[#773DCE] shadow-lg shadow-purple-100 rounded-xl hover:bg-[#5e2faf] active:scale-95">
                        <i class="mr-1 fa-solid fa-filter"></i> Filter
                    </button>

                    {{-- Tombol Reset (Opsional tapi sangat berguna) --}}
                    @if(request()->anyFilled(['search', 'school_class_id', 'start_date', 'end_date']))
                        <a href="{{ route('attendance.recap') }}"
                        class="p-2.5 text-red-500 bg-red-50 rounded-xl hover:bg-red-100 transition-colors flex items-center justify-center">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif

                    <a href="{{ route('attendance.export', request()->all()) }}"
                        class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold text-white transition bg-green-600 shadow-lg shadow-green-100 rounded-xl hover:bg-green-700 active:scale-95">
                        <i class="fa-solid fa-file-excel"></i> Export
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="overflow-hidden border border-gray-100 shadow-sm rounded-2xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-purple-50">
                        <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider">Siswa</th>
                        <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider">Kelas</th>
                        <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider">Tanggal</th>
                        <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-center">Waktu Tap</th>
                        <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-600 divide-y divide-gray-100">
                    @forelse($attendances as $item)
                        <tr class="transition hover:bg-gray-50/50">
                            <td class="p-4">
                                <p class="font-bold text-gray-800">{{ $item->student->name }}</p>
                                <p class="text-[10px] font-mono text-gray-400 tracking-tighter uppercase">NISN: {{ $item->student->nisn }}</p>
                            </td>
                            <td class="p-4 font-medium text-gray-500">{{ $item->student->schoolClass->name }}</td>
                            <td class="p-4 font-medium">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                            <td class="p-4 text-center">
                                <div class="inline-flex items-center gap-2 px-3 py-1 border border-gray-100 rounded-lg bg-gray-50">
                                    <span class="font-mono font-bold text-[#773DCE]">{{ $item->check_in ?? '--:--' }}</span>
                                    <span class="text-gray-300">|</span>
                                    <span class="font-mono font-bold text-amber-600">{{ $item->check_out ?? '--:--' }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                @if($item->status == 'Hadir')
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-green-50 text-green-600 border border-green-100">HADIR</span>
                                @else
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-red-50 text-red-600 border border-red-100">TERLAMBAT</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('attendance.destroy', $item) }}" method="POST"
                                    onsubmit="return confirm('Hapus data absensi ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-gray-400 transition rounded-lg hover:text-red-500 hover:bg-red-50">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-16 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="mb-4 text-4xl text-gray-200 fa-solid fa-folder-open"></i>
                                    <p class="italic text-gray-400">Tidak ada data absensi ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $attendances->links() }}
        </div>
    </div>
@endsection
