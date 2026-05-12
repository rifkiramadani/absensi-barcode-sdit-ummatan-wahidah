@extends('layouts.app')

@section('title', 'Tambah Guru')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl p-8 mx-auto bg-white border border-gray-100 shadow-sm rounded-2xl">

            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-50">
                <div class="p-2 bg-purple-50 rounded-lg text-[#773DCE]">
                    <i class="text-xl fa-solid fa-chalkboard-user"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Tambah Guru Baru</h2>
            </div>

            <form action="{{ route('teacher.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                            placeholder="Masukkan nama lengkap guru" required>
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">Jenis Kelamin</label>
                        <select name="gender"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">NIY <span
                                class="font-normal text-gray-400">(opsional)</span></label>
                        <input type="text" name="nip" value="{{ old('nip') }}"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                            placeholder="Kosongkan jika guru honorer">
                        @error('nip')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                            placeholder="Contoh: Guru Kelas 1A">
                        @error('jabatan')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">No. HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                            placeholder="08xxxxxxxxxx">
                        @error('no_hp')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2 p-5 border-2 border-purple-100 border-dashed rounded-2xl bg-purple-50/30">
                        <label class="block text-xs font-black tracking-widest text-[#773DCE] uppercase">RFID UID / Barcode
                            ID</label>
                        <div class="relative mt-2">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-purple-400">
                                <i class="fa-solid fa-rss"></i>
                            </span>
                            <input type="text" name="rfid_uid" value="{{ old('rfid_uid') }}"
                                placeholder="Scan kartu atau masukkan kode..."
                                class="block w-full pl-10 font-mono text-lg bg-white border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-200">
                        </div>
                        @error('rfid_uid')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                   <div class="col-span-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-bold text-gray-700">Foto Guru</label>
                            @error('photo')
                                <span class="text-xs font-bold text-red-500 animate-pulse">
                                    <i class="mr-1 fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="relative flex flex-col items-center justify-center px-6 pt-5 pb-6 mt-2 transition-all border-2 rounded-2xl group @error('photo') border-red-200 bg-red-50/30 @else border-gray-100 border-dashed hover:bg-gray-50 @enderror">

                            {{-- Preview Box --}}
                            <div id="previewContainer" class="relative hidden mb-4">
                                <div class="relative inline-block group/img">
                                    <img id="photoPreview" src="#"
                                        class="object-cover w-32 h-32 border-4 border-white shadow-md rounded-2xl ring-1 ring-gray-100 cursor-zoom-in hover:ring-2 hover:ring-[#773DCE] transition-all"
                                        onclick="bukaPreviewFoto(this.src, 'Preview Foto Guru')"
                                        title="Klik untuk preview">
                                    <div class="absolute inset-0 flex items-center justify-center transition-opacity opacity-0 pointer-events-none rounded-2xl bg-black/20 group-hover/img:opacity-100">
                                        <i class="text-lg text-white fa-solid fa-magnifying-glass-plus"></i>
                                    </div>
                                </div>
                                <button type="button" id="removePhoto"
                                    class="absolute flex items-center justify-center w-6 h-6 text-white transition-colors bg-red-500 rounded-full shadow-sm -top-2 -right-2 hover:bg-red-600">
                                    <i class="text-xs fa-solid fa-xmark"></i>
                                </button>
                            </div>

                            {{-- Icon & Text --}}
                            <div id="uploadPlaceholder" class="space-y-1 text-center">
                                <i class="mb-2 text-3xl transition-colors fa-solid fa-image @error('photo') text-red-300 @else text-gray-300 group-hover:text-purple-300 @enderror"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="photo"
                                        class="relative cursor-pointer bg-transparent rounded-md font-bold text-[#773DCE] hover:text-[#5e2faf]">
                                        <span>Upload file foto</span>
                                        <input id="photo" name="photo" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-400">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-10">
                    <a href="{{ route('teacher.index') }}"
                        class="px-6 py-3 text-sm font-bold text-gray-500 transition-all border border-gray-100 rounded-xl hover:bg-gray-50">Batal</a>
                    <button type="submit"
                        class="px-10 py-3 text-sm font-bold text-white bg-[#773DCE] rounded-xl shadow-lg shadow-purple-200 hover:bg-[#5e2faf] transition-all">
                        <i class="mr-2 fa-solid fa-floppy-disk"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
   <script>
    const photoInput       = document.getElementById('photo');
    const photoPreview     = document.getElementById('photoPreview');
    const previewContainer = document.getElementById('previewContainer');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const removeBtn        = document.getElementById('removePhoto');

    photoInput.onchange = evt => {
        const [file] = photoInput.files;
        if (file) {
            if (file.type.startsWith('image/')) {
                photoPreview.src = URL.createObjectURL(file);
                previewContainer.classList.remove('hidden');
                uploadPlaceholder.classList.add('opacity-40');
            } else {
                alert('Mohon unggah file gambar yang valid (JPG/PNG).');
                photoInput.value = '';
            }
        }
    };

    removeBtn.onclick = () => {
        photoInput.value = '';
        previewContainer.classList.add('hidden');
        uploadPlaceholder.classList.remove('opacity-40');
        photoPreview.src = '#';
    };
    </script>
@endsection
