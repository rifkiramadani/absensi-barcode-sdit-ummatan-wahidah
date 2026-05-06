<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <section id="content" class="flex">
        <div id="sidebar" class="w-[270px] flex flex-col shrink-0 min-h-screen justify-between p-[30px] border-r border-[#EEEEEE] bg-[#FBFBFB]">
            <div class="w-full flex flex-col gap-[30px]">
                <a href="index.html" class="flex items-center justify-center">
                    <p>SDITUW</p>
                </a>
                <ul class="flex flex-col gap-3">
                    <li>
                        <h3 class="font-bold text-xs text-[#A5ABB2]">MENU</h3>
                    </li>
                    <li>
                        <a href="{{route('dashboard')}}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 bg-[#2B82FE] transition-all duration-300 hover:bg-[#2B82FE]">
                            <div>
                                <img src="{{asset("assets/images/icons/home-hashtag.svg")}}" alt="icon">
                            </div>
                            <p class="font-semibold transition-all duration-300 hover:text-white">Dashboard</p>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('student.index')}}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11  transition-all duration-300 hover:bg-[#2B82FE]">
                            <div>
                                <img src="{{asset("assets/images/icons/profile-2user.svg")}}" alt="icon">
                            </div>
                            <p class="font-semibold transition-all duration-300 hover:text-white">Data Siswa</p>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('school_class.index')}}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300 hover:bg-[#2B82FE]">
                            <div>
                                <img src="{{asset("assets/images/icons/profile-2user.svg")}}" alt="icon">
                            </div>
                            <p class="font-semibold transition-all duration-300 hover:text-white">Data Kelas</p>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('school_class.index')}}" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300 hover:bg-[#2B82FE]">
                            <div>
                                <img src="{{asset("assets/images/icons/profile-2user.svg")}}" alt="icon">
                            </div>
                            <p class="font-semibold transition-all duration-300 hover:text-white">Pengaturan Jam Masuk</p>
                        </a>
                    </li>
                    <li>
                        <a href="" class="p-[10px_16px] flex items-center gap-[14px] rounded-full h-11 transition-all duration-300 hover:bg-[#2B82FE]">
                            <div>
                                <img src="{{asset("assets/images/icons/sms-tracking.svg")}}" alt="icon">
                            </div>
                            <p class="font-semibold transition-all duration-300 hover:text-white">Scan Absensi</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div id="menu-content" class="flex flex-col w-full pb-[30px]">
            <div class="nav flex justify-end p-5 border-b border-[#EEEEEE]">
                {{-- <form class="search flex items-center w-[400px] h-[52px] p-[10px_16px] rounded-full border border-[#EEEEEE]">
                    <input type="text" class="font-semibold placeholder:text-[#7F8190] placeholder:font-normal w-full outline-none" placeholder="Search by report, student, etc" name="search">
                    <button type="submit" class="ml-[10px] w-8 h-8 flex items-center justify-center">
                        <img src="{{asset("assets/images/icons/search.svg")}}" alt="icon">
                    </button>
                </form> --}}
                <div class="flex items-center gap-[30px]">
                    <div class="h-[46px] w-[1px] flex shrink-0 border border-[#EEEEEE]"></div>

                    <!-- Dropdown Container -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 focus:outline-none">
                            <div class="flex flex-col text-right">
                                <p class="text-sm text-[#7F8190]">Hallo</p>
                                <p class="font-semibold">{{ auth()->user()->name }}</p>
                            </div>
                            <div class="w-[46px] h-[46px]">
                                <img src="{{ asset('assets/images/photos/default-photo.svg') }}" alt="photo" class="rounded-full">
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 z-50 w-48 py-2 mt-2 bg-white border border-gray-200 shadow-lg rounded-xl"
                            style="display: none;">

                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-100">
                                Edit Profile
                            </a>

                            <hr class="my-1 border-gray-100">

                            <!-- Authentication / Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 text-sm text-left text-red-600 transition hover:bg-red-50">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
                <div class="w-full">
                    @yield('content')
                </div>
            <!-- CONTENT -->

            {{-- <div id="pagiantion" class="flex gap-4 items-center mt-[37px] px-5">
                <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">1</button>
                <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">2</button>
                <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-white bg-[#0A090B]">3</button>
                <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">4</button>
                <button class="flex items-center justify-center border border-[#EEEEEE] rounded-full w-10 h-10 font-semibold transition-all duration-300 hover:text-white hover:bg-[#0A090B] text-[#7F8190]">5</button>
            </div> --}}
        </div>
    </section>

</body>
</html>
