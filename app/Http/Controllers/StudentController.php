<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('schoolClass')->latest()->paginate(10);
        return view('students.index', compact('students'));
    }

     public function create()
    {
        $classes = SchoolClass::all();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'gender' => 'required',
            'birth_place' => 'required',
            'birth_date' => 'required|date',
            'nik' => 'required|unique:students',
            'entry_year' => 'required',
            'school_class_id' => 'required',
            'rfid_uid' => 'required|unique:students',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::all();
        return view('students.edit', compact('student','classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required',
            'gender' => 'required',
            'birth_place' => 'required',
            'birth_date' => 'required|date',
            'nik' => 'required|unique:students,nik,'.$student->id,
            'entry_year' => 'required',
            'school_class_id' => 'required',
            'rfid_uid' => 'required|unique:students,rfid_uid,'.$student->id,
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return back()->with('success', 'Data dihapus');
    }
}
