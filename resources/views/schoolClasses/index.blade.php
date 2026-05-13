@extends('layouts.app')

@section('title', 'Data Kelas')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="p-6 bg-white border border-gray-100 shadow-sm sm:flex sm:items-center rounded-2xl">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold text-gray-800">Daftar Kelas</h1>
                <p class="mt-2 text-sm text-gray-500">Kelola data kelas untuk pengelompokan siswa SDIT Ummatan Wahidah.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('school_class.create') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-[#773DCE] border border-transparent rounded-xl shadow-lg shadow-purple-100 hover:bg-[#5e2faf] transition-all">
                    <i class="mr-2 fa-solid fa-plus"></i> Tambah Kelas
                </a>
            </div>
        </div>

        {{-- Search --}}
        <div class="mt-6">
            <form action="{{ route('school_class.index') }}" method="GET" class="flex gap-3">
                <div class="relative w-full max-w-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                        <i class="text-xs fa-solid fa-magnifying-glass"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Cari nama kelas..."
                        class="block w-full pl-10 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-purple-100 focus:border-[#773DCE] transition-all">
                </div>
                <button type="submit"
                    class="px-5 py-2.5 bg-gray-800 text-white text-sm font-bold rounded-xl hover:bg-gray-900 transition-all">
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('school_class.index') }}"
                        class="px-5 py-2.5 text-red-500 bg-red-50 rounded-xl hover:bg-red-100 transition-colors text-sm font-bold">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="flex p-4 mt-4 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50" role="alert">
                <i class="mt-1 mr-3 text-lg fa-solid fa-circle-check"></i>
                <div><span class="font-bold">Berhasil!</span> {{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="flex p-4 mt-4 text-sm text-red-800 border border-red-200 rounded-xl bg-red-50" role="alert">
                <i class="mt-1 mr-3 text-lg fa-solid fa-circle-xmark"></i>
                <div><span class="font-bold">Gagal!</span> {{ session('error') }}</div>
            </div>
        @endif

        <div class="flex flex-col mt-4">
            <div class="overflow-x-auto shadow-sm ring-1 ring-gray-100 md:rounded-2xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-purple-50">
                        <tr>
                            <th class="py-4 pl-6 pr-3 text-left text-xs font-black text-[#773DCE] uppercase tracking-wider">Nama Kelas</th>
                            <th class="px-3 py-4 text-left text-xs font-black text-[#773DCE] uppercase tracking-wider">Jumlah Siswa</th>
                            <th class="relative py-4 pl-3 pr-6 text-right text-xs font-black text-[#773DCE] uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($classes as $class)
                            <tr class="transition-colors hover:bg-gray-50/50">
                                <td class="py-4 pl-6 pr-3 text-sm font-bold text-gray-800 whitespace-nowrap">
                                    {{ $class->name }}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full">
                                        {{ $class->students_count }} Siswa
                                    </span>
                                </td>
                                <td class="relative py-4 pl-3 pr-6 text-sm font-medium text-right whitespace-nowrap">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('school_class.edit', $class->id) }}"
                                            class="p-2 text-indigo-600 transition-all rounded-lg bg-indigo-50 hover:bg-indigo-100" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('school_class.destroy', $class->id) }}"
                                            method="POST" onsubmit="return confirm('Hapus kelas ini?')">
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
                                <td colspan="3" class="py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="mb-3 text-4xl text-gray-200 fa-solid fa-school-flag"></i>
                                        <p class="text-sm font-medium text-gray-400">
                                            {{ $search ? 'Tidak ada kelas dengan nama "' . $search . '".' : 'Belum ada data kelas.' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $classes->links() }}
            </div>
        </div>
    </div>
@endsection
