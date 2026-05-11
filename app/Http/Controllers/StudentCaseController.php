<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentCase;
use Illuminate\Http\Request;

class StudentCaseController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('search');
        $kategori = $request->get('kategori');
        $classId  = $request->get('school_class_id');
        $classes  = \App\Models\SchoolClass::all();

        $query = StudentCase::with('student.schoolClass')->latest('tanggal_kejadian');

        if ($search) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('nisn', 'LIKE', "%{$search}%");
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($classId) {
            $query->whereHas('student', fn($q) => $q->where('school_class_id', $classId));
        }

        $cases = $query->paginate(10)->withQueryString();

        return view('student_cases.index', compact('cases', 'search', 'kategori', 'classes', 'classId'));
    }

    public function create()
    {
        $students = Student::with('schoolClass')->orderBy('name')->get();
        return view('student_cases.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id'       => 'required|exists:students,id',
            'tanggal_kejadian' => 'required|date',
            'kategori'         => 'required|in:Pelanggaran,Prestasi,Lainnya',
            'judul'            => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'tindak_lanjut'    => 'nullable|string',
        ]);

        StudentCase::create([
            ...$request->all(),
            'dicatat_oleh' => auth()->user()->name,
        ]);

        return redirect()->route('student_case.index')
            ->with('success', 'Catatan kasus berhasil ditambahkan!');
    }

    public function edit(StudentCase $studentCase)
    {
        $students = Student::with('schoolClass')->orderBy('name')->get();
        return view('student_cases.edit', compact('studentCase', 'students'));
    }

    public function update(Request $request, StudentCase $studentCase)
    {
        $request->validate([
            'student_id'       => 'required|exists:students,id',
            'tanggal_kejadian' => 'required|date',
            'kategori'         => 'required|in:Pelanggaran,Prestasi,Lainnya',
            'judul'            => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'tindak_lanjut'    => 'nullable|string',
        ]);

        $studentCase->update($request->all());

        return redirect()->route('student_case.index')
            ->with('success', 'Catatan kasus berhasil diperbarui!');
    }

    public function destroy(StudentCase $studentCase)
    {
        $studentCase->delete();
        return back()->with('success', 'Catatan kasus berhasil dihapus.');
    }
}
