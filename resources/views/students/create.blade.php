@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
    <div class="max-w-4xl p-8 mx-auto bg-white border border-gray-100 shadow-md rounded-xl">
        <h2 class="pb-4 mb-6 text-2xl font-bold text-gray-800 border-b">Tambah Siswa Baru</h2>

        {{-- Tampilkan Pesan Error Umum di Atas --}}
        @if ($errors->any())
            <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <p class="font-bold">Terjadi kesalahan:</p>
            </div>
        @endif

        <form action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Nama -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                        required>
                    {{-- Pesan Error Spesifik --}}
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NISN & NIK -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    {{-- Pesan Error Spesifik --}}
                    @error('nisn')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik') }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    {{-- Pesan Error Spesifik --}}
                    @error('nik')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tempat & Tanggal Lahir -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                    <input type="text" name="birth_place" value="{{ old('birth_place') }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    {{-- Pesan Error Spesifik --}}
                    @error('birth_place')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    {{-- Pesan Error Spesifik --}}
                    @error('birth_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender & Kelas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="gender"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    {{-- Pesan Error Spesifik --}}
                    @error('gender')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kelas</label>
                    <select name="school_class_id"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" {{ old('school_class_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}</option>
                        @endforeach
                    </select>
                    {{-- Pesan Error Spesifik --}}
                    @error('school_class_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Entry Year -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun Masuk</label>
                    <input type="number" name="entry_year" value="{{ old('entry_year', date('Y')) }}"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    {{-- Pesan Error Spesifik --}}
                    @error('entry_year')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RFID UID -->
                <div class="col-span-2 p-4 border border-yellow-200 rounded-lg bg-yellow-50">
                    <label class="block text-sm font-bold tracking-wider text-yellow-800 uppercase">RFID UID (UNTUK
                        BARCODE)</label>
                    <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}" placeholder="Scan kartu..."
                        class="block w-full mt-1 font-mono text-lg border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm">
                    {{-- Pesan Error Spesifik --}}
                    @error('rfid_uid')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Foto Siswa</label>
                    <input type="file" name="photo"
                        class="block w-full mt-1 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    {{-- Pesan Error Spesifik --}}
                    @error('photo')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('student.index') }}"
                    class="px-4 py-2 text-gray-600 border rounded-md hover:bg-gray-50">Batal</a>
                <button type="submit"
                    class="px-6 py-2 text-white bg-blue-600 rounded-md shadow-lg hover:bg-blue-700">Simpan Data
                    Siswa</button>
            </div>
        </form>
    </div>
@endsection
