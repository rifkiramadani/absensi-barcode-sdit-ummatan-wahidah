<x-guest-layout>
        <div class="w-full max-w-md p-8 bg-white border border-gray-100 shadow-2xl rounded-3xl">

            <div class="mb-8 text-center">
                <h2 class="text-2xl font-extrabold text-gray-800">Daftar Akun</h2>
                <p class="mt-1 text-sm font-medium text-gray-500">Silahkan lengkapi data diri Anda</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-600 uppercase">Nama Lengkap</label>
                    <input type="text" name="name"
                        class="w-full px-4 py-3 text-sm transition duration-200 border border-gray-200 outline-none bg-gray-50 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Contoh: Budi Santoso" required autofocus>
                </div>

                <div>
                    <label class="block mb-1.5 text-xs font-bold text-gray-600 uppercase">Email</label>
                    <input type="email" name="email"
                        class="w-full px-4 py-3 text-sm transition duration-200 border border-gray-200 outline-none bg-gray-50 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="email@sekolah.id" required>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-gray-600 uppercase">Password</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-3 text-sm transition duration-200 border border-gray-200 outline-none bg-gray-50 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="••••••••" required>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-xs font-bold text-gray-600 uppercase">Konfirmasi</label>
                        <input type="password" name="password_confirmation"
                            class="w-full px-4 py-3 text-sm transition duration-200 border border-gray-200 outline-none bg-gray-50 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit"
                    class="w-full mt-4 py-3.5 px-5 text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-xl text-sm shadow-lg shadow-blue-200 transition duration-300 transform hover:-translate-y-0.5 active:scale-95">
                    Daftar Sekarang
                </button>

                <p class="mt-6 text-sm text-center text-gray-500">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 transition duration-200 hover:text-blue-800">
                        Login Di Sini
                    </a>
                </p>
            </form>
        </div>
</x-guest-layout>
