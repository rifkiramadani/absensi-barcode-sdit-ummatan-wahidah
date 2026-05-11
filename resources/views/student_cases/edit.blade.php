@extends('layouts.app')

@section('title', 'Edit Catatan Kasus')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl p-8 mx-auto bg-white border border-gray-100 shadow-sm rounded-2xl">

            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-50">
                <div class="p-2 text-red-500 rounded-lg bg-red-50">
                    <i class="text-xl fa-solid fa-pen-to-square"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Edit Catatan Kasus</h2>
            </div>

            <form action="{{ route('student_case.update', $studentCase->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="flex flex-col gap-6">

                    <div>
                        <label class="block text-sm font-bold text-gray-700">Nama Siswa</label>
                        <select name="student_id"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                            required>
                            @foreach ($students as $s)
                                <option value="{{ $s->id }}"
                                    {{ old('student_id', $studentCase->student_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} - {{ $s->schoolClass->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Tanggal Kejadian</label>
                            <input type="date" name="tanggal_kejadian"
                                value="{{ old('tanggal_kejadian', $studentCase->tanggal_kejadian) }}"
                                class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700">Kategori</label>
                            <select name="kategori"
                                class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">
                                <option value="Pelanggaran"
                                    {{ old('kategori', $studentCase->kategori) == 'Pelanggaran' ? 'selected' : '' }}>
                                    Pelanggaran</option>
                                <option value="Prestasi"
                                    {{ old('kategori', $studentCase->kategori) == 'Prestasi' ? 'selected' : '' }}>
                                    Prestasi</option>
                                <option value="Lainnya"
                                    {{ old('kategori', $studentCase->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">Judul Kasus</label>
                        <input type="text" name="judul" value="{{ old('judul', $studentCase->judul) }}"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">Deskripsi Lengkap</label>
                        <textarea name="deskripsi" rows="4"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm"
                            required>{{ old('deskripsi', $studentCase->deskripsi) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">Tindak Lanjut</label>
                        <textarea name="tindak_lanjut" rows="3"
                            class="block w-full mt-1 border-gray-200 rounded-xl shadow-sm focus:border-[#773DCE] focus:ring focus:ring-purple-100 sm:text-sm">{{ old('tindak_lanjut', $studentCase->tindak_lanjut) }}</textarea>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-10">
                    <a href="{{ route('student_case.index') }}"
                        class="px-6 py-3 text-sm font-bold text-gray-500 transition-all border border-gray-100 rounded-xl hover:bg-gray-50">Batal</a>
                    <button type="submit"
                        class="px-10 py-3 text-sm font-bold text-white bg-[#773DCE] rounded-xl shadow-lg shadow-purple-200 hover:bg-[#5e2faf] transition-all">
                        <i class="mr-2 fa-solid fa-floppy-disk"></i> Update Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
