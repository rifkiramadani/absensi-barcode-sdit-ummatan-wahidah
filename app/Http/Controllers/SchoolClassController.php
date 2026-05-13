<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $classes = SchoolClass::withCount('students')
            ->when($search, fn($q) =>
                $q->where('name', 'LIKE', "%{$search}%")
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('schoolClasses.index', compact('classes', 'search'));
    }

    public function create()
    {
        return view('schoolClasses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:school_classes,name',
        ]);

        SchoolClass::create($request->all());

        return redirect()->route('school_class.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit(SchoolClass $schoolClass)
    {
        return view('schoolClasses.edit', compact('schoolClass'));
    }

    public function update(Request $request, SchoolClass $schoolClass)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:school_classes,name,' . $schoolClass->id,
        ]);

        $schoolClass->update($request->all());

        return redirect()->route('school_class.index')
            ->with('success', 'Nama kelas berhasil diperbarui!');
    }

    public function destroy(SchoolClass $schoolClass)
    {
        if ($schoolClass->students()->count() > 0) {
            return back()->with('error', 'Kelas tidak bisa dihapus karena masih memiliki siswa!');
        }

        $schoolClass->delete();
        return redirect()->route('school_class.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
}
