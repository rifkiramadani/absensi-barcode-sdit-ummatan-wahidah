@extends('layouts.app')

@section('title', 'Edit Guru')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl p-8 mx-auto bg-white border border-gray-100 shadow-sm rounded-2xl">

        <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-50">
            <div class="p-2 bg-purple-50 rounded-lg text-[#773DCE]">
                <i class="text-xl fa-solid fa-user-pen"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Guru: <span class="text-[#773DCE]">{{ $teacher->name }}</span></h2>
        </div>

        <form action="{{ route('teacher.update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $teacher->name) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm" required>
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Jenis Kelamin</label>
                    <select name="gender" class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                        <option value="L" {{ old('gender', $teacher->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $teacher->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip', $teacher->nip) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                    @error('nip') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $teacher->jabatan) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                    @error('jabatan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $teacher->no_hp) }}"
                        class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                    @error('no_hp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Status</label>
                    <select name="is_active" class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                        <option value="1" {{ old('is_active', $teacher->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $teacher->is_active) == 0 ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                <div class="col-span-2 p-5 border-2 border-purple-100 border-dashed rounded-2xl bg-purple-50/30">
                    <label class="block text-xs font-black tracking-widest text-[#773DCE] uppercase">RFID UID</label>
                    <div class="relative mt-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-purple-400">
                            <i class="fa-solid fa-rss"></i>
                        </span>
                        <input type="text" name="rfid_uid" value="{{ old('rfid_uid', $teacher->rfid_uid) }}"
                            class="block w-full pl-10 font-mono text-lg bg-white border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-200">
                    </div>
                    @error('rfid_uid') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-2">
                <div class="flex items-center gap-6 p-6 border border-gray-100 rounded-2xl bg-gray-50">

                    {{-- Foto Preview --}}
                    <div class="relative flex-shrink-0 group">
                        <img id="photoPreview"
                            src="{{ $teacher->photo ? asset('storage/'.$teacher->photo) : asset('assets/images/photos/default-photo.svg') }}"
                            class="object-cover w-24 h-24 border-4 border-white shadow-md rounded-2xl ring-1 ring-gray-100 cursor-zoom-in hover:ring-2 hover:ring-[#773DCE] transition-all"
                            onclick="bukaPreviewFoto(this.src, '{{ addslashes($teacher->name) }}')"
                            title="Klik untuk preview">
                        <div class="absolute inset-0 flex items-center justify-center transition-opacity opacity-0 pointer-events-none rounded-2xl bg-black/20 group-hover:opacity-100">
                            <i class="text-lg text-white fa-solid fa-magnifying-glass-plus"></i>
                        </div>
                        {{-- Tombol hapus foto --}}
                        @if($teacher->photo)
                        <button type="button" id="btnHapusFoto"
                            onclick="hapusFotoGuru()"
                            class="absolute z-10 flex items-center justify-center w-6 h-6 text-white transition-all bg-red-500 rounded-full shadow-md -top-2 -right-2 hover:bg-red-600"
                            title="Hapus foto">
                            <i class="text-xs fa-solid fa-xmark"></i>
                        </button>
                        @endif
                    </div>

                    <div class="flex-1">
                        <p class="mb-2 text-xs italic text-gray-400">*Kosongkan jika foto tidak ingin diganti</p>
                        <input type="file" name="photo" id="photoInput" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-[#773DCE] hover:file:bg-purple-100">
                        <p class="mt-2 text-[10px] text-gray-400">Format: PNG, JPG, JPEG (Maks. 2MB)</p>

                        {{-- Info foto dihapus --}}
                        <p id="infoHapusFoto" class="hidden mt-2 text-xs font-semibold text-red-500">
                            <i class="mr-1 fa-solid fa-triangle-exclamation"></i> Foto akan dihapus saat disimpan
                        </p>

                        @error('photo')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Hidden input untuk tandai hapus foto --}}
                <input type="hidden" name="hapus_foto" id="hapusFotoInput" value="0">
            </div>

            </div>

            <div class="flex justify-end gap-3 mt-10">
                <a href="{{ route('teacher.index') }}"
                    class="px-6 py-3 text-sm font-bold text-gray-500 transition-all border border-gray-100 rounded-xl hover:bg-gray-50">Kembali</a>
                <button type="submit"
                    class="px-10 py-3 text-sm font-bold text-white bg-[#773DCE] rounded-xl shadow-lg shadow-purple-100 hover:bg-[#5e2faf] transition-all">
                    <i class="mr-2 fa-solid fa-floppy-disk"></i> Update Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const DEFAULT_PHOTO = "{{ asset('assets/images/photos/default-photo.svg') }}";

    document.getElementById('photoInput').onchange = function() {
        const [file] = this.files;
        if (file) {
            document.getElementById('photoPreview').src = URL.createObjectURL(file);
            document.getElementById('hapusFotoInput').value = '0';
            document.getElementById('infoHapusFoto').classList.add('hidden');
        }
    };

    function hapusFotoGuru() {
        if (!confirm('Hapus foto guru ini?')) return;
        document.getElementById('photoPreview').src = DEFAULT_PHOTO;
        document.getElementById('hapusFotoInput').value = '1';
        document.getElementById('photoInput').value = '';
        document.getElementById('infoHapusFoto').classList.remove('hidden');
        document.getElementById('btnHapusFoto').classList.add('hidden');
    }
</script>
@endsection
