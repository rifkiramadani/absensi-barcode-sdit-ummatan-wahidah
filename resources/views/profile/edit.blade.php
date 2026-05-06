{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Profile') }}
        </h2>
    </x-slot>


</x-app-layout> --}}

@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
     <div class="py-2">
         <div class="max-w-5xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
               <div class="sm:flex-auto">
                   <h1 class="text-2xl font-semibold text-gray-900">Edit Profile</h1>
                   <p class="mt-2 text-sm text-gray-700">Manajemen data profil, password, dan penghapusan akun.</p>
               </div>
           </div>
            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 bg-red-400 shadow sm:p-8 sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
