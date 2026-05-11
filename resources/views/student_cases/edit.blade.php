@extends('layouts.app')

@section('title', 'Edit Catatan')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl p-8 mx-auto bg-white border border-gray-100 shadow-sm rounded-2xl">

        <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-50">
            <div class="p-2 bg-purple-50 rounded-lg text-[#773DCE]">
                <i class="text-xl fa-solid fa-book-open"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Catatan</h2>
        </div>

        <form action="{{ route('student_case.update', $studentCase->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="flex flex-col gap-6">

                {{-- Pilih Siswa dengan Search --}}
                <div x-data="studentSearch()" class="relative">
                    <label class="block text-sm font-bold text-gray-700">Nama Siswa</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                            <i class="text-xs fa-solid fa-magnifying-glass"></i>
                        </div>
                        <input type="text" x-model="search" @input="filter()" @focus="open = true"
                            @click.away="open = false" placeholder="Ketik nama siswa..."
                            class="block w-full pl-10 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                    </div>

                    <input type="hidden" name="student_id" x-model="selectedId">

                    <div x-show="selectedName"
                        class="flex items-center gap-2 px-3 py-2 mt-2 border border-purple-100 bg-purple-50 rounded-xl">
                        <i class="fa-solid fa-circle-check text-[#773DCE] text-xs"></i>
                        <span class="text-sm font-bold text-[#773DCE]" x-text="selectedName"></span>
                        <button type="button" @click="clear()" class="ml-auto text-xs text-red-400 hover:text-red-600">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div x-show="open && results.length > 0" x-cloak
                        class="absolute z-50 w-full mt-1 overflow-y-auto bg-white border border-gray-200 shadow-xl rounded-xl max-h-60">
                        <template x-for="s in results" :key="s.id">
                            <div @click="select(s)"
                                class="flex flex-col px-4 py-3 transition-colors border-b cursor-pointer hover:bg-purple-50 border-gray-50 last:border-0">
                                <span class="text-sm font-bold text-gray-800" x-text="s.name"></span>
                                <span class="text-[10px] text-gray-400" x-text="s.class + ' • NISN: ' + s.nisn"></span>
                            </div>
                        </template>
                    </div>

                    <div x-show="open && search.length > 1 && results.length === 0" x-cloak
                        class="absolute z-50 w-full px-4 py-3 mt-1 text-sm text-gray-400 bg-white border border-gray-200 shadow-xl rounded-xl">
                        Siswa tidak ditemukan.
                    </div>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700">Tanggal Kejadian</label>
                    <input type="date" name="tanggal_kejadian"
                        value="{{ old('tanggal_kejadian', $studentCase->tanggal_kejadian) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                        required>
                    @error('tanggal_kejadian')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori dengan Alpine -- inline style agar tidak bergantung Tailwind JIT --}}
                <div x-data="{ selected: '{{ old('kategori', $studentCase->kategori) }}' }">
                    <label class="block mb-3 text-sm font-bold text-gray-700">Kategori</label>

                    <input type="hidden" name="kategori" :value="selected">

                    @php
                    $kategoriConfig = [
                        'Pelanggaran'           => ['warna' => '#EF4444', 'bg' => '#FEF2F2', 'border' => '#FCA5A5', 'icon' => 'fa-triangle-exclamation'],
                        'Prestasi Akademik'     => ['warna' => '#16A34A', 'bg' => '#F0FDF4', 'border' => '#86EFAC', 'icon' => 'fa-award'],
                        'Prestasi Non-Akademik' => ['warna' => '#2563EB', 'bg' => '#EFF6FF', 'border' => '#93C5FD', 'icon' => 'fa-trophy'],
                        'Perilaku Baik'         => ['warna' => '#9333EA', 'bg' => '#FAF5FF', 'border' => '#D8B4FE', 'icon' => 'fa-heart'],
                        'Catatan Umum'          => ['warna' => '#6B7280', 'bg' => '#F9FAFB', 'border' => '#D1D5DB', 'icon' => 'fa-note-sticky'],
                    ];
                    @endphp

                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                        @foreach($kategoriConfig as $nama => $cfg)
                        <div @click="selected = '{{ $nama }}'"
                            :style="selected === '{{ $nama }}'
                                ? 'border-color: {{ $cfg['border'] }}; background-color: {{ $cfg['bg'] }};'
                                : 'border-color: #F3F4F6; background-color: white;'"
                            class="flex flex-col items-center gap-2 p-4 transition-all border-2 cursor-pointer select-none rounded-2xl hover:shadow-sm">
                            <i class="fa-solid {{ $cfg['icon'] }} text-2xl"
                                style="color: {{ $cfg['warna'] }}"></i>
                            <span class="text-[11px] font-bold text-center text-gray-700 leading-tight">{{ $nama }}</span>
                            <div class="w-2 h-2 transition-all rounded-full"
                                :style="selected === '{{ $nama }}'
                                    ? 'background-color: {{ $cfg['warna'] }}'
                                    : 'background-color: #E5E7EB'">
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @error('kategori')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700">Judul Catatan</label>
                    <input type="text" name="judul" value="{{ old('judul', $studentCase->judul) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                        required>
                    @error('judul')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700">Deskripsi Lengkap</label>
                    <textarea name="deskripsi" rows="4"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                        required>{{ old('deskripsi', $studentCase->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tindak Lanjut --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700">
                        Tindak Lanjut <span class="font-normal text-gray-400">(opsional)</span>
                    </label>
                    <textarea name="tindak_lanjut" rows="3"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                        placeholder="Contoh: Siswa dipanggil orang tua, diberikan penghargaan, dll...">{{ old('tindak_lanjut', $studentCase->tindak_lanjut) }}</textarea>
                </div>

            </div>

            <div class="flex justify-end gap-3 mt-10">
                <a href="{{ route('student_case.index') }}"
                    class="px-6 py-3 text-sm font-bold text-gray-500 transition-all border border-gray-100 rounded-xl hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                    class="px-10 py-3 text-sm font-bold text-white bg-[#773DCE] rounded-xl shadow-lg shadow-purple-200 hover:bg-[#5e2faf] transition-all">
                    <i class="mr-2 fa-solid fa-floppy-disk"></i> Update Catatan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const allStudentData = {!! json_encode($studentList) !!};

function studentSearch() {
    return {
        search: '',
        selectedId: '{{ old("student_id", $studentCase->student_id) }}',
        selectedName: '{{ addslashes($studentCase->student->name) }} - {{ addslashes($studentCase->student->schoolClass->name) }}',
        open: false,
        results: [],

        filter() {
            if (this.search.length < 1) {
                this.results = [];
                this.open = false;
                return;
            }
            const q = this.search.toLowerCase();
            this.results = allStudentData.filter(s =>
                s.name.toLowerCase().includes(q) ||
                s.nisn.toLowerCase().includes(q) ||
                s.class.toLowerCase().includes(q)
            ).slice(0, 10);
            this.open = true;
        },

        select(s) {
            this.selectedId   = s.id;
            this.selectedName = s.name + ' - ' + s.class;
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
