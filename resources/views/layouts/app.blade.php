<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - SDITUW</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Alpine.js untuk Dropdown --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <section id="content" class="flex">
        <!-- SIDEBAR -->
        <div id="sidebar" class="w-[270px] flex flex-col shrink-0 min-h-screen justify-between p-[30px] border-r border-[#EEEEEE] bg-[#FBFBFB]">
            <div class="w-full flex flex-col gap-[30px]">
                <a href="{{ route('dashboard') }}" class="flex items-center justify-center">
                    <p class="text-2xl font-bold tracking-widest text-blue-600">SDITUW</p>
                </a>
                <ul class="flex flex-col gap-3">
                    <li>
                        <h3 class="font-bold text-xs text-[#A5ABB2] uppercase tracking-wider">Menu Utama</h3>
                    </li>

                    {{-- Dashboard --}}
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300
                           {{ request()->routeIs('dashboard') ? 'bg-[#2B82FE] text-white shadow-lg shadow-blue-200' : 'text-[#7F8190] hover:bg-[#2B82FE] hover:text-white' }}">
                            <div class="flex shrink-0">
                                <img src="{{ asset('assets/images/icons/home-hashtag.svg') }}" alt="icon"
                                     class="w-6 h-6 {{ request()->routeIs('dashboard') ? 'brightness-0 invert' : '' }}">
                            </div>
                            <p class="font-semibold">Dashboard</p>
                        </a>
                    </li>

                    {{-- Data Siswa --}}
                    <li>
                        <a href="{{ route('student.index') }}"
                           class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300
                           {{ request()->routeIs('student.*') ? 'bg-[#2B82FE] text-white shadow-lg shadow-blue-200' : 'text-[#7F8190] hover:bg-[#2B82FE] hover:text-white' }}">
                            <div class="flex shrink-0">
                                <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" alt="icon"
                                     class="w-6 h-6 {{ request()->routeIs('student.*') ? 'brightness-0 invert' : '' }}">
                            </div>
                            <p class="font-semibold">Data Siswa</p>
                        </a>
                    </li>

                    {{-- Data Kelas --}}
                    <li>
                        <a href="{{ route('school_class.index') }}"
                           class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300
                           {{ request()->routeIs('school_class.*') ? 'bg-[#2B82FE] text-white shadow-lg shadow-blue-200' : 'text-[#7F8190] hover:bg-[#2B82FE] hover:text-white' }}">
                            <div class="flex shrink-0">
                                <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" alt="icon"
                                     class="w-6 h-6 {{ request()->routeIs('school_class.*') ? 'brightness-0 invert' : '' }}">
                            </div>
                            <p class="font-semibold">Data Kelas</p>
                        </a>
                    </li>

                    <li>
                        <h3 class="font-bold text-xs text-[#A5ABB2] uppercase tracking-wider mt-4">Sistem</h3>
                    </li>

                    {{-- Pengaturan Jam --}}
                    <li>
                        <a href="{{ route('setting.index') }}"
                           class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300
                           {{ request()->routeIs('setting.*') ? 'bg-[#2B82FE] text-white shadow-lg shadow-blue-200' : 'text-[#7F8190] hover:bg-[#2B82FE] hover:text-white' }}">
                            <div class="flex shrink-0">
                                <img src="{{ asset('assets/images/icons/profile-2user.svg') }}" alt="icon"
                                     class="w-6 h-6 {{ request()->routeIs('setting.*') ? 'brightness-0 invert' : '' }}">
                            </div>
                            <p class="font-semibold">Jam Masuk</p>
                        </a>
                    </li>

                    {{-- Scan Absensi --}}
                    <li>
                        <a href="#"
                           class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300
                           {{ request()->is('scan*') ? 'bg-[#2B82FE] text-white shadow-lg shadow-blue-200' : 'text-[#7F8190] hover:bg-[#2B82FE] hover:text-white' }}">
                            <div class="flex shrink-0">
                                <img src="{{ asset('assets/images/icons/sms-tracking.svg') }}" alt="icon"
                                     class="w-6 h-6 {{ request()->is('scan*') ? 'brightness-0 invert' : '' }}">
                            </div>
                            <p class="font-semibold">Scan Absensi</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div id="menu-content" class="flex flex-col w-full pb-[30px]">
            <!-- TOP NAV -->
            <div class="nav flex justify-end p-5 border-b border-[#EEEEEE] bg-white">
                <div class="flex items-center gap-[30px]">
                    <div class="h-[46px] w-[1px] flex shrink-0 border border-[#EEEEEE]"></div>

                    <!-- Dropdown User -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 focus:outline-none">
                            <div class="flex flex-col text-right">
                                <p class="text-xs text-[#7F8190]">Administrator</p>
                                <p class="text-sm font-bold text-gray-800">{{ auth()->user()->name }}</p>
                            </div>
                            <div class="w-[46px] h-[46px] border-2 border-blue-500 rounded-full p-0.5">
                                <img src="{{ asset('assets/images/photos/default-photo.svg') }}" alt="photo" class="object-cover w-full h-full rounded-full">
                            </div>
                        </button>

                        <div x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 z-50 w-48 py-2 mt-2 bg-white border border-gray-200 shadow-xl rounded-xl"
                            style="display: none;">

                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-100">
                                Edit Profile
                            </a>

                            <hr class="my-1 border-gray-100">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 text-sm font-semibold text-left text-red-600 transition hover:bg-red-50">
                                    Keluar Sistem
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DYNAMIC CONTENT -->
            <div class="w-full p-8">
                @yield('content')
            </div>
        </div>
    </section>
</body>
</html>
