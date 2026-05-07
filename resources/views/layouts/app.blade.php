<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - SDITUW</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="overflow-hidden bg-gray-50"> {{-- overflow-hidden di body agar scroll hanya di area konten --}}

    <section id="content" class="flex w-full h-screen"> {{-- h-screen sangat penting di sini --}}

        {{-- SIDEBAR: FIXED/STICKY --}}
        <div id="sidebar" class="w-[280px] h-full flex flex-col shrink-0 justify-between p-6 border-r border-gray-100 bg-[#FBFBFB] overflow-y-auto">
            <div class="flex flex-col w-full gap-8">

               {{-- Logo Section --}}
                <div class="flex flex-col items-center gap-3 p-4 bg-white border border-gray-100 shadow-sm rounded-2xl">
                    <div class="flex items-center justify-center gap-3">
                        <img src="{{ asset('assets/logosdit/sdit_ummatan_wahidah_logo.png') }}" alt="Logo SDIT" class="object-contain w-auto h-10">
                        <div class="w-px h-8 bg-gray-200"></div>
                        <img src="{{ asset('assets/logosdit/yayasan_assalam_logo.png') }}" alt="Logo Yayasan" class="object-contain w-auto h-10">
                    </div>

                    <div class="text-center">
                        <p class="text-[10px] font-black leading-tight text-[#773DCE] uppercase tracking-tighter">
                            Sistem Absensi
                        </p>
                        <p class="text-[9px] font-bold leading-tight text-gray-400 uppercase">
                            SDIT Ummatan Wahidah
                        </p>
                    </div>
                </div>

                <ul class="flex flex-col gap-2">
                    <li><h3 class="px-4 mb-2 text-xs font-bold tracking-widest text-gray-400 uppercase">Menu Utama</h3></li>

                    {{-- Dashboard --}}
                    <li>
                        <a href="{{ route('dashboard') }}"
                        class="group p-3 flex items-center gap-4 rounded-xl transition-all duration-300
                        {{ request()->routeIs('dashboard') ? 'bg-[#773DCE] text-white shadow-lg shadow-purple-200' : 'text-[#7F8190] hover:bg-[#773DCE] hover:text-white hover:shadow-lg hover:shadow-purple-100' }}">
                            <i class="text-lg fa-solid fa-house-chimney"></i>
                            <p class="text-sm font-bold">Dashboard</p>
                        </a>
                    </li>

                    {{-- Data Siswa --}}
                    <li>
                        <a href="{{ route('student.index') }}"
                        class="group p-3 flex items-center gap-4 rounded-xl transition-all duration-300
                        {{ request()->routeIs('student.*') ? 'bg-[#773DCE] text-white shadow-lg shadow-purple-200' : 'text-[#7F8190] hover:bg-[#773DCE] hover:text-white hover:shadow-lg hover:shadow-purple-100' }}">
                            <i class="text-lg fa-solid fa-users-rectangle"></i>
                            <p class="text-sm font-bold">Data Siswa</p>
                        </a>
                    </li>

                    {{-- Data Kelas --}}
                    <li>
                        <a href="{{ route('school_class.index') }}"
                        class="group p-3 flex items-center gap-4 rounded-xl transition-all duration-300
                        {{ request()->routeIs('school_class.*') ? 'bg-[#773DCE] text-white shadow-lg shadow-purple-200' : 'text-[#7F8190] hover:bg-[#773DCE] hover:text-white hover:shadow-lg hover:shadow-purple-100' }}">
                            <i class="text-lg fa-solid fa-school-flag"></i>
                            <p class="text-sm font-bold">Data Kelas</p>
                        </a>
                    </li>

                    <li><h3 class="px-4 mt-6 mb-2 text-xs font-bold tracking-widest text-gray-400 uppercase">Sistem</h3></li>

                    {{-- Jam Masuk --}}
                    <li>
                        <a href="{{ route('setting.index') }}"
                        class="group p-3 flex items-center gap-4 rounded-xl transition-all duration-300
                        {{ request()->routeIs('setting.*') ? 'bg-[#773DCE] text-white shadow-lg shadow-purple-200' : 'text-[#7F8190] hover:bg-[#773DCE] hover:text-white hover:shadow-lg hover:shadow-purple-100' }}">
                            <i class="text-lg fa-solid fa-clock-rotate-left"></i>
                            <p class="text-sm font-bold">Jam Masuk</p>
                        </a>
                    </li>

                    {{-- Scan Absensi --}}
                    <li>
                        <a href="{{ route('scan.index') }}"
                        class="group p-3 flex items-center gap-4 rounded-xl transition-all duration-300
                        {{ request()->is('scan*') ? 'bg-[#773DCE] text-white shadow-lg shadow-purple-200' : 'text-[#7F8190] hover:bg-[#773DCE] hover:text-white hover:shadow-lg hover:shadow-purple-100' }}">
                            <i class="text-lg fa-solid fa-qrcode"></i>
                            <p class="text-sm font-bold">Scan Absensi</p>
                        </a>
                    </li>

                    {{-- Rekap Absensi --}}
                    <li>
                        <a href="{{ route('attendance.recap') }}"
                        class="group p-3 flex items-center gap-4 rounded-xl transition-all duration-300
                        {{ request()->routeIs('attendance.recap') ? 'bg-[#773DCE] text-white shadow-lg shadow-purple-200' : 'text-[#7F8190] hover:bg-[#773DCE] hover:text-white hover:shadow-lg hover:shadow-purple-100' }}">
                            <i class="text-lg fa-solid fa-file-invoice"></i>
                            <p class="text-sm font-bold">Rekap Absensi</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- MAIN CONTENT AREA --}}
        <div id="main-scroll" class="flex flex-col flex-1 h-full overflow-y-auto"> {{-- Area yang bisa di-scroll --}}

            {{-- NAVBAR: STICKY --}}
            <nav class="sticky top-0 z-40 flex justify-end px-8 py-4 border-b border-gray-100 bg-white/80 backdrop-blur-md">
                <div class="flex items-center gap-6">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 focus:outline-none group">
                            <div class="flex flex-col text-right">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Administrator</p>
                                <p class="text-sm font-extrabold text-gray-800 group-hover:text-[#773DCE] transition leading-none">{{ auth()->user()->name }}</p>
                            </div>
                            <div class="w-10 h-10 border-2 border-[#773DCE] rounded-full p-0.5 shadow-sm overflow-hidden">
                                <img src="{{ asset('assets/images/photos/default-photo.svg') }}" alt="photo" class="object-cover w-full h-full rounded-full">
                            </div>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             style="display: none;"
                             class="absolute right-0 z-50 w-48 py-2 mt-3 bg-white border border-gray-100 shadow-2xl rounded-2xl">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-purple-50 hover:text-[#773DCE]">
                                <i class="mr-2 fa-solid fa-user-gear"></i> Edit Profile
                            </a>
                            <hr class="my-2 border-gray-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 text-sm font-bold text-left text-red-500 hover:bg-red-50">
                                    <i class="mr-2 fa-solid fa-power-off"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- CONTENT SECTION --}}
            <main class="w-full p-8">
                @yield('content')
            </main>

        </div>
    </section>
</body>
</html>
