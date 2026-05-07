<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
   public function index(Request $request)
    {
        // Ambil semua kelas untuk isi dropdown filter
        $classes = SchoolClass::all();

        // Query dasar dengan relasi
        $query = Student::with('schoolClass');

        // Filter jika ada input 'class_id'
        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('school_class_id', $request->class_id);
        }

        $students = $query->latest()->paginate(10)->withQueryString();

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'gender'          => 'required|in:L,P',
            'birth_place'     => 'required',
            'birth_date'      => 'required|date',
            'nik'             => 'required|unique:students,nik',
            'nisn'            => 'required|unique:students,nisn',
            'entry_year'      => 'required|digits:4',
            'school_class_id' => 'required|exists:school_classes,id',
            'rfid_uid'        => 'required|unique:students,rfid_uid',
            'photo'           => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        Student::create($data);

        return redirect()->route('student.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::all();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'gender'          => 'required|in:L,P',
            'birth_place'     => 'required',
            'birth_date'      => 'required|date',
            'nik'             => 'required|unique:students,nik,' . $student->id,
            'nisn'            => 'required|unique:students,nisn,' . $student->id,
            'entry_year'      => 'required|digits:4',
            'school_class_id' => 'required|exists:school_classes,id',
            'rfid_uid'        => 'required|unique:students,rfid_uid,' . $student->id,
            'photo'           => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada foto baru
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($data);

        return redirect()->route('student.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Student $student)
    {
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();
        return redirect()->route('student.index')->with('success', 'Data siswa berhasil dihapus!');
    }
}
