<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-900">
            Informasi Profil
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Perbarui informasi profil akun dan alamat email Anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nama Lengkap" class="font-bold text-gray-700" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full border-gray-200 focus:border-[#773DCE] focus:ring-[#773DCE] rounded-xl shadow-sm" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Alamat Email" class="font-bold text-gray-700" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full border-gray-200 focus:border-[#773DCE] focus:ring-[#773DCE] rounded-xl shadow-sm" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-800">
                        Alamat email Anda belum diverifikasi.
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-[#773DCE] rounded-md focus:outline-none">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600">
                            Link verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-[#773DCE] border border-transparent rounded-xl shadow-lg shadow-purple-100 hover:bg-[#5e2faf] transition-all">
                <i class="mr-2 fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm italic font-medium text-gray-500">
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>
