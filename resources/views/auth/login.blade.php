<x-guest-layout>
        <div class="w-full max-w-md p-8 bg-white border border-gray-100 shadow-2xl rounded-3xl">

            <div class="flex justify-center mb-6">
                <div class="p-4 text-3xl bg-blue-50 rounded-2xl">
                    🏫
                </div>
            </div>

            <h2 class="mb-2 text-2xl font-extrabold text-center text-gray-800">
                Sistem Absensi
            </h2>
            <p class="mb-8 text-sm font-medium tracking-widest text-center text-gray-500 uppercase">
                SDIT Ummatan Wahidah
            </p>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block mb-2 text-xs font-bold text-gray-600 uppercase">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-400">
                            📧
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full py-3 pl-10 pr-4 text-sm text-gray-900 transition duration-200 border border-gray-200 outline-none bg-gray-50 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="nama@email.com" required autofocus>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between mb-2">
                        <label class="text-xs font-bold text-gray-600 uppercase">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-400">
                            🔒
                        </span>
                        <input type="password" name="password"
                            class="w-full py-3 pl-10 pr-4 text-sm text-gray-900 transition duration-200 border border-gray-200 outline-none bg-gray-50 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="••••••••" required>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 mr-2 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        Ingat Saya
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-5 text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-xl text-sm shadow-lg shadow-blue-200 transition duration-300 transform hover:-translate-y-0.5 active:scale-95">
                    Masuk Sekarang
                </button>

                <p class="pt-4 text-sm text-center text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 transition duration-200 hover:text-blue-800">
                        Buat Akun
                    </a>
                </p>
            </form>
        </div>
</x-guest-layout>
