@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="p-6 bg-white border border-gray-100 shadow-sm sm:flex sm:items-center rounded-2xl">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Siswa</h1>
            <p class="mt-2 text-sm text-gray-500">Manajemen data siswa, kelas, dan kode unik RFID/Barcode SDIT Ummatan Wahidah.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('student.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#773DCE] border border-transparent rounded-xl shadow-lg shadow-purple-100 hover:bg-[#5e2faf] transition-all">
                <i class="mr-2 fa-solid fa-plus"></i> Tambah Siswa
            </a>
        </div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="flex flex-col gap-4 mt-6 lg:flex-row lg:items-center lg:justify-between">
        <form action="{{ route('student.index') }}" method="GET" class="flex flex-col items-center w-full gap-3 md:flex-row">

            {{-- Input Search --}}
            <div class="relative w-full md:w-80">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                    <i class="text-xs fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari Nama, NISN, atau NIK..."
                    class="block w-full pl-10 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-purple-100 focus:border-[#773DCE] transition-all">
            </div>

            {{-- Dropdown Filter Kelas --}}
            <div class="relative w-full md:w-52">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-[#773DCE]">
                    <i class="text-xs fa-solid fa-filter"></i>
                </div>
                <select name="class_id" onchange="this.form.submit()"
                    class="block w-full pl-10 pr-10 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-200 rounded-xl focus:ring-purple-100 focus:border-[#773DCE] transition-all appearance-none cursor-pointer">
                    <option value="">Semua Kelas</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            Kelas {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 pointer-events-none">
                    <i class="fa-solid fa-chevron-down text-[10px]"></i>
                </div>
            </div>

            {{-- Tombol Cari & Reset --}}
            <div class="flex w-full gap-2 md:w-auto">
                <button type="submit" class="flex-1 md:flex-none px-5 py-2.5 bg-gray-800 text-white text-sm font-bold rounded-xl hover:bg-gray-900 transition-all">
                    Cari
                </button>

                @if(request('search') || request('class_id'))
                    <a href="{{ route('student.index') }}" class="px-5 py-2.5 text-red-500 bg-red-50 rounded-xl hover:bg-red-100 transition-colors text-sm font-bold" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="text-xs font-medium text-gray-400">
            Menampilkan <span class="font-bold text-gray-700">{{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-700">{{ $students->total() }}</span> siswa
        </div>
    </div>

    {{-- Alert Section --}}
    @if (session('success'))
        <div class="flex p-4 mt-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50" role="alert">
            <i class="mt-1 mr-3 text-lg fa-solid fa-circle-check"></i>
            <div><span class="font-bold">Berhasil!</span> {{ session('success') }}</div>
        </div>
    @endif

    {{-- Table Section --}}
    <div class="flex flex-col mt-4">
        <div class="overflow-x-auto shadow-sm ring-1 ring-gray-100 md:rounded-2xl">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-purple-50">
                    <tr>
                        <th class="py-4 pl-6 pr-3 text-xs font-black text-[#773DCE] uppercase tracking-wider text-left">Profil</th>
                        <th class="px-3 py-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-left">NISN / NIK</th>
                        <th class="px-3 py-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-left">Kelas</th>
                        <th class="px-3 py-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-left">Jenis Kelamin</th>
                        <th class="px-3 py-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-center">RFID UID</th>
                        <th class="relative py-4 pl-3 pr-6 text-xs font-black text-[#773DCE] uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($students as $s)
                    <tr class="transition-colors hover:bg-gray-50/50">
                        {{-- ... (Isi <td> sama seperti kode sebelumnya) ... --}}
                        <td class="py-4 pl-6 pr-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <img class="object-cover w-10 h-10 border-2 border-white rounded-full shadow-sm ring-1 ring-gray-100 cursor-zoom-in hover:ring-2 hover:ring-[#773DCE] transition-all"
                                    src="{{ $s->photo ? asset('storage/'.$s->photo) : asset('assets/images/photos/default-photo.svg') }}"
                                    onclick="bukaPreviewFoto('{{ $s->photo ? asset('storage/'.$s->photo) : asset('assets/images/photos/default-photo.svg') }}', '{{ addslashes($s->name) }}')"
                                    alt="{{ $s->name }}" title="Klik untuk preview">
                                <div class="ml-4">
                                    <div class="font-bold text-gray-800">{{ $s->name }}</div>
                                    <div class="text-[11px] text-gray-400">{{ $s->birth_place }}, {{ \Carbon\Carbon::parse($s->birth_date)->format('d M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-4 text-sm whitespace-nowrap">
                            <div class="font-bold text-gray-700">{{ $s->nisn }}</div>
                            <div class="text-[10px] text-gray-400 uppercase tracking-tight">NIK: {{ $s->nik }}</div>
                        </td>
                        <td class="px-3 py-4 text-sm whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-50 text-[#773DCE] border border-purple-100">
                                {{ $s->schoolClass->name }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-sm whitespace-nowrap">
                            @if($s->gender == 'L')
                                <span class="px-2.5 py-1 text-[10px] font-black text-blue-600 border border-blue-100 rounded-lg bg-blue-50">LAKI-LAKI</span>
                            @else
                                <span class="px-2.5 py-1 text-[10px] font-black text-pink-600 border border-pink-100 rounded-lg bg-pink-50">PEREMPUAN</span>
                            @endif
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <div class="flex flex-col items-center gap-1">
                                <div class="p-1 bg-white border border-gray-100 rounded-lg shadow-xs">
                                    {!! DNS2D::getBarcodeHTML($s->rfid_uid, 'QRCODE', 1.8, 1.8) !!}
                                </div>
                                <span class="font-mono text-[9px] font-bold tracking-widest text-gray-400 uppercase">
                                    {{ $s->rfid_uid }}
                                </span>
                            </div>
                        </td>
                        <td class="relative py-4 pl-3 pr-6 text-sm font-medium text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('student.edit', $s->id) }}"
                                   class="p-2 text-indigo-600 transition-all rounded-lg bg-indigo-50 hover:bg-indigo-100" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('student.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus data siswa ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-red-600 transition-all rounded-lg bg-red-50 hover:bg-red-100" title="Hapus">
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
                                <i class="mb-3 text-4xl text-gray-200 fa-solid fa-user-slash"></i>
                                <p class="text-sm font-medium text-gray-400">Tidak ada data siswa ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
