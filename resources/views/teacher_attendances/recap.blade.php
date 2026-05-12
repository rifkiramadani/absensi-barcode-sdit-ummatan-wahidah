@extends('layouts.app')

@section('title', 'Rekap Absensi Guru')

@section('content')
<div x-data="guruKeteranganModal()" @buka-edit-guru.window="
    editMode      = true;
    namaEdit      = $event.detail.nama;
    teacherIdEdit = $event.detail.teacherId;
    tanggalEdit   = $event.detail.tanggal;
    keterangan    = $event.detail.keteranganAwal || 'Izin';
    catatanEdit   = $event.detail.catatan;
    show          = true;
">
<div class="p-8 bg-white border border-gray-100 shadow-sm rounded-[2rem]">

    <div class="flex flex-col justify-between gap-4 mb-8 md:flex-row md:items-center">
        <div>
            <h2 class="text-2xl font-black text-gray-800">Rekapitulasi Absensi Guru</h2>
            <p class="text-sm font-medium text-gray-400">Pantau dan ekspor data kehadiran guru</p>
        </div>
        <button type="button" @click="editMode = false; show = true"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-blue-500 rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-600 transition-all">
            <i class="fa-solid fa-pen-clip"></i> Input Keterangan Guru
        </button>
    </div>

    @if(session('success'))
        <div class="flex p-4 mb-6 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50">
            <i class="mt-0.5 mr-3 fa-solid fa-circle-check text-lg"></i>
            <div><span class="font-bold">Berhasil!</span> {{ session('success') }}</div>
        </div>
    @endif

    {{-- Filter --}}
    <div class="mb-8">
        <form action="{{ route('teacher_attendance.recap') }}" method="GET"
            class="grid grid-cols-1 gap-4 p-6 border border-gray-100 md:grid-cols-2 lg:flex lg:items-end rounded-2xl bg-gray-50">

            <div class="flex flex-col flex-1 gap-2 lg:min-w-[180px]">
                <label class="text-[10px] font-black text-[#773DCE] uppercase tracking-widest ml-1">Cari Guru</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                        <i class="text-xs fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nama / NIP..."
                        class="w-full pl-9 text-sm border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] transition-all">
                </div>
            </div>

            <div class="flex flex-col flex-1 gap-2">
                <label class="text-[10px] font-black text-[#773DCE] uppercase tracking-widest ml-1">Periode</label>
                <select name="filter" class="w-full text-sm border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] transition-all">
                    <option value="daily"   {{ $filter == 'daily'   ? 'selected' : '' }}>Harian</option>
                    <option value="weekly"  {{ $filter == 'weekly'  ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly"  {{ $filter == 'yearly'  ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>

            <div class="flex flex-col flex-1 gap-2">
                <label class="text-[10px] font-black text-[#773DCE] uppercase tracking-widest ml-1">Keterangan</label>
                <select name="keterangan" class="w-full text-sm border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] transition-all">
                    <option value="">Semua</option>
                    <option value="Hadir"       {{ $keterangan == 'Hadir'       ? 'selected' : '' }}>Hadir / Telat</option>
                    <option value="Tidak Hadir" {{ $keterangan == 'Tidak Hadir' ? 'selected' : '' }}>Semua Tidak Hadir</option>
                    <option value="Izin"        {{ $keterangan == 'Izin'        ? 'selected' : '' }}>Izin</option>
                    <option value="Sakit"       {{ $keterangan == 'Sakit'       ? 'selected' : '' }}>Sakit</option>
                    <option value="Alpa"        {{ $keterangan == 'Alpa'        ? 'selected' : '' }}>Alpa</option>
                </select>
            </div>

            <div class="flex flex-col flex-1 gap-2">
                <label class="text-[10px] font-black text-[#773DCE] uppercase tracking-widest ml-1">Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full text-sm border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] transition-all">
            </div>

            <div class="flex flex-col flex-1 gap-2">
                <label class="text-[10px] font-black text-[#773DCE] uppercase tracking-widest ml-1">Sampai</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full text-sm border-gray-200 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] transition-all">
            </div>

            <div class="flex gap-2 lg:flex-none">
                <button type="submit"
                    class="flex-1 lg:flex-none px-6 py-2.5 text-sm font-bold text-white bg-[#773DCE] shadow-lg shadow-purple-100 rounded-xl hover:bg-[#5e2faf] active:scale-95 transition">
                    <i class="mr-1 fa-solid fa-filter"></i> Filter
                </button>
                @if($search || $startDate || $endDate || $keterangan)
                    <a href="{{ route('teacher_attendance.recap') }}"
                        class="p-2.5 text-red-500 bg-red-50 rounded-xl hover:bg-red-100 transition flex items-center justify-center" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
                <a href="{{ route('teacher_attendance.export', request()->query()) }}"
                    class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-green-600 shadow-lg shadow-green-100 rounded-xl hover:bg-green-700 active:scale-95 transition">
                    <i class="fa-solid fa-file-excel"></i> Export
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="overflow-hidden border border-gray-100 shadow-sm rounded-2xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-purple-50">
                    <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider">Guru</th>
                    <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider">Jabatan</th>
                    <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider">Tanggal</th>
                    <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-center">Waktu Tap</th>
                    <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider">Status</th>
                    <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-center">Keterangan</th>
                    <th class="p-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600 divide-y divide-gray-100">
                @forelse($attendances as $a)
                <tr class="transition hover:bg-gray-50/50">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="relative flex-shrink-0 group">
                                <img class="object-cover rounded-full w-9 h-9 ring-1 ring-gray-100 cursor-zoom-in hover:ring-2 hover:ring-[#773DCE] transition-all"
                                    src="{{ $a->teacher->photo ? asset('storage/'.$a->teacher->photo) : asset('assets/images/photos/default-photo.svg') }}"
                                    onclick="bukaPreviewFoto('{{ $a->teacher->photo ? asset('storage/'.$a->teacher->photo) : asset('assets/images/photos/default-photo.svg') }}', '{{ addslashes($a->teacher->name) }}')"
                                    title="Klik untuk preview">
                                <div class="absolute inset-0 flex items-center justify-center transition-opacity rounded-full opacity-0 pointer-events-none bg-black/20 group-hover:opacity-100">
                                    <i class="fa-solid fa-magnifying-glass-plus text-white text-[8px]"></i>
                                </div>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $a->teacher->name }}</p>
                                <p class="text-[10px] font-mono text-gray-400 tracking-tighter uppercase">
                                    NIP: {{ $a->teacher->nip ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 font-medium text-gray-500">{{ $a->teacher->jabatan ?? '-' }}</td>
                    <td class="p-4 font-medium">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>
                    <td class="p-4 text-center">
                        <div class="inline-flex items-center gap-2 px-3 py-1 border border-gray-100 rounded-lg bg-gray-50">
                            <span class="font-mono font-bold text-[#773DCE]">{{ $a->check_in ?? '--:--' }}</span>
                            <span class="text-gray-300">|</span>
                            <span class="font-mono font-bold text-amber-600">{{ $a->check_out ?? '--:--' }}</span>
                        </div>
                    </td>
                    <td class="p-4">
                        @if($a->status == 'Hadir')
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-green-50 text-green-600 border border-green-100">HADIR</span>
                        @elseif($a->status == 'Telat')
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-amber-50 text-amber-600 border border-amber-100">TERLAMBAT</span>
                        @else
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase bg-gray-50 text-gray-400 border border-gray-100">-</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        @if($a->keterangan)
                            @php
                                $kColors = [
                                    'Izin'  => ['bg'=>'#EFF6FF','border'=>'#BFDBFE','text'=>'#1D4ED8'],
                                    'Sakit' => ['bg'=>'#FFF7ED','border'=>'#FED7AA','text'=>'#C2410C'],
                                    'Alpa'  => ['bg'=>'#FEF2F2','border'=>'#FECACA','text'=>'#B91C1C'],
                                ];
                                $kc = $kColors[$a->keterangan] ?? ['bg'=>'#F9FAFB','border'=>'#E5E7EB','text'=>'#6B7280'];
                            @endphp
                            <span class="px-2.5 py-1 text-[10px] font-black rounded-lg"
                                style="background:{{ $kc['bg'] }};border:1px solid {{ $kc['border'] }};color:{{ $kc['text'] }}">
                                {{ strtoupper($a->keterangan) }}
                            </span>
                            @if($a->catatan_keterangan)
                                <div class="text-[9px] text-gray-400 mt-1">{{ $a->catatan_keterangan }}</div>
                            @endif
                        @else
                            <span class="text-xs text-gray-300">-</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <button type="button"
                                onclick="bukaEditGuru(
                                    {{ $a->teacher_id }},
                                    '{{ addslashes($a->teacher->name) }}',
                                    '{{ $a->date }}',
                                    '{{ $a->keterangan ?? '' }}',
                                    '{{ addslashes($a->catatan_keterangan ?? '') }}'
                                )"
                                class="p-2 text-blue-600 transition-all rounded-lg bg-blue-50 hover:bg-blue-100" title="Edit Keterangan">
                                <i class="fa-solid fa-pen-clip"></i>
                            </button>
                            <form action="{{ route('teacher_attendance.destroy', $a->id) }}" method="POST"
                                onsubmit="return confirm('Hapus data ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="p-2 text-gray-400 transition rounded-lg hover:text-red-500 hover:bg-red-50">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-16 text-center">
                        <div class="flex flex-col items-center">
                            <i class="mb-4 text-4xl text-gray-200 fa-solid fa-folder-open"></i>
                            <p class="italic text-gray-400">Tidak ada data absensi guru ditemukan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">{{ $attendances->links() }}</div>
</div>

{{-- MODAL INPUT / EDIT KETERANGAN GURU --}}
<div x-show="show" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
    @click.self="show = false">
    <div class="w-full max-w-md p-8 mx-4 bg-white shadow-2xl rounded-3xl">

        <div class="flex items-center gap-3 mb-6">
            <div class="p-2 text-blue-500 bg-blue-50 rounded-xl">
                <i class="text-lg fa-solid fa-pen-clip"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800"
                    x-text="editMode ? 'Edit Keterangan' : 'Input Keterangan Guru'"></h3>
                <p class="text-xs text-gray-400"
                    x-text="editMode ? namaEdit : 'Izin / Sakit / Alpa — tanpa perlu scan kartu'"></p>
            </div>
        </div>

        <form action="{{ route('teacher_attendance.keterangan') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            {{-- Pilih Guru — hanya tampil jika bukan mode edit --}}
            <div x-show="!editMode" x-data="guruSearch()">
                <label class="block mb-2 text-xs font-bold tracking-wider text-gray-500 uppercase">Pilih Guru</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                        <i class="text-xs fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" x-model="search" @input="filter()" @focus="open = true" @click.away="open = false"
                        placeholder="Ketik nama guru..."
                        class="block w-full pl-9 border-gray-200 rounded-xl text-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100">
                </div>
                <input type="hidden" name="teacher_id" x-model="selectedId">
                <div x-show="selectedName" class="flex items-center gap-2 px-3 py-2 mt-2 border border-purple-100 bg-purple-50 rounded-xl">
                    <i class="fa-solid fa-circle-check text-[#773DCE] text-xs"></i>
                    <span class="text-sm font-bold text-[#773DCE]" x-text="selectedName"></span>
                    <button type="button" @click="clear()" class="ml-auto text-xs text-red-400 hover:text-red-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div x-show="open && results.length > 0" x-cloak
                    class="relative z-[60] w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                    <template x-for="g in results" :key="g.id">
                        <div @click="select(g)" class="flex flex-col px-4 py-3 transition-colors border-b cursor-pointer hover:bg-purple-50 border-gray-50 last:border-0">
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

            <input type="hidden" name="teacher_id" x-show="editMode" :value="teacherIdEdit">

            {{-- Tanggal --}}
            <div>
                <label class="block mb-2 text-xs font-bold tracking-wider text-gray-500 uppercase">Tanggal</label>
                <input type="date" name="date" :value="tanggalEdit || today"
                    class="block w-full border-gray-200 rounded-xl text-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100"
                    required>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block mb-2 text-xs font-bold tracking-wider text-gray-500 uppercase">Keterangan</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="flex flex-col items-center gap-2 p-3 transition-all border-2 cursor-pointer rounded-xl"
                        :class="keterangan === 'Izin' ? 'border-blue-400 bg-blue-50' : 'border-gray-100 hover:border-blue-200'">
                        <input type="radio" name="keterangan" value="Izin" x-model="keterangan" class="sr-only">
                        <i class="text-xl text-blue-500 fa-solid fa-file-circle-check"></i>
                        <span class="text-xs font-bold text-blue-600">Izin</span>
                    </label>
                    <label class="flex flex-col items-center gap-2 p-3 transition-all border-2 cursor-pointer rounded-xl"
                        :class="keterangan === 'Sakit' ? 'border-orange-400 bg-orange-50' : 'border-gray-100 hover:border-orange-200'">
                        <input type="radio" name="keterangan" value="Sakit" x-model="keterangan" class="sr-only">
                        <i class="text-xl text-orange-500 fa-solid fa-kit-medical"></i>
                        <span class="text-xs font-bold text-orange-600">Sakit</span>
                    </label>
                    <label class="flex flex-col items-center gap-2 p-3 transition-all border-2 cursor-pointer rounded-xl"
                        :class="keterangan === 'Alpa' ? 'border-red-400 bg-red-50' : 'border-gray-100 hover:border-red-200'">
                        <input type="radio" name="keterangan" value="Alpa" x-model="keterangan" class="sr-only">
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
                <input type="text" name="catatan_keterangan" :value="catatanEdit"
                    placeholder="Contoh: Izin rapat dinas"
                    class="block w-full border-gray-200 rounded-xl text-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100">
            </div>

            <div class="flex justify-end gap-3 mt-2">
                <button type="button" @click="show = false; editMode = false"
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
const allGuruData = {!! json_encode($guruList) !!};

function guruKeteranganModal() {
    return {
        show: false,
        keterangan: 'Izin',
        today: '{{ date("Y-m-d") }}',
        editMode: false,
        namaEdit: '',
        teacherIdEdit: '',
        tanggalEdit: '',
        catatanEdit: '',
    }
}

function bukaEditGuru(teacherId, nama, tanggal, keteranganAwal, catatan) {
    window.dispatchEvent(new CustomEvent('buka-edit-guru', {
        detail: { teacherId, nama, tanggal, keteranganAwal, catatan }
    }));
}

function guruSearch() {
    return {
        search: '',
        selectedId: '',
        selectedName: '',
        open: false,
        results: [],

        filter() {
            if (this.search.length < 1) { this.results = []; this.open = false; return; }
            const q = this.search.toLowerCase();
            this.results = allGuruData.filter(g =>
                g.name.toLowerCase().includes(q) ||
                g.jabatan.toLowerCase().includes(q)
            ).slice(0, 8);
            this.open = true;
        },

        select(g) {
            this.selectedId   = g.id;
            this.selectedName = g.name + ' — ' + g.jabatan;
            this.search       = '';
            this.open         = false;
            this.results      = [];
        },

        clear() {
            this.selectedId   = '';
            this.selectedName = '';
            this.search       = '';
        }
    }
}
</script>
@endsection
