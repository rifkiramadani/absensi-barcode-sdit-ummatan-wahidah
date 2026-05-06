<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function recap(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        $classId = $request->get('school_class_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $classes = \App\Models\SchoolClass::all();
        $query = Attendance::with(['student.schoolClass'])->latest('date');

        // Filter Kelas
        if ($classId) {
            $query->whereHas('student', fn($q) => $q->where('school_class_id', $classId));
        }

        // Filter Berdasarkan Input Tanggal Manual
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } else {
            $date = Carbon::today();
            switch ($filter) {
                case 'weekly': $query->whereBetween('date', [$date->startOfWeek()->toImmutable(), $date->endOfWeek()->toImmutable()]); break;
                case 'monthly': $query->whereMonth('date', $date->month)->whereYear('date', $date->year); break;
                case 'quarterly': $query->whereBetween('date', [$date->startOfQuarter(), $date->endOfQuarter()]); break;
                case 'yearly': $query->whereYear('date', $date->year); break;
                default: $query->whereDate('date', Carbon::today()); break;
            }
        }

        // Pagination: 10 data per halaman, tetap membawa parameter filter (queryString)
        $attendances = $query->paginate(10)->withQueryString();

        return view('attendances.recap', compact('attendances', 'filter', 'classes', 'classId', 'startDate', 'endDate'));
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return back()->with('success', 'Data absensi berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->get('filter');
        $classId = $request->get('school_class_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $fileName = 'rekap_absensi_' . now()->format('Y-m-d_His') . '.xlsx';
        return Excel::download(new AttendanceExport($filter, $classId, $startDate, $endDate), $fileName);
    }
}
