@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
    <div class="max-w-2xl p-8 mx-auto bg-white border border-gray-100 shadow-md rounded-xl">
        <h2 class="pb-4 mb-6 text-2xl font-bold text-gray-800 border-b">Edit Kelas</h2>

        <form action="{{ route('school_class.update', $schoolClass->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                <input type="text" name="name" value="{{ old('name', $schoolClass->name) }}"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                    placeholder="Contoh: Kelas 1-A" required>

                {{-- Error Spesifik di Bawah Form --}}
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('school_class.index') }}"
                    class="px-4 py-2 text-gray-600 border rounded-md hover:bg-gray-50">Batal</a>
                <button type="submit"
                    class="px-6 py-2 text-white bg-blue-600 rounded-md shadow-lg hover:bg-blue-700">Simpan Kelas</button>
            </div>
        </form>
    </div>
@endsection
