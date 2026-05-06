<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function recap(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        $date = Carbon::today();

        // Query dasar dengan eager loading
        $query = Attendance::with(['student.schoolClass']);

        // Logika Filter & Validasi Waktu
        switch ($filter) {
            case 'weekly':
                $query->whereBetween('date', [$date->startOfWeek()->format('Y-m-d'), $date->endOfWeek()->format('Y-m-d')]);
                break;
            case 'monthly':
                $query->whereMonth('date', $date->month)->whereYear('date', $date->year);
                break;
            case 'quarterly': // Triwulan
                $query->whereBetween('date', [$date->startOfQuarter()->format('Y-m-d'), $date->endOfQuarter()->format('Y-m-d')]);
                break;
            case 'yearly':
                $query->whereYear('date', $date->year);
                break;
            default: // Daily
                $query->whereDate('date', Carbon::today());
                break;
        }

        $attendances = $query->get();

        return view('attendances.recap', compact('attendances', 'filter'));
    }

    public function exportExcel(Request $request)
    {
        // Untuk Export Excel, Anda bisa menggunakan library Laravel Excel (Maatwebsite)
        // Namun sebagai alternatif cepat, Anda bisa menggunakan format HTML Table yang terbaca Excel
        $filter = $request->filter;
        $attendances = Attendance::with('student.schoolClass')->get(); // Sesuaikan query dengan filter

        if($attendances->isEmpty()){
            return back()->with('error', 'Data belum tersedia untuk periode ini.');
        }

        $fileName = "rekap-absensi-{$filter}-" . date('Y-m-d') . ".xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$fileName\"");

        return view('attendances.export_excel', compact('attendances'));
    }
}
