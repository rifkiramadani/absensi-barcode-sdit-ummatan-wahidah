@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
    <div class="max-w-4xl p-8 mx-auto bg-white border border-gray-100 shadow-md rounded-xl">
        <h2 class="pb-4 mb-6 text-2xl font-bold text-gray-800 border-b">Edit Data Siswa: {{ $student->name }}</h2>

        {{-- Tampilkan Pesan Error Umum --}}
        @if ($errors->any())
            <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <p class="font-bold">Terjadi kesalahan:</p>
            </div>
        @endif

        <form action="{{ route('student.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Nama -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    {{-- Pesan Error Spesifik --}}
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="gender"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                    {{-- Pesan Error Spesifik --}}
                    @error('gender')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kelas</label>
                    <select name="school_class_id"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}"
                                {{ old('school_class_id', $student->school_class_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Pesan Error Spesifik --}}
                    @error('school_class_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NISN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn', $student->nisn) }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    {{-- Pesan Error Spesifik --}}
                    @error('nisn')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIK -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $student->nik) }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    {{-- Pesan Error Spesifik --}}
                    @error('nik')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place', $student->birth_place) }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    {{-- Pesan Error Spesifik --}}
                    @error('birth_place')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date) }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    {{-- Pesan Error Spesifik --}}
                    @error('birth_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Masuk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun Masuk</label>
                    <input type="number" name="entry_year" value="{{ old('entry_year', $student->entry_year) }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    {{-- Pesan Error Spesifik --}}
                    @error('entry_year')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RFID UID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">RFID UID (UNTUK BARCODE)</label>
                    <input type="text" name="rfid_uid" value="{{ old('rfid_uid', $student->rfid_uid) }}"
                        class="block w-full mt-1 font-mono border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        required>
                    {{-- Pesan Error Spesifik --}}
                    @error('rfid_uid')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Section -->
                <div class="flex items-center col-span-2 gap-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    @if ($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}"
                            class="object-cover w-20 h-20 border-2 border-white rounded-full shadow">
                    @endif
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Ganti Foto</label>
                        <p class="mb-2 text-xs italic text-gray-500">*Kosongkan jika tidak ingin mengubah foto</p>
                        <input type="file" name="photo"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    {{-- Pesan Error Spesifik --}}
                    @error('photo')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('student.index') }}"
                    class="px-4 py-2 text-gray-600 border rounded-md hover:bg-gray-50">Kembali</a>
                <button type="submit"
                    class="px-6 py-2 font-semibold text-white transition-colors bg-green-600 rounded-md shadow-lg hover:bg-green-700">
                    Update Data Siswa
                </button>
            </div>
        </form>
    </div>
@endsection
