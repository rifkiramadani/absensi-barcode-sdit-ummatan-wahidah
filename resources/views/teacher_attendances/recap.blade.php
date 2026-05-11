@extends('layouts.app')

@section('title', 'Rekap Absensi Guru')

@section('content')
    <div x-data="{ show: false, keterangan: 'Izin', today: '{{ date('Y-m-d') }}' }">
        <div class="px-4 sm:px-6 lg:px-8">

            <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-2xl sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-2xl font-bold text-gray-800">Rekap Absensi Guru</h1>
                    <p class="mt-2 text-sm text-gray-500">Riwayat kehadiran seluruh guru SDIT Ummatan Wahidah.</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <button type="button" @click="show = true"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-blue-500 rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-600 transition-all">
                        <i class="fa-solid fa-pen-clip"></i> Input Keterangan Guru
                    </button>
                </div>
            </div>

            {{-- Filter --}}
            <div class="p-5 mt-6 bg-white border border-gray-100 shadow-sm rounded-2xl">
                <form action="{{ route('teacher_attendance.recap') }}" method="GET"
                    class="flex flex-wrap items-end gap-4">

                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Filter Periode</label>
                        <select name="filter" onchange="this.form.submit()"
                            class="py-2.5 pl-3 pr-8 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl focus:border-[#773DCE] appearance-none cursor-pointer">
                            <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="weekly" {{ $filter == 'weekly' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Tahun Ini</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="py-2.5 px-3 text-sm border border-gray-200 rounded-xl focus:border-[#773DCE]">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="py-2.5 px-3 text-sm border border-gray-200 rounded-xl focus:border-[#773DCE]">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cari Guru</label>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Nama atau NIP..."
                            class="py-2.5 px-3 text-sm border border-gray-200 rounded-xl focus:border-[#773DCE]">
                    </div>

                    <button type="submit"
                        class="px-5 py-2.5 bg-[#773DCE] text-white text-sm font-bold rounded-xl hover:bg-[#5e2faf] transition-all">
                        <i class="mr-1 fa-solid fa-filter"></i> Filter
                    </button>

                    @if ($search || $startDate || $endDate)
                        <a href="{{ route('teacher_attendance.recap') }}"
                            class="px-5 py-2.5 text-red-500 bg-red-50 rounded-xl hover:bg-red-100 text-sm font-bold">Reset</a>
                    @endif

                    <a href="{{ route('teacher_attendance.export', request()->query()) }}"
                        class="px-5 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl hover:bg-green-700 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-file-excel"></i> Export
                    </a>
                </form>
            </div>

            @if (session('success'))
                <div class="flex p-4 mt-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50">
                    <i class="mt-1 mr-3 text-lg fa-solid fa-circle-check"></i>
                    <div><span class="font-bold">Berhasil!</span> {{ session('success') }}</div>
                </div>
            @endif

            <div class="mt-6 overflow-x-auto shadow-sm ring-1 ring-gray-100 md:rounded-2xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-purple-50">
                        <tr>
                            <th class="py-4 pl-6 pr-3 text-left text-xs font-black text-[#773DCE] uppercase tracking-wider">
                                Guru</th>
                            <th class="px-3 py-4 text-left text-xs font-black text-[#773DCE] uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-3 py-4 text-center text-xs font-black text-[#773DCE] uppercase tracking-wider">Jam
                                Masuk</th>
                            <th class="px-3 py-4 text-center text-xs font-black text-[#773DCE] uppercase tracking-wider">Jam
                                Pulang</th>
                            <th class="px-3 py-4 text-center text-xs font-black text-[#773DCE] uppercase tracking-wider">
                                Status</th>
                            <th class="px-3 py-4 text-center text-xs font-black text-[#773DCE] uppercase tracking-wider">
                                Keterangan</th>
                            <th
                                class="py-4 pl-3 pr-6 text-right text-xs font-black text-[#773DCE] uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($attendances as $a)
                            <tr class="transition-colors hover:bg-gray-50/50">
                                <td class="py-4 pl-6 pr-3 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <img class="object-cover rounded-full w-9 h-9 ring-1 ring-gray-100"
                                            src="{{ $a->teacher->photo ? asset('storage/' . $a->teacher->photo) : asset('assets/images/photos/default-photo.svg') }}">
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">{{ $a->teacher->name }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $a->teacher->jabatan ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}
                                </td>
                                <td class="px-3 py-4 font-mono text-sm text-center text-gray-700 whitespace-nowrap">
                                    {{ $a->check_in ?? '-' }}
                                </td>
                                <td class="px-3 py-4 font-mono text-sm text-center text-gray-700 whitespace-nowrap">
                                    {{ $a->check_out ?? '-' }}
                                </td>
                                <td class="px-3 py-4 text-center whitespace-nowrap">
                                    @if ($a->status == 'Hadir')
                                        <span
                                            class="px-2.5 py-1 text-[10px] font-black text-green-600 border border-green-100 rounded-lg bg-green-50">HADIR</span>
                                    @elseif($a->status == 'Telat')
                                        <span
                                            class="px-2.5 py-1 text-[10px] font-black text-amber-600 border border-amber-100 rounded-lg bg-amber-50">TELAT</span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 text-[10px] font-black text-gray-400 border border-gray-100 rounded-lg bg-gray-50">-</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-center whitespace-nowrap">
                                    @if ($a->keterangan)
                                        @php $c = ['Izin'=>'blue','Sakit'=>'orange','Alpa'=>'red'][$a->keterangan] ?? 'gray'; @endphp
                                        <span
                                            class="px-2.5 py-1 text-[10px] font-black text-{{ $c }}-600 border border-{{ $c }}-100 rounded-lg bg-{{ $c }}-50">
                                            {{ strtoupper($a->keterangan) }}
                                        </span>
                                        @if ($a->catatan_keterangan)
                                            <div class="text-[9px] text-gray-400 mt-1">{{ $a->catatan_keterangan }}</div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-300">-</span>
                                    @endif
                                </td>
                                <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                    <form action="{{ route('teacher_attendance.destroy', $a->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button
                                            class="p-2 text-red-600 transition-all rounded-lg bg-red-50 hover:bg-red-100">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="mb-3 text-4xl text-gray-200 fa-solid fa-calendar-xmark"></i>
                                        <p class="text-sm font-medium text-gray-400">Tidak ada data absensi guru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $attendances->links() }}</div>
        </div>

        {{-- MODAL INPUT KETERANGAN GURU - MANDIRI, TIDAK BUTUH SCAN --}}
        <div x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
            @click.self="show = false">
            <div class="w-full max-w-md p-8 mx-4 bg-white shadow-2xl rounded-3xl">

                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 text-blue-500 bg-blue-50 rounded-xl">
                        <i class="text-lg fa-solid fa-pen-clip"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Input Keterangan Guru</h3>
                        <p class="text-xs text-gray-400">Izin / Sakit / Alpa — tanpa perlu scan kartu</p>
                    </div>
                </div>

                <form action="{{ route('teacher_attendance.keterangan') }}" method="POST" class="flex flex-col gap-4">
                    @csrf

                    {{-- Search Guru --}}
                    <div x-data="guruSearch()">
                        <label class="block mb-2 text-xs font-bold tracking-wider text-gray-500 uppercase">Pilih
                            Guru</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                <i class="text-xs fa-solid fa-magnifying-glass"></i>
                            </div>
                            <input type="text" x-model="search" @input="filter()" @focus="open = true"
                                @click.away="open = false" placeholder="Ketik nama guru..."
                                class="block w-full pl-9 border-gray-200 rounded-xl text-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100">
                        </div>

                        <input type="hidden" name="teacher_id" x-model="selectedId">

                        <div x-show="selectedName"
                            class="flex items-center gap-2 px-3 py-2 mt-2 border border-purple-100 bg-purple-50 rounded-xl">
                            <i class="fa-solid fa-circle-check text-[#773DCE] text-xs"></i>
                            <span class="text-sm font-bold text-[#773DCE]" x-text="selectedName"></span>
                            <button type="button" @click="clear()"
                                class="ml-auto text-xs text-red-400 hover:text-red-600">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div x-show="open && results.length > 0" x-cloak
                            class="relative z-[60] w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                            <template x-for="g in results" :key="g.id">
                                <div @click="select(g)"
                                    class="flex flex-col px-4 py-3 transition-colors border-b cursor-pointer hover:bg-purple-50 border-gray-50 last:border-0">
                                    <span class="text-sm font-bold text-gray-800" x-text="g.name"></span>
                                    <span class="text-[10px] text-gray-400" x-text="g.jabatan"></span>
                                </div>
                            </template>
                        </div>

                        <div x-show="open && search.length > 1 && results.length === 0" x-cloak
                            class="relative z-[60] w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-xl px-4 py-3 text-sm text-gray-400">
                            Guru tidak ditemukan.
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="block mb-2 text-xs font-bold tracking-wider text-gray-500 uppercase">Tanggal</label>
                        <input type="date" name="date" :value="today"
                            class="block w-full border-gray-200 rounded-xl text-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100"
                            required>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label
                            class="block mb-2 text-xs font-bold tracking-wider text-gray-500 uppercase">Keterangan</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label
                                class="flex flex-col items-center gap-2 p-3 transition-all border-2 cursor-pointer rounded-xl"
                                :class="keterangan === 'Izin' ? 'border-blue-400 bg-blue-50' :
                                    'border-gray-100 hover:border-blue-200'">
                                <input type="radio" name="keterangan" value="Izin" x-model="keterangan"
                                    class="sr-only">
                                <i class="text-xl text-blue-500 fa-solid fa-file-circle-check"></i>
                                <span class="text-xs font-bold text-blue-600">Izin</span>
                            </label>
                            <label
                                class="flex flex-col items-center gap-2 p-3 transition-all border-2 cursor-pointer rounded-xl"
                                :class="keterangan === 'Sakit' ? 'border-orange-400 bg-orange-50' :
                                    'border-gray-100 hover:border-orange-200'">
                                <input type="radio" name="keterangan" value="Sakit" x-model="keterangan"
                                    class="sr-only">
                                <i class="text-xl text-orange-500 fa-solid fa-kit-medical"></i>
                                <span class="text-xs font-bold text-orange-600">Sakit</span>
                            </label>
                            <label
                                class="flex flex-col items-center gap-2 p-3 transition-all border-2 cursor-pointer rounded-xl"
                                :class="keterangan === 'Alpa' ? 'border-red-400 bg-red-50' :
                                    'border-gray-100 hover:border-red-200'">
                                <input type="radio" name="keterangan" value="Alpa" x-model="keterangan"
                                    class="sr-only">
                                <i class="text-xl text-red-500 fa-solid fa-circle-xmark"></i>
                                <span class="text-xs font-bold text-red-600">Alpa</span>
                            </label>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="block mb-2 text-xs font-bold tracking-wider text-gray-500 uppercase">
                            Catatan <span class="font-normal normal-case">(opsional)</span>
                        </label>
                        <input type="text" name="catatan_keterangan" placeholder="Contoh: Izin rapat dinas"
                            class="block w-full border-gray-200 rounded-xl text-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100">
                    </div>

                    <div class="flex justify-end gap-3 mt-2">
                        <button type="button" @click="show = false"
                            class="px-6 py-2.5 text-sm font-bold text-gray-500 border border-gray-100 rounded-xl hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-8 py-2.5 text-sm font-bold text-white bg-[#773DCE] rounded-xl hover:bg-[#5e2faf] transition-all">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>{{-- tutup x-data --}}

    <script>
        // DATA GURU dari controller — tidak ada query di dalam blade
        const allGuruData = {!! json_encode($guruList) !!};

        function guruSearch() {
            return {
                search: '',
                selectedId: '',
                selectedName: '',
                open: false,
                results: [],

                filter() {
                    if (this.search.length < 1) {
                        this.results = [];
                        this.open = false;
                        return;
                    }
                    const q = this.search.toLowerCase();
                    this.results = allGuruData.filter(g =>
                        g.name.toLowerCase().includes(q) ||
                        g.jabatan.toLowerCase().includes(q)
                    ).slice(0, 8);
                    this.open = true;
                },

                select(g) {
                    this.selectedId = g.id;
                    this.selectedName = g.name + ' — ' + g.jabatan;
                    this.search = '';
                    this.open = false;
                    this.results = [];
                },

                clear() {
                    this.selectedId = '';
                    this.selectedName = '';
                    this.search = '';
                }
            }
        }
    </script>
@endsection
