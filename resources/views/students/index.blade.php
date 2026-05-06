@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

    {{-- <a href="{{ route('students.create') }}" class="inline-block px-4 py-2 mb-4 text-white bg-blue-600 rounded-lg">
        + Tambah Siswa
    </a> --}}

    <div class="overflow-x-auto bg-white shadow rounded-xl">

        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Foto</th>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Kelas</th>
                    <th class="p-3">Gender</th>
                    <th class="p-3">RFID</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($students as $s)
                    <tr class="border-b">
                        <td class="p-3">
                            @if ($s->photo)
                                <img src="{{ asset('storage/' . $s->photo) }}" class="w-10 h-10 rounded-full">
                            @else
                                -
                            @endif
                        </td>

                        <td class="p-3">{{ $s->name }}</td>
                        <td class="p-3">{{ $s->schoolClass->name }}</td>
                        <td class="p-3">{{ $s->gender }}</td>
                        <td class="p-3">{{ $s->rfid_uid }}</td>

                        <td class="flex gap-2 p-3">
                            {{-- <a href="{{ route('students.edit', $s->id) }}" class="text-blue-500">Edit</a>

                            <form action="{{ route('students.destroy', $s->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="text-red-500">Hapus</button>
                            </form> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <div class="mt-4">
        {{ $students->links() }}
    </div>

@endsection
