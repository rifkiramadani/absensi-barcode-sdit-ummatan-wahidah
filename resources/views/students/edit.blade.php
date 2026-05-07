@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl p-8 mx-auto bg-white border border-gray-100 shadow-sm rounded-2xl">
        {{-- Header Form --}}
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
                {{-- Nama Lengkap --}}
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm @error('name') border-red-300 @enderror" required>
                    @error('name') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- NISN --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm @error('nisn') border-red-300 @enderror">
                    @error('nisn') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- NIK --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $student->nik) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm @error('nik') border-red-300 @enderror">
                    @error('nik') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Tempat Lahir --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700">Tempat Lahir</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place', $student->birth_place) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm @error('birth_place') border-red-300 @enderror">
                    @error('birth_place') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm @error('birth_date') border-red-300 @enderror">
                    @error('birth_date') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700">Jenis Kelamin</label>
                    <select name="gender" class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                        <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                {{-- Kelas --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700">Kelas</label>
                    <select name="school_class_id" class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" {{ old('school_class_id', $student->school_class_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tahun Masuk --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700">Tahun Masuk</label>
                    <input type="number" name="entry_year" value="{{ old('entry_year', $student->entry_year) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm @error('entry_year') border-red-300 @enderror">
                    @error('entry_year') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- RFID / Barcode --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700">RFID UID (KODE BARCODE)</label>
                    <input type="text" name="rfid_uid" value="{{ old('rfid_uid', $student->rfid_uid) }}"
                        class="block w-full mt-1 font-mono border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm @error('rfid_uid') border-red-300 @enderror">
                    @error('rfid_uid') <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Bagian Foto & Preview dengan Validasi Inline --}}
                <div class="flex flex-col col-span-2 gap-4 p-6 mt-4 border rounded-2xl @error('photo') border-red-200 bg-red-50/30 @else border-gray-100 bg-gray-50 @enderror">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-bold text-gray-700">Ubah Foto Siswa</label>
                        {{-- Validasi Error Foto --}}
                        @error('photo')
                            <span class="text-xs font-bold text-red-500 animate-pulse">
                                <i class="mr-1 fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="relative flex-shrink-0">
                            <img id="photoPreview"
                                 src="{{ $student->photo ? asset('storage/' . $student->photo) : asset('assets/images/photos/default-photo.svg') }}"
                                 class="object-cover w-24 h-24 transition-all duration-300 border-4 border-white shadow-md rounded-2xl ring-1 ring-gray-100">
                        </div>
                        <div class="flex-1">
                            <p class="mb-3 text-xs italic text-gray-400">*Kosongkan jika foto tidak ingin diganti</p>
                            <input type="file" name="photo" id="photoInput" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-[#773DCE] hover:file:bg-purple-100 transition-all">
                            <p class="mt-2 text-[10px] text-gray-400">Format: PNG, JPG, JPEG (Maks. 2MB)</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 mt-10">
                <a href="{{ route('student.index') }}"
                   class="px-6 py-3 text-sm font-bold text-gray-500 transition-all border border-gray-100 rounded-xl hover:bg-gray-50">
                    Kembali
                </a>

                <button type="submit"
                        class="px-10 py-3 text-sm font-bold text-white transition-all bg-[#773DCE] shadow-lg rounded-xl shadow-purple-100 hover:bg-[#5e2faf] focus:ring-2 focus:ring-[#773DCE] focus:ring-offset-2">
                    <i class="mr-2 fa-solid fa-floppy-disk"></i> Update Data Siswa
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script Preview Gambar --}}
<script>
    document.getElementById('photoInput').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
            if (file.type.startsWith('image/')) {
                const photoPreview = document.getElementById('photoPreview');
                photoPreview.src = URL.createObjectURL(file);
            } else {
                alert("Mohon pilih file gambar (JPG, PNG, atau SVG).");
                this.value = "";
            }
        }
    };
</script>
@endsection
