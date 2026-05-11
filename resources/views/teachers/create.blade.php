@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl p-8 mx-auto bg-white border border-gray-100 shadow-sm rounded-2xl">

            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-50">
                <div class="p-2 bg-purple-50 rounded-lg text-[#773DCE]">
                    <i class="text-xl fa-solid fa-chalkboard-user"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Guru Baru</h2>
            </div>

            <form action="{{ route('teacher.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                            placeholder="Masukkan nama lengkap guru" required>
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">Jenis Kelamin</label>
                        <select name="gender"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">NIY <span
                                class="font-normal text-gray-400">(opsional)</span></label>
                        <input type="text" name="nip" value="{{ old('nip') }}"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                            placeholder="Kosongkan jika guru honorer">
                        @error('nip')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                            placeholder="Contoh: Guru Kelas 1A">
                        @error('jabatan')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">No. HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                            placeholder="08xxxxxxxxxx">
                        @error('no_hp')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2 p-5 border-2 border-purple-100 border-dashed rounded-2xl bg-purple-50/30">
                        <label class="block text-xs font-black tracking-widest text-[#773DCE] uppercase">RFID UID / Barcode
                            ID</label>
                        <div class="relative mt-2">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-purple-400">
                                <i class="fa-solid fa-rss"></i>
                            </span>
                            <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}"
                                placeholder="Scan kartu atau masukkan kode..."
                                class="block w-full pl-10 font-mono text-lg bg-white border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-200">
                        </div>
                        @error('rfid_uid')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700">Foto Guru</label>
                        <input type="file" name="photo" accept="image/*"
                            class="block w-full mt-2 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-[#773DCE] hover:file:bg-purple-100">
                        @error('photo')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-10">
                    <a href="{{ route('teacher.index') }}"
                        class="px-6 py-3 text-sm font-bold text-gray-500 transition-all border border-gray-100 rounded-xl hover:bg-gray-50">Batal</a>
                    <button type="submit"
                        class="px-10 py-3 text-sm font-bold text-white bg-[#773DCE] rounded-xl shadow-lg shadow-purple-200 hover:bg-[#5e2faf] transition-all">
                        <i class="mr-2 fa-solid fa-floppy-disk"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
