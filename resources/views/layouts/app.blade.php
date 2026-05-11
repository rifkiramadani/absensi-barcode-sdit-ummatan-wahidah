<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - SDITUW</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/logosdit/sdit_ummatan_wahidah_logo.png') }}">

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
        }
        [x-cloak] { display: none !important; }

        .main-wrapper {
            display: flex;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
        }

        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        /* CSS Tambahan untuk memastikan dropdown selalu di depan */
        .dropdown-top {
            z-index: 9999 !important;
        }
    </style>
</head>

<body class="bg-[#F9FAFB] text-slate-700">

    <div class="main-wrapper">

        <aside class="w-[260px] h-full flex flex-col shrink-0 border-r border-slate-100 bg-white z-40">

            {{-- Logo --}}
            <div class="px-5 pt-5 pb-4 border-b border-slate-100">
                <div class="flex flex-col items-center gap-3 px-2 py-4 bg-gradient-to-b from-slate-50 to-white border border-slate-100 rounded-[1.5rem]">
                    <div class="flex items-center justify-center gap-3">
                        <img src="{{ asset('assets/logosdit/sdit_ummatan_wahidah_logo.png') }}" class="w-auto h-10">
                        <div class="w-px h-7 bg-slate-200"></div>
                        <img src="{{ asset('assets/logosdit/yayasan_assalam_logo.png') }}" class="w-auto h-10">
                    </div>
                    <p class="text-[9px] font-extrabold text-[#773DCE] uppercase tracking-[0.2em]">Sistem Absensi</p>
                </div>
            </div>

            {{-- Menu --}}
            <nav class="flex flex-col flex-1 gap-1 px-4 py-3 overflow-hidden">

                @php
                $groups = [
                    [
                        'label' => 'Utama',
                        'menus' => [
                            ['route' => 'dashboard',    'icon' => 'fa-gauge-high',    'label' => 'Dashboard'],
                        ]
                    ],
                    [
                        'label' => 'Master Data',
                        'menus' => [
                            ['route' => 'student.index',      'icon' => 'fa-users-rectangle',  'label' => 'Data Siswa'],
                            ['route' => 'school_class.index',  'icon' => 'fa-school-flag',      'label' => 'Data Kelas'],
                            ['route' => 'teacher.index',       'icon' => 'fa-chalkboard-user',  'label' => 'Data Guru'],
                            ['route' => 'setting.index',       'icon' => 'fa-clock-rotate-left','label' => 'Jam Masuk'],
                        ]
                    ],
                    [
                        'label' => 'Absensi',
                        'menus' => [
                            ['route' => 'scan.index',               'icon' => 'fa-qrcode',       'label' => 'Scan Absensi'],
                            ['route' => 'attendance.recap',         'icon' => 'fa-file-invoice', 'label' => 'Rekap Siswa'],
                            ['route' => 'teacher_attendance.recap', 'icon' => 'fa-file-invoice', 'label' => 'Rekap Guru'],
                        ]
                    ],
                    [
                        'label' => 'Catatan',
                        'menus' => [
                            ['route' => 'student_case.index', 'icon' => 'fa-book-open', 'label' => 'Buku Catatan'],
                        ]
                    ],
                ];
                @endphp

                @foreach($groups as $group)
                    <div class="mb-1">
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.18em] px-3 mb-1">
                            {{ $group['label'] }}
                        </p>
                        @foreach($group['menus'] as $menu)
                        <a href="{{ route($menu['route']) }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 mb-0.5
                            {{ request()->routeIs($menu['route'])
                                ? 'bg-[#773DCE] text-white shadow-md shadow-purple-200'
                                : 'text-slate-500 hover:bg-purple-50 hover:text-[#773DCE]' }}">
                            <i class="fa-solid {{ $menu['icon'] }} text-base w-4 text-center"></i>
                            <span class="text-sm font-semibold">{{ $menu['label'] }}</span>
                        </a>
                        @endforeach
                    </div>
                @endforeach

            </nav>

            {{-- Footer sidebar --}}
            <div class="px-5 py-4 border-t border-slate-100">
                <p class="text-[9px] text-center text-slate-300 font-medium">SDIT Ummatan Wahidah © {{ date('Y') }}</p>
            </div>

        </aside>

        <div class="flex flex-col flex-1 h-full min-w-0 overflow-hidden">

            <header class="h-20 bg-white border-b border-slate-100 px-10 flex justify-end items-center sticky top-0 z-[100]">
                <div x-data="{ open: false }" class="relative overflow-visible">
                    <button
                        @click.stop="open = !open"
                        @mousedown.stop
                        class="flex items-center gap-4 group focus:outline-none cursor-pointer relative z-[110]">
                        <div class="flex flex-col text-right">
                            <p class="text-[10px] font-bold text-slate-300 uppercase leading-none mb-1">Administrator</p>
                            <p class="text-sm font-bold text-slate-700 group-hover:text-[#773DCE] transition">
                                {{ auth()->user()->name }}
                            </p>
                        </div>
                        <div class="w-11 h-11 border-2 border-purple-100 rounded-xl overflow-hidden group-hover:border-[#773DCE]">
                            <img src="{{ asset('assets/images/photos/default-photo.svg') }}" class="object-cover w-full h-full">
                        </div>
                    </button>

                    <div
                        x-show="open"
                        x-cloak
                        @click.away="open = false"
                        class="absolute right-0 py-2 mt-2 bg-white border shadow-xl w-60 border-slate-200 rounded-2xl dropdown-top">

                        <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-purple-50 hover:text-[#773DCE] transition pointer-events-auto">
                            <i class="fa-solid fa-user-gear"></i>
                            <span>Profile Settings</span>
                        </a>

                        <div class="my-1 border-b border-slate-100"></div>

                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit"
                                    class="flex items-center w-full gap-3 px-5 py-3 text-sm font-bold text-red-500 transition cursor-pointer pointer-events-auto hover:bg-red-50">
                                <i class="fa-solid fa-power-off"></i>
                                <span>Logout Aplikasi</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto custom-scroll p-10 bg-[#F9FAFB]">
                <div class="mx-auto max-w-7xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

</body>
</html>
