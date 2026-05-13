<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentCase;
use App\Exports\StudentCaseExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentCaseController extends Controller
{
    // Daftar kategori terpusat — mudah diubah di satu tempat
    const KATEGORI = [
        'Pelanggaran'           => ['warna' => 'red',    'icon' => 'fa-triangle-exclamation'],
        'Prestasi Akademik'     => ['warna' => 'green',  'icon' => 'fa-award'],
        'Prestasi Non-Akademik' => ['warna' => 'blue',   'icon' => 'fa-trophy'],
        'Perilaku Baik'         => ['warna' => 'purple', 'icon' => 'fa-heart'],
        'Catatan Umum'          => ['warna' => 'gray',   'icon' => 'fa-note-sticky'],
    ];

    public function index(Request $request)
    {
        $search    = $request->get('search');
        $kategori  = $request->get('kategori');
        $classId   = $request->get('school_class_id');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');
        $classes   = \App\Models\SchoolClass::all();

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

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_kejadian', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('tanggal_kejadian', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('tanggal_kejadian', '<=', $endDate);
        }

        $cases        = $query->paginate(10)->withQueryString();
        $kategoriList = self::KATEGORI;

        return view('student_cases.index', compact(
            'cases', 'search', 'kategori', 'classes', 'classId',
            'kategoriList', 'startDate', 'endDate'
        ));
    }

    public function export(Request $request)
    {
        $search    = $request->get('search');
        $kategori  = $request->get('kategori');
        $classId   = $request->get('school_class_id');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        $fileName = 'buku_catatan_siswa_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new StudentCaseExport($search, $kategori, $classId, $startDate, $endDate),
            $fileName
        );
    }

    public function create()
    {
        $students = Student::with('schoolClass')->orderBy('name')->get();

        $studentList = $students->map(fn($s) => [
            'id'    => $s->id,
            'name'  => $s->name,
            'nisn'  => $s->nisn,
            'class' => $s->schoolClass->name,
        ])->values();

        $kategoriList = self::KATEGORI;

        return view('student_cases.create', compact('students', 'studentList', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id'       => 'required|exists:students,id',
            'tanggal_kejadian' => 'required|date',
            'kategori'         => 'required|in:' . implode(',', array_keys(self::KATEGORI)),
            'judul'            => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'tindak_lanjut'    => 'nullable|string',
        ]);

        StudentCase::create([
            ...$request->all(),
            'dicatat_oleh' => auth()->user()->name,
        ]);

        return redirect()->route('student_case.index')
            ->with('success', 'Catatan berhasil ditambahkan!');
    }

    public function edit(StudentCase $studentCase)
    {
        $students = Student::with('schoolClass')->orderBy('name')->get();

        $studentList = $students->map(fn($s) => [
            'id'    => $s->id,
            'name'  => $s->name,
            'nisn'  => $s->nisn,
            'class' => $s->schoolClass->name,
        ])->values();

        $kategoriList = self::KATEGORI;

        return view('student_cases.edit', compact('studentCase', 'students', 'studentList', 'kategoriList'));
    }

    public function update(Request $request, StudentCase $studentCase)
    {
        $request->validate([
            'student_id'       => 'required|exists:students,id',
            'tanggal_kejadian' => 'required|date',
            'kategori'         => 'required|in:' . implode(',', array_keys(self::KATEGORI)),
            'judul'            => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'tindak_lanjut'    => 'nullable|string',
        ]);

        $studentCase->update($request->all());

        return redirect()->route('student_case.index')
            ->with('success', 'Catatan berhasil diperbarui!');
    }

    public function destroy(StudentCase $studentCase)
    {
        $studentCase->delete();
        return back()->with('success', 'Catatan berhasil dihapus.');
    }
}
