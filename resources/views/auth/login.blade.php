<x-guest-layout>
        <div class="w-full max-w-md p-8 rounded-2xl">

            <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">
                Sistem Absensi <br> SDIT UMMATAN WAHIDAH
            </h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email"
                        value="{{ old('email') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                        placeholder="email@gmail.com" required>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                        placeholder="••••••••" required>
                </div>

                <!-- Remember -->
                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="mr-2">
                        Remember me
                    </label>
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Login
                </button>

                <!-- Register -->
                <p class="mt-4 text-sm text-center text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">
                        Daftar
                    </a>
                </p>

            </form>
        </div>
</x-guest-layout>
