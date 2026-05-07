@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-900">Daftar Siswa SDIT Ummatan Wahidah</h1>
            <p class="mt-2 text-sm text-gray-600">Manajemen data siswa, kelas, dan kode unik RFID/Barcode.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            {{-- Button Purple Theme --}}
            <a href="{{ route('student.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-white bg-[#773DCE] border border-transparent rounded-xl shadow-md hover:bg-[#5e2faf] transition-all">
                <i class="mr-2 fa-solid fa-plus"></i> Tambah Siswa
            </a>
        </div>
    </div>

    {{-- Alert Section (Sama seperti sebelumnya namun dengan rounded-xl) --}}
    @if (session('success'))
        <div class="flex p-4 mt-6 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50" role="alert">
            <i class="mt-1 mr-3 fa-solid fa-circle-check"></i>
            <div><span class="font-bold">Berhasil!</span> {{ session('success') }}</div>
        </div>
    @endif

    <div class="flex flex-col mt-8">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden border border-gray-100 shadow-sm rounded-2xl">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-4 pl-4 pr-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase sm:pl-6">Profil</th>
                                <th class="px-3 py-4 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">NISN / NIK</th>
                                <th class="px-3 py-4 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Kelas</th>
                                <th class="px-3 py-4 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Gender</th>
                                <th class="px-3 py-4 text-xs font-bold tracking-wider text-center text-gray-500 uppercase">RFID UID</th>
                                <th class="relative py-4 pl-3 pr-4 text-xs font-bold tracking-wider text-right text-gray-500 uppercase sm:pr-6">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($students as $s)
                            <tr class="transition hover:bg-purple-50/30">
                                <td class="py-4 pl-4 pr-3 whitespace-nowrap sm:pl-6">
                                    <div class="flex items-center">
                                        <img class="object-cover w-10 h-10 border-2 border-white rounded-full shadow-sm ring-1 ring-gray-100"
                                             src="{{ $s->photo ? asset('storage/'.$s->photo) : asset('assets/images/photos/default-photo.svg') }}" alt="">
                                        <div class="ml-4">
                                            <div class="font-bold text-gray-900">{{ $s->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $s->birth_place }}, {{ \Carbon\Carbon::parse($s->birth_date)->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-sm whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $s->nisn }}</div>
                                    <div class="text-[10px] text-gray-400">NIK: {{ $s->nik }}</div>
                                </td>
                                <td class="px-3 py-4 text-sm whitespace-nowrap">
                                    {{-- Badge Purple Theme --}}
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-purple-50 text-[#773DCE] border border-purple-100">
                                        {{ $s->schoolClass->name }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-sm whitespace-nowrap">
                                    @if($s->gender == 'L')
                                        <span class="px-2 py-1 text-[10px] font-bold text-blue-600 border border-blue-100 rounded-lg bg-blue-50">LAKI-LAKI</span>
                                    @else
                                        <span class="px-2 py-1 text-[10px] font-bold text-pink-600 border border-pink-100 rounded-lg bg-pink-50">PEREMPUAN</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="flex flex-col items-center gap-1">
                                        <div class="p-1 bg-white border border-gray-100 rounded-lg">
                                            {!! DNS2D::getBarcodeHTML($s->rfid_uid, 'QRCODE', 2, 2) !!}
                                        </div>
                                        <span class="font-mono text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                            {{ $s->rfid_uid }}
                                        </span>
                                    </div>
                                </td>
                                <td class="relative py-4 pl-3 pr-4 text-sm font-medium text-right whitespace-nowrap sm:pr-6">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('student.edit', $s->id) }}" class="text-[#773DCE] hover:text-[#5e2faf] font-bold">Edit</a>
                                        <form action="{{ route('student.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus data siswa ini?')">
                                            @csrf @method('DELETE')
                                            <button class="font-bold text-red-500 hover:text-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
