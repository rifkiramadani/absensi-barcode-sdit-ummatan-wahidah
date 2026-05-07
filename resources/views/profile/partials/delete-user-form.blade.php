<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-red-700">
            Hapus Akun
        </h2>
        <p class="mt-1 text-sm text-red-600">
            Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun, harap unduh data atau informasi apa pun yang ingin Anda simpan.
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-red-600 border border-transparent rounded-xl shadow-lg shadow-red-100 hover:bg-red-700 transition-all"
    >
        <i class="mr-2 fa-solid fa-trash-can"></i> Hapus Akun
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-gray-900">
                Apakah Anda yakin ingin menghapus akun?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Setelah akun Anda dihapus, semua data akan hilang selamanya. Masukkan kata sandi Anda untuk mengonfirmasi penghapusan permanen ini.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Kata Sandi" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full mt-1 border-gray-200 focus:border-red-500 focus:ring-red-500 rounded-xl"
                    placeholder="Masukkan Kata Sandi Anda"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 text-sm font-bold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-all">
                    Batal
                </button>

                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 transition-all">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
