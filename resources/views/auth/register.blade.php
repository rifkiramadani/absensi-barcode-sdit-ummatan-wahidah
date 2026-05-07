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

            <div class="mb-8 text-center">
                <h2 class="text-2xl font-extrabold text-gray-800">Daftar Akun</h2>
                <p class="mt-1 text-sm font-medium text-gray-500">Silahkan lengkapi data diri Anda</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Name Field --}}
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm @error('name') text-red-500 @else text-gray-400 @enderror">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full py-3 pl-10 pr-4 text-sm transition duration-200 border outline-none rounded-xl
                            @error('name') border-red-500 bg-red-50 focus:ring-1 focus:ring-red-500 @else border-gray-200 bg-gray-50 focus:ring-2 focus:ring-[#773DCE] @enderror"
                            placeholder="Contoh: Muhammad Rifky Ramadani" required autofocus>
                    </div>
                    @error('name')
                        <p class="mt-1 text-[10px] font-semibold text-red-500 flex items-center gap-1">
                            <i class="fa-solid fa-circle-info"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email Field --}}
                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm @error('email') text-red-500 @else text-gray-400 @enderror">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full py-3 pl-10 pr-4 text-sm transition duration-200 border outline-none rounded-xl
                            @error('email') border-red-500 bg-red-50 focus:ring-1 focus:ring-red-500 @else border-gray-200 bg-gray-50 focus:ring-2 focus:ring-[#773DCE] @enderror"
                            placeholder="email@sekolah.id" required>
                    </div>
                    @error('email')
                        <p class="mt-1 text-[10px] font-semibold text-red-500 flex items-center gap-1">
                            <i class="fa-solid fa-circle-info"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password Grid --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    {{-- Password --}}
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Password</label>
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
                            <p class="mt-1 text-[10px] font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Confirm Password --}}
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-gray-600 uppercase tracking-wider">Konfirmasi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-400">
                                <i class="fa-solid fa-check-double"></i> {{-- Atau gunakan fa-check-double --}}
                            </span>
                            <input type="password" name="password_confirmation"
                                class="w-full py-3 pl-10 pr-4 text-sm transition duration-200 border border-gray-200 bg-gray-50 rounded-xl focus:ring-2 focus:ring-[#773DCE] outline-none"
                                placeholder="••••••••" required>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full mt-4 py-3.5 px-5 text-white bg-[#773DCE] hover:bg-[#622eb1] font-bold rounded-xl text-sm shadow-lg shadow-purple-100 transition duration-300 transform active:scale-95">
                    <i class="mr-2 fa-solid fa-user-plus"></i> Daftar Sekarang
                </button>

                <p class="mt-6 text-sm font-medium text-center text-gray-500">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-bold text-[#773DCE] hover:underline">
                        Login Di Sini
                    </a>
                </p>
            </form>
        </div>
</x-guest-layout>
