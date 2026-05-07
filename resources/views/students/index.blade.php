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

    {{-- Alert Section --}}
    @if (session('success'))
        <div class="flex p-4 mt-6 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50" role="alert">
            <i class="mt-1 mr-3 text-lg fa-solid fa-circle-check"></i>
            <div><span class="font-bold">Berhasil!</span> {{ session('success') }}</div>
        </div>
    @endif

    <div class="flex flex-col mt-8">
        <div class="overflow-x-auto shadow-sm ring-1 ring-gray-100 md:rounded-2xl">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-purple-50">
                    <tr>
                        <th class="py-4 pl-6 pr-3 text-xs font-black text-[#773DCE] uppercase tracking-wider text-left">Profil</th>
                        <th class="px-3 py-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-left">NISN / NIK</th>
                        <th class="px-3 py-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-left">Kelas</th>
                        <th class="px-3 py-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-left">Gender</th>
                        <th class="px-3 py-4 text-xs font-black text-[#773DCE] uppercase tracking-wider text-center">RFID UID</th>
                        <th class="relative py-4 pl-3 pr-6 text-xs font-black text-[#773DCE] uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($students as $s)
                    <tr class="transition-colors hover:bg-gray-50/50">
                        <td class="py-4 pl-6 pr-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <img class="object-cover w-10 h-10 border-2 border-white rounded-full shadow-sm ring-1 ring-gray-100"
                                     src="{{ $s->photo ? asset('storage/'.$s->photo) : asset('assets/images/photos/default-photo.svg') }}" alt="">
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
                    @endforeach
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
