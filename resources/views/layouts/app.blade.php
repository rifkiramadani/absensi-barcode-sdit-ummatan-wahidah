<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - SDITUW</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
            letter-spacing: -0.01em;
        }

        /* Haluskan transisi hover */
        .nav-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>
</head>
<body class="overflow-hidden bg-[#F9FAFB] text-slate-700">

    <section id="content" class="flex w-full h-screen">

        {{-- SIDEBAR --}}
        <div id="sidebar" class="w-[280px] h-full flex flex-col shrink-0 justify-between p-6 border-r border-slate-100 bg-white overflow-y-auto">
            <div class="flex flex-col w-full gap-9">

               {{-- Logo Section --}}
                <div class="flex flex-col items-center gap-4 px-2 py-6 bg-gradient-to-b from-slate-50 to-white border border-slate-100 rounded-[2rem]">
                    <div class="flex items-center justify-center gap-3">
                        <img src="{{ asset('assets/logosdit/sdit_ummatan_wahidah_logo.png') }}" alt="Logo SDIT" class="object-contain w-auto h-12">
                        <div class="w-px h-8 bg-slate-200"></div>
                        <img src="{{ asset('assets/logosdit/yayasan_assalam_logo.png') }}" alt="Logo Yayasan" class="object-contain w-auto h-12">
                    </div>

                    <div class="text-center">
                        <p class="text-[10px] font-extrabold leading-tight text-[#773DCE] uppercase tracking-[0.2em]">
                            Sistem Absensi
                        </p>
                        <p class="text-[11px] font-medium text-slate-400 uppercase tracking-tighter">
                            SDIT Ummatan Wahidah
                        </p>
                    </div>
                </div>

                <ul class="flex flex-col gap-2">
                    <li><h3 class="px-4 mb-2 text-[11px] font-bold tracking-widest text-slate-300 uppercase">Menu Utama</h3></li>

                   @php
                        $mainMenus = [
                            // Saya ganti ke fa-gauge-high (icon dashboard standar) atau fa-house-chimney
                            ['route' => 'dashboard', 'icon' => 'fa-gauge-high', 'label' => 'Dashboard'],
                            ['route' => 'student.index', 'icon' => 'fa-users-rectangle', 'label' => 'Data Siswa'],
                            ['route' => 'school_class.index', 'icon' => 'fa-school-flag', 'label' => 'Data Kelas'],
                        ];
                        $systemMenus = [
                            ['route' => 'setting.index', 'icon' => 'fa-clock-rotate-left', 'label' => 'Jam Masuk'],
                            ['route' => 'scan.index', 'icon' => 'fa-qrcode', 'label' => 'Scan Absensi'],
                            ['route' => 'attendance.recap', 'icon' => 'fa-file-invoice', 'label' => 'Rekap Absensi'],
                        ];
                    @endphp

                    @foreach($mainMenus as $menu)
                    <li>
                        <a href="{{ route($menu['route']) }}"
                        class="nav-link group p-3.5 flex items-center gap-4 rounded-2xl
                        {{ request()->routeIs($menu['route'])
                            ? 'bg-[#773DCE] text-white shadow-xl shadow-purple-100'
                            : 'text-slate-500 hover:bg-purple-50 hover:text-[#773DCE]' }}">
                            <i class="text-lg fa-solid {{ $menu['icon'] }}"></i>
                            <span class="text-[14px] font-semibold">{{ $menu['label'] }}</span>
                        </a>
                    </li>
                    @endforeach

                    <li><h3 class="px-4 mt-6 mb-2 text-[11px] font-bold tracking-widest text-slate-300 uppercase">Sistem</h3></li>

                    @foreach($systemMenus as $menu)
                    <li>
                        <a href="{{ request()->routeIs($menu['route']) ? '#' : route($menu['route']) }}"
                        class="nav-link group p-3.5 flex items-center gap-4 rounded-2xl
                        {{ (request()->routeIs($menu['route']) || ($menu['route'] == 'scan.index' && request()->is('scan*')))
                            ? 'bg-[#773DCE] text-white shadow-xl shadow-purple-100'
                            : 'text-slate-500 hover:bg-purple-50 hover:text-[#773DCE]' }}">
                            <i class="text-lg fa-solid {{ $menu['icon'] }}"></i>
                            <span class="text-[14px] font-semibold">{{ $menu['label'] }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- MAIN AREA --}}
        <div id="main-scroll" class="flex flex-col flex-1 h-full overflow-y-auto">

            {{-- NAVBAR --}}
            <nav class="sticky top-0 z-40 flex justify-end px-10 py-5 border-b bg-white/80 backdrop-blur-md border-slate-50">
                <div class="flex items-center gap-6">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-4 focus:outline-none group">
                            <div class="flex flex-col text-right">
                                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Administrator</p>
                                <p class="text-sm font-bold text-slate-700 group-hover:text-[#773DCE] transition-colors uppercase tracking-tight">{{ auth()->user()->name }}</p>
                            </div>
                            <div class="w-12 h-12 p-1 bg-white border-2 border-purple-100 rounded-2xl shadow-sm overflow-hidden group-hover:border-[#773DCE] transition-all">
                                <img src="{{ asset('assets/images/photos/default-photo.svg') }}" alt="photo" class="object-cover w-full h-full rounded-xl">
                            </div>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             style="display: none;"
                             class="absolute right-0 z-50 w-56 py-3 mt-4 bg-white border border-slate-100 shadow-2xl rounded-[1.5rem]">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-purple-50 hover:text-[#773DCE] transition-all">
                                <i class="fa-solid fa-user-gear"></i> Profile Settings
                            </a>
                            <div class="my-2 border-b border-slate-50"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full gap-3 px-5 py-3 text-sm font-bold text-red-400 transition-all hover:bg-red-50 hover:text-red-600">
                                    <i class="fa-solid fa-power-off"></i> Logout Aplikasi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- CONTENT SECTION --}}
            <main class="w-full p-10">
                <div class="mx-auto max-w-7xl">
                    @yield('content')
                </div>
            </main>

        </div>
    </section>
</body>
</html>
