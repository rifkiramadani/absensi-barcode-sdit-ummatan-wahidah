<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index(Request $request)
    {
        // Tetap hitung total keseluruhan untuk Card di atas
        $totalSiswa = Student::count();
        $totalKelas = SchoolClass::count();

        // Ambil tanggal dari filter, default hari ini
        $tanggalSelected = $request->get('tanggal', date('Y-m-d'));

        // Query data absensi berdasarkan tanggal terpilih
        $absensiData = Attendance::whereDate('date', $tanggalSelected)->get();

        // Total rekap dihitung berdasarkan hasil filter tanggal
        $totalAbsensi = $absensiData->count();

        $chartData = [
            // Logika: Jika status Hadir & belum check_out
            'hadir'   => $absensiData->where('status', 'Hadir')->whereNull('check_out')->count(),
            // Logika: Jika status Telat & belum check_out
            'telat'   => $absensiData->where('status', 'Telat')->whereNull('check_out')->count(),
            // Logika: Jika sudah ada jam check_out (apapun status awalnya)
            'pulang'  => $absensiData->whereNotNull('check_out')->count(),
            // Logika: Status khusus Selesai
            'selesai' => $absensiData->where('status', 'Selesai')->count(),
        ];

        return view('dashboard', compact('totalSiswa', 'totalKelas', 'totalAbsensi', 'chartData', 'tanggalSelected'));
    }
}
