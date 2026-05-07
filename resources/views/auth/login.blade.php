<x-guest-layout>
        <div class="w-full max-w-md p-8 bg-white border border-gray-100 shadow-2xl rounded-3xl">

            {{-- Bagian Logo Berjejer --}}
            <div class="flex items-center justify-center gap-6 p-3 mb-8 border border-gray-100 bg-gray-50/50 rounded-2xl">
                {{-- Logo 1: SDIT Ummatan Wahidah --}}
                <img src="{{ asset('assets/logosdit/sdit_ummatan_wahidah_logo.png') }}" alt="Logo SDIT Ummatan Wahidah" class="object-contain w-auto h-20">

                {{-- Pembatas Vertikal --}}
                <div class="w-px h-16 bg-gray-200"></div>

                {{-- Logo 2: Yayasan As-Salam --}}
                <img src="{{ asset('assets/logosdit/yayasan_assalam_logo.png') }}" alt="Logo Yayasan As-Salam" class="object-contain w-auto h-20">
            </div>

            <h2 class="mb-2 text-2xl font-extrabold text-center text-gray-800">
                Sistem Absensi
            </h2>
            <p class="mb-8 text-sm font-medium tracking-widest text-center text-gray-500 uppercase">
                SDIT Ummatan Wahidah
            </p>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email Field --}}
                <div>
                    <label class="block mb-2 text-xs font-bold text-gray-600 uppercase">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm @error('email') text-red-500 @else text-gray-400 @enderror">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full py-3 pl-10 pr-4 text-sm transition duration-200 border outline-none rounded-xl
                            @error('email') border-red-500 bg-red-50 focus:ring-1 focus:ring-red-500 @else border-gray-200 bg-gray-50 focus:ring-2 focus:ring-[#773DCE] @enderror"
                            placeholder="nama@email.com" required autofocus>
                    </div>
                    @error('email')
                        <p class="mt-1 text-[10px] font-semibold text-red-500 flex items-center gap-1">
                            <i class="fa-solid fa-circle-info"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div>
                    <div class="flex justify-between mb-2">
                        <label class="text-xs font-bold text-gray-600 uppercase">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm @error('password') text-red-500 @else text-gray-400 @enderror">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password"
                            class="w-full py-3 pl-10 pr-4 text-sm transition duration-200 border outline-none rounded-xl
                            @error('password') border-red-500 bg-red-50 focus:ring-1 focus:ring-red-500 @else border-gray-200 bg-gray-50 focus:ring-2 focus:ring-[#773DCE] @enderror"
                            placeholder="••••••••" required>
                    </div>
                    @error('password')
                        <p class="mt-1 text-[10px] font-semibold text-red-500 flex items-center gap-1">
                            <i class="fa-solid fa-circle-info"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                {{-- <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 mr-2 border-gray-300 rounded text-[#773DCE] focus:ring-[#773DCE]">
                        Ingat Saya
                    </label>
                </div> --}}

                {{-- Login Button (Warna Ungu Sekolah) --}}
                <button type="submit" class="w-full py-3.5 px-5 text-white bg-[#773DCE] hover:bg-[#622eb1] font-bold rounded-xl shadow-lg shadow-purple-200 transition duration-300 transform active:scale-95">
                    <i class="mr-2 fa-solid fa-right-to-bracket"></i> Masuk Sekarang
                </button>

                <p class="pt-4 text-sm font-medium text-center text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-[#773DCE] transition duration-200 hover:underline">
                        Daftar Di Sini
                    </a>
                </p>
            </form>
        </div>
</x-guest-layout>
