<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tanggalSelected = $request->get('tanggal', date('Y-m-d'));

        // ===== DATA SISWA =====
        $totalSiswa  = Student::count();
        $totalKelas  = SchoolClass::count();

        $absensiSiswa = Attendance::whereDate('date', $tanggalSelected)->get();
        $totalAbsensiSiswa = $absensiSiswa->count();

        $chartSiswa = [
            'hadir'  => $absensiSiswa->where('status', 'Hadir')->whereNull('check_out')->count(),
            'telat'  => $absensiSiswa->where('status', 'Telat')->whereNull('check_out')->count(),
            'pulang' => $absensiSiswa->whereNotNull('check_out')->count(),
            'izin'   => $absensiSiswa->where('keterangan', 'Izin')->count(),
            'sakit'  => $absensiSiswa->where('keterangan', 'Sakit')->count(),
            'alpa'   => $absensiSiswa->where('keterangan', 'Alpa')->count(),
        ];

        // ===== DATA GURU =====
        $totalGuru = Teacher::where('is_active', true)->count();

        $absensiGuru = TeacherAttendance::whereDate('date', $tanggalSelected)->get();
        $totalAbsensiGuru = $absensiGuru->count();

        $chartGuru = [
            'hadir'  => $absensiGuru->where('status', 'Hadir')->whereNull('check_out')->count(),
            'telat'  => $absensiGuru->where('status', 'Telat')->whereNull('check_out')->count(),
            'pulang' => $absensiGuru->whereNotNull('check_out')->count(),
            'izin'   => $absensiGuru->where('keterangan', 'Izin')->count(),
            'sakit'  => $absensiGuru->where('keterangan', 'Sakit')->count(),
            'alpa'   => $absensiGuru->where('keterangan', 'Alpa')->count(),
        ];

        return view('dashboard', compact(
            'totalSiswa', 'totalKelas', 'totalGuru',
            'totalAbsensiSiswa', 'totalAbsensiGuru',
            'chartSiswa', 'chartGuru',
            'tanggalSelected'
        ));
    }
}
