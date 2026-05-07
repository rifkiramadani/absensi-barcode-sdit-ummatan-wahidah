@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
    <div class="max-w-4xl p-8 mx-auto bg-white border border-gray-100 shadow-sm rounded-2xl">
        <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-50">
            <div class="p-2 bg-purple-50 rounded-lg text-[#773DCE]">
                <i class="text-xl fa-solid fa-user-plus"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Siswa Baru</h2>
        </div>

        @if ($errors->any())
            <div class="flex p-4 mb-6 text-sm text-red-800 border border-red-200 rounded-xl bg-red-50" role="alert">
                <i class="mt-1 mr-3 fa-solid fa-circle-exclamation"></i>
                <div>
                    <span class="font-bold">Terjadi kesalahan:</span>
                    <ul class="mt-1 ml-4 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik') }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Tempat Lahir</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place') }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Jenis Kelamin</label>
                    <select name="gender" class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Kelas</label>
                    <select name="school_class_id" class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" {{ old('school_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-bold text-gray-700">Tahun Masuk</label>
                    <input type="number" name="entry_year" value="{{ old('entry_year', date('Y')) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                </div>

                <div class="col-span-2 p-5 border-2 border-purple-100 border-dashed rounded-2xl bg-purple-50/30">
                    <label class="block text-xs font-black tracking-widest text-[#773DCE] uppercase">RFID UID / Barcode ID</label>
                    <div class="relative mt-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-purple-400">
                            <i class="fa-solid fa-rss"></i>
                        </span>
                        <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}" placeholder="Scan kartu..."
                            class="block w-full pl-10 font-mono text-lg bg-white border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-200">
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700">Foto Siswa</label>
                    <div class="flex justify-center px-6 pt-5 pb-6 mt-2 transition-all border-2 border-gray-100 border-dashed rounded-2xl hover:bg-gray-50">
                        <div class="space-y-1 text-center">
                            <i class="mb-2 text-3xl text-gray-300 fa-solid fa-image"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="photo" class="relative cursor-pointer bg-white rounded-md font-bold text-[#773DCE] hover:text-[#5e2faf]">
                                    <span>Upload file foto</span>
                                    <input id="photo" name="photo" type="file" class="sr-only">
                                </label>
                            </div>
                            <p class="text-xs text-gray-400">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-10">
                <a href="{{ route('student.index') }}" class="px-6 py-3 text-sm font-bold text-gray-500 transition-all border border-gray-100 rounded-xl hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-10 py-3 text-sm font-bold text-white bg-[#773DCE] rounded-xl shadow-lg shadow-purple-200 hover:bg-[#5e2faf] transition-all">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection
