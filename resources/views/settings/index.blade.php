@extends('layouts.app')

@section('title', 'Pengaturan Waktu')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[70vh]">
    <div class="w-full max-w-lg p-10 bg-white border border-gray-100 shadow-2xl rounded-3xl">

        <div class="flex flex-col items-center mb-8">
            <div class="p-4 bg-purple-50 rounded-2xl text-[#773DCE] mb-4">
                <i class="text-3xl fa-solid fa-clock-rotate-left"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Pengaturan Waktu</h2>
            <p class="text-sm text-center text-gray-500">Tentukan batas jam masuk siswa dan guru</p>
        </div>

        @if (session('success'))
            <div class="flex p-4 mb-6 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50">
                <i class="mt-0.5 mr-3 fa-solid fa-circle-check text-lg"></i>
                <div><span class="font-bold">Berhasil!</span> {{ session('success') }}</div>
            </div>
        @endif

        <form action="{{ route('setting.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Jam Masuk Siswa --}}
            <div class="mb-6">
                <label class="block mb-3 text-xs font-black tracking-widest text-center text-[#773DCE] uppercase">
                    <i class="mr-1 fa-solid fa-users"></i> Batas Jam Masuk Siswa
                </label>
                <input type="time" name="max_time"
                    value="{{ old('max_time', \Carbon\Carbon::parse($setting->max_time)->format('H:i')) }}"
                    class="block w-full px-4 py-5 text-4xl font-mono text-center text-gray-800 bg-gray-50 border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-purple-100 focus:border-[#773DCE] focus:bg-white transition-all outline-none">
                @error('max_time')
                    <p class="mt-2 text-xs font-bold text-center text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jam Masuk Guru --}}
            <div class="mb-8">
                <label class="block mb-3 text-xs font-black tracking-widest text-center uppercase text-emerald-600">
                    <i class="mr-1 fa-solid fa-chalkboard-user"></i> Batas Jam Masuk Guru
                </label>
                <input type="time" name="teacher_max_time"
                    value="{{ old('teacher_max_time', \Carbon\Carbon::parse($setting->teacher_max_time)->format('H:i')) }}"
                    class="block w-full px-4 py-5 font-mono text-4xl text-center text-gray-800 transition-all border-2 outline-none bg-gray-50 border-emerald-100 rounded-2xl focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 focus:bg-white">
                @error('teacher_max_time')
                    <p class="mt-2 text-xs font-bold text-center text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-4 mb-6 border bg-amber-50 border-amber-100 rounded-xl">
                <p class="text-[11px] leading-relaxed text-amber-700 text-center italic font-medium">
                    <i class="mr-1 fa-solid fa-circle-info"></i>
                    Tap kartu setelah batas waktu akan otomatis tercatat <strong>Terlambat</strong>.
                </p>
            </div>

            <button type="submit"
                class="w-full px-6 py-4 font-bold text-white bg-[#773DCE] shadow-lg shadow-purple-200 rounded-2xl hover:bg-[#5e2faf] active:scale-95 flex items-center justify-center gap-2 transition-all">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
