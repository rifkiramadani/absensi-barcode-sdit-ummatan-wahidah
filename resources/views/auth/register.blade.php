<x-guest-layout>
        <div class="w-full max-w-md p-8shadow-lg rounded-2xl">

            <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">
                Register
            </h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="name"
                        class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5"
                        required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email"
                        class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5"
                        required>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password"
                        class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5"
                        required>
                </div>

                <!-- Confirm -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5"
                        required>
                </div>

                 <!-- Button -->
                <button type="submit"
                    class="w-full text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Register
                </button>

                <p class="mt-4 text-sm text-center text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                        Login
                    </a>
                </p>

            </form>
        </div>
</x-guest-layout>
