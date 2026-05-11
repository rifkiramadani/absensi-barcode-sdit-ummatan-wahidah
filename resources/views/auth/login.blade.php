<x-guest-layout>
<style>
    /* Menghilangkan scrollbar dan memastikan ukuran layar pas */
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%;
        overflow: hidden; /* Anti scroll */
    }

    .login-bg {
        height: 100vh;
        width: 100vw;
        background: linear-gradient(135deg, #f5f0ff 0%, #ede9fe 50%, #f0f9ff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden; /* Memastikan shape tidak keluar layar */
    }

    /* Floating shapes */
    .shape {
        position: absolute;
        border-radius: 50%;
        opacity: 0.15;
        animation: float linear infinite;
    }

    .shape-1 { width: 80px; height: 80px; background: #773DCE; top: 10%; left: 8%; animation-duration: 8s; }
    .shape-2 { width: 120px; height: 120px; background: #a78bfa; top: 60%; left: 3%; animation-duration: 11s; animation-delay: -3s; }
    .shape-3 { width: 60px; height: 60px; background: #773DCE; top: 80%; left: 20%; animation-duration: 9s; animation-delay: -1s; }
    .shape-4 { width: 100px; height: 100px; background: #c4b5fd; top: 5%; right: 10%; animation-duration: 10s; animation-delay: -5s; }
    .shape-5 { width: 50px; height: 50px; background: #773DCE; top: 40%; right: 5%; animation-duration: 7s; animation-delay: -2s; }
    .shape-6 { width: 140px; height: 140px; background: #ddd6fe; top: 70%; right: 8%; animation-duration: 13s; animation-delay: -4s; }
    .shape-7 { width: 40px; height: 40px; background: #8b5cf6; top: 25%; left: 40%; animation-duration: 6s; animation-delay: -1.5s; }
    .shape-8 { width: 90px; height: 90px; background: #ede9fe; top: 15%; right: 30%; animation-duration: 12s; animation-delay: -6s; opacity: 0.3; }

    .shape-square {
        border-radius: 12px;
        animation: floatRotate linear infinite;
        opacity: 0.1;
    }
    .sq-1 { width: 60px; height: 60px; background: #773DCE; top: 45%; left: 15%; animation-duration: 14s; }
    .sq-2 { width: 45px; height: 45px; background: #a78bfa; top: 20%; right: 20%; animation-duration: 10s; animation-delay: -3s; }

    @keyframes float {
        0%   { transform: translateY(0px) scale(1); }
        33%  { transform: translateY(-20px) scale(1.05); }
        66%  { transform: translateY(10px) scale(0.95); }
        100% { transform: translateY(0px) scale(1); }
    }

    @keyframes floatRotate {
        0%   { transform: translateY(0px) rotate(0deg); }
        50%  { transform: translateY(-25px) rotate(180deg); }
        100% { transform: translateY(0px) rotate(360deg); }
    }

    .login-card {
        animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .logo-wrapper {
        animation: fadeDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
    }

    @keyframes fadeDown {
        from { opacity: 0; transform: translateY(-15px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .form-field {
        animation: fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    .field-1 { animation-delay: 0.3s; }
    .field-2 { animation-delay: 0.4s; }
    .field-3 { animation-delay: 0.5s; }
    .field-4 { animation-delay: 0.6s; }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .btn-shimmer {
        position: relative;
        overflow: hidden;
    }
    .btn-shimmer::after {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 60%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0%   { left: -100%; }
        50%  { left: 150%; }
        100% { left: 150%; }
    }
</style>

{{-- Container Utama: Menghapus spasi putih di luar layout --}}
<div class="fixed inset-0 z-0 overflow-hidden bg-white">
    <div class="login-bg">

        {{-- Floating Shapes --}}
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
        <div class="shape shape-5"></div>
        <div class="shape shape-6"></div>
        <div class="shape shape-7"></div>
        <div class="shape shape-8"></div>
        <div class="shape shape-square sq-1"></div>
        <div class="shape shape-square sq-2"></div>

        {{-- Card Login --}}
        <div class="relative z-10 w-full max-w-md p-8 mx-4 bg-white border border-gray-100 shadow-2xl login-card rounded-3xl">

            {{-- Logo --}}
            <div class="flex items-center justify-center gap-6 p-3 mb-8 border border-gray-100 logo-wrapper bg-gray-50/50 rounded-2xl">
                <div>
                    <img src="{{ asset('assets/logosdit/sdit_ummatan_wahidah_logo.png') }}"
                        alt="Logo SDIT" class="object-contain w-auto h-16 md:h-20">
                </div>
                <div class="w-px h-12 bg-gray-200"></div>
                <div>
                    <img src="{{ asset('assets/logosdit/yayasan_assalam_logo.png') }}"
                        alt="Logo Yayasan" class="object-contain w-auto h-16 md:h-20">
                </div>
            </div>

            <div class="form-field field-1">
                <h2 class="mb-1 text-2xl font-extrabold text-center text-gray-800">Sistem Absensi</h2>
                <p class="mb-8 text-sm font-medium tracking-widest text-center text-gray-500 uppercase">
                    SDIT Ummatan Wahidah
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div class="form-field field-2">
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

                {{-- Password --}}
                <div class="form-field field-3">
                    <label class="block mb-2 text-xs font-bold text-gray-600 uppercase">Password</label>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm @error('password') text-red-500 @else text-gray-400 @enderror">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input :type="show ? 'text' : 'password'" name="password"
                            class="w-full py-3 pl-10 pr-10 text-sm transition duration-200 border outline-none rounded-xl
                            @error('password') border-red-500 bg-red-50 focus:ring-1 focus:ring-red-500 @else border-gray-200 bg-gray-50 focus:ring-2 focus:ring-[#773DCE] @enderror"
                            placeholder="••••••••" required>
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-[#773DCE] transition">
                            <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-[10px] font-semibold text-red-500 flex items-center gap-1">
                            <i class="fa-solid fa-circle-info"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Tombol Login --}}
                <div class="pt-2 form-field field-4">
                    <button type="submit"
                        class="btn-shimmer w-full py-3.5 px-5 text-white bg-[#773DCE] hover:bg-[#622eb1] font-bold rounded-xl shadow-lg shadow-purple-200 transition duration-300 transform active:scale-95">
                        <i class="mr-2 fa-solid fa-right-to-bracket"></i> Masuk Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-guest-layout>
