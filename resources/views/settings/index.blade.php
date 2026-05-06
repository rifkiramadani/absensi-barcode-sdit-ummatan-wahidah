@extends('layouts.app')

@section('title', 'Pengaturan Waktu')

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[80vh]">
        <div class="w-full max-w-md p-8 bg-white border border-gray-100 shadow-xl rounded-2xl">

            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Pengaturan Waktu Absensi</h2>
            </div>

            {{-- Alert Success --}}
            @if (session('success'))
                <div class="flex p-3 mb-6 text-sm text-green-800 border border-green-200 rounded-lg bg-green-50"
                    role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-2" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                    </svg>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <form action="{{ route('setting.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-8">
                    <label class="block mb-2 text-sm font-semibold tracking-wider text-gray-600 uppercase">
                        Batas Maksimal Waktu Masuk
                    </label>
                    <div class="relative">
                        <input type="time" name="max_time"
                            value="{{ old('max_time', \Carbon\Carbon::parse($setting->max_time)->format('H:i')) }}"
                            class="block w-full px-4 py-3 text-2xl font-mono text-center text-gray-700 bg-gray-50 border border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 @error('max_time') border-red-500 @enderror">

                        @error('max_time')
                            <p class="mt-1 text-xs text-center text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <p class="mt-3 text-xs italic text-center text-gray-500">
                        *Siswa yang melakukan tap kartu setelah waktu ini akan dianggap terlambat.
                    </p>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit"
                        class="w-full px-6 py-3 font-bold text-white transition-all bg-blue-600 shadow-md rounded-xl hover:bg-blue-700 active:scale-95">
                        Simpan Perubahan Waktu
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="w-full px-6 py-3 text-sm font-medium text-center text-gray-500 transition-colors hover:text-gray-700">
                        Kembali ke Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
