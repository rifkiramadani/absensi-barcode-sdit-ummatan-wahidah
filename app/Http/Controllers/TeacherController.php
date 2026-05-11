<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query  = Teacher::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('nip', 'LIKE', "%{$search}%")
                  ->orWhere('jabatan', 'LIKE', "%{$search}%");
            });
        }

        $teachers = $query->latest()->paginate(10)->withQueryString();

        return view('teachers.index', compact('teachers', 'search'));
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'gender'   => 'required|in:L,P',
            'nip'      => 'nullable|unique:teachers,nip',
            'jabatan'  => 'nullable|string|max:255',
            'no_hp'    => 'nullable|string|max:20',
            'rfid_uid' => [
                'required',
                'unique:teachers,rfid_uid',
                'unique:students,rfid_uid', // PENTING: cek ke tabel siswa
            ],
            'photo'    => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ], [
            'rfid_uid.unique' => 'RFID ini sudah digunakan oleh siswa atau guru lain!',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        Teacher::create($data);

        return redirect()->route('teacher.index')
            ->with('success', 'Data guru berhasil ditambahkan!');
    }

    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'rfid_uid' => [
                'required',
                Rule::unique('teachers', 'rfid_uid')->ignore($teacher->id),
                'unique:students,rfid_uid', // cek ke tabel siswa
            ],
        ], [
            'rfid_uid.unique' => 'RFID ini sudah digunakan oleh siswa atau guru lain!',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update($data);

        return redirect()->route('teacher.index')
            ->with('success', 'Data guru berhasil diperbarui!');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }
        $teacher->delete();
        return back()->with('success', 'Data guru berhasil dihapus!');
    }
}
