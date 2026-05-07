@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
    <div class="max-w-4xl p-8 mx-auto bg-white border border-gray-100 shadow-sm rounded-2xl">
        <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-50">
            <div class="p-2 bg-purple-50 rounded-lg text-[#773DCE]">
                <i class="text-xl fa-solid fa-user-pen"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Siswa: <span class="text-[#773DCE]">{{ $student->name }}</span></h2>
        </div>

        <form action="{{ route('student.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $student->nik) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Tempat Lahir</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place', $student->birth_place) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Jenis Kelamin</label>
                    <select name="gender" class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                        <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Kelas</label>
                    <select name="school_class_id" class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" {{ old('school_class_id', $student->school_class_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Tahun Masuk</label>
                    <input type="number" name="entry_year" value="{{ old('entry_year', $student->entry_year) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">RFID UID (KODE BARCODE)</label>
                    <input type="text" name="rfid_uid" value="{{ old('rfid_uid', $student->rfid_uid) }}"
                        class="block w-full mt-1 font-mono border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                </div>

                <div class="flex items-center col-span-2 gap-6 p-6 mt-4 border border-gray-100 bg-gray-50 rounded-2xl">
                    <div class="relative flex-shrink-0">
                        <img src="{{ $student->photo ? asset('storage/' . $student->photo) : asset('assets/images/photos/default-photo.svg') }}"
                             class="object-cover w-24 h-24 border-4 border-white shadow-md rounded-2xl ring-1 ring-gray-100">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700">Ubah Foto Siswa</label>
                        <p class="mb-3 text-xs italic text-gray-400">*Kosongkan jika foto tidak ingin diganti</p>
                        <input type="file" name="photo"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-[#773DCE] hover:file:bg-purple-100">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-10">
                <a href="{{ route('student.index') }}" class="px-6 py-3 text-sm font-bold text-gray-500 transition-all border border-gray-100 rounded-xl hover:bg-gray-50">Kembali</a>
                <button type="submit" class="px-10 py-3 text-sm font-bold text-white transition-all bg-green-600 shadow-lg rounded-xl shadow-green-100 hover:bg-green-700">
                    Update Data Siswa
                </button>
            </div>
        </form>
    </div>
@endsection
