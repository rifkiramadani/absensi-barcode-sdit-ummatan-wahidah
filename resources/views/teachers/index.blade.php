@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8">

        <div class="p-6 bg-white border border-gray-100 shadow-sm sm:flex sm:items-center rounded-2xl">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold text-gray-800">Daftar Guru</h1>
                <p class="mt-2 text-sm text-gray-500">Manajemen data guru dan kartu RFID untuk absensi.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('teacher.create') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#773DCE] rounded-xl shadow-lg shadow-purple-100 hover:bg-[#5e2faf] transition-all">
                    <i class="mr-2 fa-solid fa-plus"></i> Tambah Guru
                </a>
            </div>
        </div>

        {{-- Search --}}
        <div class="mt-6">
            <form action="{{ route('teacher.index') }}" method="GET" class="flex gap-3">
                <div class="relative w-full max-w-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                        <i class="text-xs fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, NIY, atau jabatan..."
                        class="block w-full pl-10 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-purple-100 focus:border-[#773DCE] transition-all">
                </div>
                <button type="submit"
                    class="px-5 py-2.5 bg-gray-800 text-white text-sm font-bold rounded-xl hover:bg-gray-900 transition-all">Cari</button>
                @if (request('search'))
                    <a href="{{ route('teacher.index') }}"
                        class="px-5 py-2.5 text-red-500 bg-red-50 rounded-xl hover:bg-red-100 text-sm font-bold">Reset</a>
                @endif
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
                            Profil</th>
                        <th class="px-3 py-4 text-left text-xs font-black text-[#773DCE] uppercase tracking-wider">NIP</th>
                        <th class="px-3 py-4 text-left text-xs font-black text-[#773DCE] uppercase tracking-wider">Jabatan
                        </th>
                        <th class="px-3 py-4 text-left text-xs font-black text-[#773DCE] uppercase tracking-wider">No. HP
                        </th>
                        <th class="px-3 py-4 text-center text-xs font-black text-[#773DCE] uppercase tracking-wider">RFID
                            UID</th>
                        <th class="px-3 py-4 text-center text-xs font-black text-[#773DCE] uppercase tracking-wider">Status
                        </th>
                        <th class="py-4 pl-3 pr-6 text-right text-xs font-black text-[#773DCE] uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($teachers as $teacher)
                        <tr class="transition-colors hover:bg-gray-50/50">
                            <td class="py-4 pl-6 pr-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img class="object-cover w-14 h-14 border-2 border-white rounded-full shadow-sm ring-1 ring-gray-100 cursor-zoom-in hover:ring-2 hover:ring-[#773DCE] transition-all"
                                        src="{{ $teacher->photo ? asset('storage/' . $teacher->photo) : asset('assets/images/photos/default-photo.svg') }}"
                                        onclick="bukaPreviewFoto('{{ $teacher->photo ? asset('storage/'.$teacher->photo) : asset('assets/images/photos/default-photo.svg') }}', '{{ addslashes($teacher->name) }}')"
                                        title="Klik untuk preview">
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $teacher->name }}</div>
                                        <div class="text-[11px] text-gray-400">
                                            {{ $teacher->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $teacher->nip ?? '-' }}</td>
                            <td class="px-3 py-4 text-sm whitespace-nowrap">
                                <span
                                    class="px-2.5 py-1 text-xs font-bold bg-purple-50 text-[#773DCE] rounded-lg border border-purple-100">
                                    {{ $teacher->jabatan ?? '-' }}
                                </span>
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $teacher->no_hp ?? '-' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="p-1 bg-white border border-gray-100 rounded-lg shadow-xs">
                                        {!! DNS2D::getBarcodeHTML($teacher->rfid_uid, 'QRCODE', 1.8, 1.8) !!}
                                    </div>
                                    <span class="font-mono text-[9px] font-bold tracking-widest text-gray-400 uppercase">
                                        {{ $teacher->rfid_uid }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-3 py-4 text-center whitespace-nowrap">
                                @if ($teacher->is_active)
                                    <span
                                        class="px-2.5 py-1 text-[10px] font-black text-green-600 border border-green-100 rounded-lg bg-green-50">AKTIF</span>
                                @else
                                    <span
                                        class="px-2.5 py-1 text-[10px] font-black text-gray-400 border border-gray-100 rounded-lg bg-gray-50">NON-AKTIF</span>
                                @endif
                            </td>
                            <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('teacher.edit', $teacher->id) }}"
                                        class="p-2 text-indigo-600 transition-all rounded-lg bg-indigo-50 hover:bg-indigo-100">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('teacher.destroy', $teacher->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus data guru ini?')">
                                        @csrf @method('DELETE')
                                        <button
                                            class="p-2 text-red-600 transition-all rounded-lg bg-red-50 hover:bg-red-100">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="mb-3 text-4xl text-gray-200 fa-solid fa-chalkboard-user"></i>
                                    <p class="text-sm font-medium text-gray-400">Belum ada data guru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $teachers->links() }}</div>
    </div>
@endsection
