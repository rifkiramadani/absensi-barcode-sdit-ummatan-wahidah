@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

   <div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Daftar Siswa SDIT Ummatan Wahidah</h1>
            <p class="mt-2 text-sm text-gray-700">Manajemen data siswa, kelas, dan kode unik RFID/Barcode.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('student.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                + Tambah Siswa
            </a>
        </div>
    </div>

      {{-- Alert Success --}}
    @if (session('success'))
        <div class="flex p-4 mt-4 mb-2 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>
            <span class="sr-only">Success</span>
            <div>
                <span class="font-bold">Berhasil!</span> {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Alert Error (Jika gagal hapus) --}}
    @if (session('error'))
        <div class="flex p-4 mt-6 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z" />
            </svg>
            <div><span class="font-bold">Gagal!</span> {{ session('error') }}</div>
        </div>
    @endif

    <div class="flex flex-col mt-8">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Profil</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">NISN / NIK</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kelas</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Gender</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">RFID UID</th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-right text-sm font-semibold text-gray-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($students as $s)
                            <tr class="transition hover:bg-gray-50">
                                <td class="py-4 pl-4 pr-3 text-sm whitespace-nowrap sm:pl-6">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10">
                                            <img class="object-cover w-10 h-10 border border-gray-200 rounded-full"
                                                 src="{{ $s->photo ? asset('storage/'.$s->photo) : asset('assets/images/photos/default-photo.svg') }}" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ $s->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $s->birth_place }}, {{ \Carbon\Carbon::parse($s->birth_date)->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    <div class="text-gray-900">{{ $s->nisn }}</div>
                                    <div class="text-xs text-gray-500">NIK: {{ $s->nik }}</div>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border">
                                        {{ $s->schoolClass->name }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    @if($s->gender == 'L')
                                        <span class="px-2 py-1 text-xs font-bold text-blue-600 border border-blue-200 rounded-md bg-blue-50">LAKI-LAKI</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-bold text-pink-600 border border-pink-200 rounded-md bg-pink-50">PEREMPUAN</span>
                                    @endif
                                </td>
                               <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="flex flex-col items-center gap-1">
                                        {{-- Generate Barcode dari rfid_uid --}}
                                        <div class="barcode-container">
                                            {!! DNS2D::getBarcodeHTML($s->rfid_uid, 'QRCODE', 3, 3) !!}
                                        </div>
                                        {{-- Teks RFID UID di bawahnya --}}
                                        <span class="font-mono text-xs tracking-widest text-gray-600">
                                            {{ $s->rfid_uid }}
                                        </span>
                                    </div>
                                </td>
                                <td class="relative py-4 pl-3 pr-4 text-sm font-medium text-right whitespace-nowrap sm:pr-6">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('student.edit', $s->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('student.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus data siswa ini?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:text-red-900">Hapus</button>
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
