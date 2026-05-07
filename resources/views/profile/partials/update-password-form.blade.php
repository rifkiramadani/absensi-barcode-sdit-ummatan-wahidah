<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-900">
            Perbarui Kata Sandi
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- Input: Kata Sandi Saat Ini --}}
        <div>
            <x-input-label for="update_password_current_password" value="Kata Sandi Saat Ini" class="font-bold text-gray-700" />
            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full border-gray-200 focus:border-[#773DCE] focus:ring-[#773DCE] rounded-xl shadow-sm transition-all"
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- Input: Kata Sandi Baru --}}
        <div>
            <x-input-label for="update_password_password" value="Kata Sandi Baru" class="font-bold text-gray-700" />
            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full border-gray-200 focus:border-[#773DCE] focus:ring-[#773DCE] rounded-xl shadow-sm transition-all"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- Input: Konfirmasi Kata Sandi --}}
        <div>
            <x-input-label for="update_password_password_confirmation" value="Konfirmasi Kata Sandi" class="font-bold text-gray-700" />
            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full border-gray-200 focus:border-[#773DCE] focus:ring-[#773DCE] rounded-xl shadow-sm transition-all"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            {{-- Tombol Simpan --}}
            <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-[#773DCE] border border-transparent rounded-xl shadow-lg shadow-purple-100 hover:bg-[#5e2faf] transition-all focus:ring-2 focus:ring-[#773DCE] focus:ring-offset-2">
                <i class="mr-2 fa-solid fa-key"></i> Perbarui Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm italic font-medium text-gray-500"
                >
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>
