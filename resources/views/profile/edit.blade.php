@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    {{-- Bagian Header: Mengikuti layout Data Siswa --}}
    <div class="p-6 bg-white border border-gray-100 shadow-sm sm:flex sm:items-center rounded-2xl">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-bold text-gray-800">Pengaturan Profil</h1>
            <p class="mt-2 text-sm text-gray-500">Manajemen informasi akun, keamanan password, dan penghapusan akun sistem SDIT Ummatan Wahidah.</p>
        </div>
    </div>

    {{-- Bagian Alert (Jika berhasil simpan) --}}
    @if (session('status'))
        <div class="flex p-4 mt-6 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50" role="alert">
            <i class="mt-1 mr-3 text-lg fa-solid fa-circle-check"></i>
            <div><span class="font-bold">Berhasil!</span> {{ session('status') }}</div>
        </div>
    @endif

    <div class="flex flex-col mt-8 space-y-6">

        {{-- Section: Informasi Profil --}}
        <div class="p-6 transition-all bg-white border border-gray-100 shadow-sm rounded-2xl hover:shadow-md">
            <div class="max-w-xl">
                <header class="pb-4 mb-6 border-b border-gray-50">
                    <h2 class="text-lg font-bold text-[#773DCE] flex items-center gap-2">
                        <i class="fa-solid fa-user-pen"></i> Informasi Profil
                    </h2>
                    <p class="mt-1 text-xs font-medium tracking-wider text-gray-400 uppercase">Detail Identitas Akun</p>
                </header>
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Section: Keamanan Password --}}
        <div class="p-6 transition-all bg-white border border-gray-100 shadow-sm rounded-2xl hover:shadow-md">
            <div class="max-w-xl">
                <header class="pb-4 mb-6 border-b border-gray-50">
                    <h2 class="text-lg font-bold text-[#773DCE] flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved"></i> Keamanan Akun
                    </h2>
                    <p class="mt-1 text-xs font-medium tracking-wider text-gray-400 uppercase">Perbarui Kata Sandi</p>
                </header>
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Section: Hapus Akun (Area Berisiko) --}}
        <div class="p-6 border border-red-100 shadow-sm bg-red-50/50 rounded-2xl">
            <div class="max-w-xl">
                <header class="pb-4 mb-6 border-b border-red-100">
                    <h2 class="flex items-center gap-2 text-lg font-bold text-red-600">
                        <i class="fa-solid fa-triangle-exclamation"></i> Hapus Akun
                    </h2>
                    <p class="mt-1 text-xs font-medium tracking-wider text-red-400 uppercase">Tindakan Tidak Dapat Dibatalkan</p>
                </header>
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection
