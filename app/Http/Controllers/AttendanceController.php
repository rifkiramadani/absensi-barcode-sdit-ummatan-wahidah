<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Exports\AttendanceExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function recap(Request $request)
    {
        $filter     = $request->get('filter', 'daily');
        $classId    = $request->get('school_class_id');
        $startDate  = $request->get('start_date');
        $endDate    = $request->get('end_date');
        $search     = $request->get('search');
        $keterangan = $request->get('keterangan');
        $classes    = SchoolClass::all();

        $query = Attendance::with(['student.schoolClass'])->latest('date');

        if ($search) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('nisn', 'LIKE', "%{$search}%");
            });
        }

        if ($classId) {
            $query->whereHas('student', fn($q) => $q->where('school_class_id', $classId));
        }

        if ($keterangan) {
            if ($keterangan === 'Tidak Hadir') {
                $query->whereNotNull('keterangan');
            } elseif ($keterangan === 'Hadir') {
                $query->whereIn('status', ['Hadir', 'Telat'])->whereNull('keterangan');
            } else {
                $query->where('keterangan', $keterangan);
            }
        }

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } else {
            $date = Carbon::today();
            switch ($filter) {
                case 'weekly':
                    $query->whereBetween('date', [
                        $date->startOfWeek()->toImmutable(),
                        $date->endOfWeek()->toImmutable()
                    ]);
                    break;
                case 'monthly':
                    $query->whereMonth('date', $date->month)->whereYear('date', $date->year);
                    break;
                case 'yearly':
                    $query->whereYear('date', $date->year);
                    break;
                default:
                    // Hanya batasi ke hari ini jika tidak ada filter apapun
                    if (!$search && !$keterangan && !$classId) {
                        $query->whereDate('date', Carbon::today());
                    }
                    break;
            }
        }

        $attendances = $query->paginate(10)->withQueryString();

        $studentList = Student::with('schoolClass')
                        ->orderBy('name')
                        ->get()
                        ->map(fn($s) => [
                            'id'    => $s->id,
                            'name'  => $s->name,
                            'nisn'  => $s->nisn,
                            'kelas' => $s->schoolClass->name,
                        ])
                        ->values();

        return view('attendances.recap', compact(
            'attendances', 'filter', 'classes', 'classId',
            'startDate', 'endDate', 'search', 'studentList', 'keterangan'
        ));
    }

    public function storeKeterangan(Request $request)
    {
        $request->validate([
            'student_id'         => 'required|exists:students,id',
            'date'               => 'required|date',
            'keterangan'         => 'required|in:Izin,Sakit,Alpa',
            'catatan_keterangan' => 'nullable|string|max:255',
        ]);

        $existing = Attendance::where('student_id', $request->student_id)
                               ->where('date', $request->date)
                               ->first();

        if ($existing) {
            $existing->update([
                'keterangan'         => $request->keterangan,
                'catatan_keterangan' => $request->catatan_keterangan,
            ]);
        } else {
            Attendance::create([
                'student_id'         => $request->student_id,
                'date'               => $request->date,
                'keterangan'         => $request->keterangan,
                'catatan_keterangan' => $request->catatan_keterangan,
            ]);
        }

        // Redirect ke filter monthly agar data langsung terlihat
        return redirect()->route('attendance.recap', ['filter' => 'monthly'])
            ->with('success', 'Keterangan siswa berhasil disimpan!');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Data absensi berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $filter    = $request->get('filter');
        $classId   = $request->get('school_class_id');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        $fileName = 'rekap_absensi_siswa_' . now()->format('Y-m-d_His') . '.xlsx';
        return Excel::download(new AttendanceExport($filter, $classId, $startDate, $endDate), $fileName);
    }
}
