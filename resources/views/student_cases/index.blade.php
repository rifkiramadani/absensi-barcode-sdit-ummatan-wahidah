@extends('layouts.app')

@section('title', 'Buku Catatan Siswa')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">

    <div class="p-6 bg-white border border-gray-100 shadow-sm sm:flex sm:items-center rounded-2xl">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-800">Buku Catatan Siswa</h1>
            <p class="mt-2 text-sm text-gray-500">
                Pencatatan perilaku, prestasi, pelanggaran, dan catatan penting siswa SDIT Ummatan Wahidah.
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('student_case.create') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#773DCE] rounded-xl shadow-lg shadow-purple-100 hover:bg-[#5e2faf] transition-all">
                <i class="mr-2 fa-solid fa-plus"></i> Tambah Catatan
            </a>
        </div>
    </div>

    {{-- Statistik Kategori --}}
    <div class="grid grid-cols-2 gap-3 mt-6 md:grid-cols-5">
        @foreach($kategoriList as $nama => $config)
        <a href="{{ route('student_case.index', array_merge(request()->query(), ['kategori' => $nama])) }}"
            class="flex flex-col items-center gap-1 p-4 bg-white border rounded-2xl shadow-sm hover:shadow-md transition-all cursor-pointer
            {{ $kategori == $nama ? 'border-'.$config['warna'].'-300 bg-'.$config['warna'].'-50' : 'border-gray-100' }}">
            <i class="fa-solid {{ $config['icon'] }} text-{{ $config['warna'] }}-500 text-xl"></i>
            <span class="text-xs font-bold leading-tight text-center text-gray-600">{{ $nama }}</span>
        </a>
        @endforeach
    </div>

    {{-- Filter --}}
    <div class="mt-4">
        <form action="{{ route('student_case.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative w-full max-w-xs">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                    <i class="text-xs fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau NISN siswa..."
                    class="block w-full pl-10 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:border-[#773DCE]">
            </div>

            <select name="kategori" onchange="this.form.submit()"
                class="py-2.5 pl-3 pr-8 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl focus:border-[#773DCE] appearance-none cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $nama => $config)
                    <option value="{{ $nama }}" {{ $kategori == $nama ? 'selected' : '' }}>{{ $nama }}</option>
                @endforeach
            </select>

            <select name="school_class_id" onchange="this.form.submit()"
                class="py-2.5 pl-3 pr-8 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl focus:border-[#773DCE] appearance-none cursor-pointer">
                <option value="">Semua Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-5 py-2.5 bg-gray-800 text-white text-sm font-bold rounded-xl hover:bg-gray-900 transition-all">
                Cari
            </button>

            @if($search || $kategori || $classId)
                <a href="{{ route('student_case.index') }}"
                    class="px-5 py-2.5 text-red-500 bg-red-50 rounded-xl hover:bg-red-100 text-sm font-bold">Reset</a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="flex p-4 mt-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50">
            <i class="mt-1 mr-3 text-lg fa-solid fa-circle-check"></i>
            <div><span class="font-bold">Berhasil!</span> {{ session('success') }}</div>
        </div>
    @endif

    <div class="mt-4 overflow-x-auto shadow-sm ring-1 ring-gray-100 md:rounded-2xl">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-purple-50">
                <tr>
                    <th class="py-4 pl-6 pr-3 text-left text-xs font-black text-[#773DCE] uppercase tracking-wider">Siswa</th>
                    <th class="px-3 py-4 text-left text-xs font-black text-[#773DCE] uppercase tracking-wider">Tanggal</th>
                    <th class="px-3 py-4 text-left text-xs font-black text-[#773DCE] uppercase tracking-wider">Kategori</th>
                    <th class="px-3 py-4 text-left text-xs font-black text-[#773DCE] uppercase tracking-wider">Judul Catatan</th>
                    <th class="px-3 py-4 text-left text-xs font-black text-[#773DCE] uppercase tracking-wider">Dicatat Oleh</th>
                    <th class="py-4 pl-3 pr-6 text-right text-xs font-black text-[#773DCE] uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($cases as $case)
                @php
                    $config = $kategoriList[$case->kategori] ?? ['warna' => 'gray', 'icon' => 'fa-note-sticky'];
                    $w = $config['warna'];
                @endphp
                <tr class="transition-colors hover:bg-gray-50/50">
                    <td class="py-4 pl-6 pr-3 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <img class="object-cover rounded-full w-9 h-9 ring-1 ring-gray-100"
                                src="{{ $case->student->photo ? asset('storage/'.$case->student->photo) : asset('assets/images/photos/default-photo.svg') }}">
                            <div>
                                <div class="text-sm font-bold text-gray-800">{{ $case->student->name }}</div>
                                <div class="text-[10px] text-gray-400">{{ $case->student->schoolClass->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-600 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($case->tanggal_kejadian)->format('d M Y') }}
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-black text-{{ $w }}-600 border border-{{ $w }}-100 rounded-lg bg-{{ $w }}-50">
                            <i class="fa-solid {{ $config['icon'] }}"></i>
                            {{ strtoupper($case->kategori) }}
                        </span>
                    </td>
                    <td class="max-w-xs px-3 py-4 text-sm text-gray-700">
                        <div class="font-semibold">{{ $case->judul }}</div>
                        <div class="text-[11px] text-gray-400 truncate max-w-[200px]">{{ $case->deskripsi }}</div>
                    </td>
                    <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $case->dicatat_oleh }}</td>
                    <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('student_case.edit', $case->id) }}"
                                class="p-2 text-indigo-600 transition-all rounded-lg bg-indigo-50 hover:bg-indigo-100">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('student_case.destroy', $case->id) }}" method="POST"
                                onsubmit="return confirm('Hapus catatan ini?')">
                                @csrf @method('DELETE')
                                <button class="p-2 text-red-600 transition-all rounded-lg bg-red-50 hover:bg-red-100">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="mb-3 text-4xl text-gray-200 fa-solid fa-book-open"></i>
                            <p class="text-sm font-medium text-gray-400">Belum ada catatan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $cases->links() }}</div>
</div>
@endsection
