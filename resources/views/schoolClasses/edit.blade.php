@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
    <div class="max-w-2xl p-8 mx-auto bg-white border border-gray-100 shadow-sm rounded-2xl">
        <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-50">
            <div class="p-2 bg-purple-50 rounded-lg text-[#773DCE]">
                <i class="text-xl fa-solid fa-pen-to-square"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Kelas</h2>
        </div>

        <form action="{{ route('school_class.update', $schoolClass->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700">Nama Kelas</label>
                <input type="text" name="name" value="{{ old('name', $schoolClass->name) }}"
                    class="block w-full mt-2 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm @error('name') border-red-300 @enderror"
                    placeholder="Contoh: Kelas 1-A" required>

                @error('name')
                    <p class="flex items-center gap-1 mt-2 text-xs text-red-600">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('school_class.index') }}"
                    class="px-6 py-2.5 text-sm font-bold text-gray-500 border border-gray-100 rounded-xl hover:bg-gray-50 transition-all">Batal</a>
                <button type="submit"
                    class="px-8 py-2.5 text-sm font-bold text-white bg-green-600 rounded-xl shadow-lg shadow-green-100 hover:bg-green-700 transition-all">
                    Update Kelas
                </button>
            </div>
        </form>
    </div>
@endsection
