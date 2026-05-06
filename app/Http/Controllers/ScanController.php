<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Setting;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        return view('scans.index');
    }

  public function store(Request $request)
    {
        $request->validate(['rfid_uid' => 'required']);
        $rfid = trim($request->rfid_uid);

        // 1. Cari Siswa
        $student = Student::where('rfid_uid', $rfid)->first();

        if (!$student) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan!'], 404);
        }

        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();

        // Generate Barcode HTML (Reuseable untuk semua kondisi response)
        $barcodeHtml = \DNS2D::getBarcodeHTML($student->rfid_uid, 'QRCODE', 3, 3);

        // 2. Cek apakah sudah ada record absensi hari ini
        $attendance = Attendance::where('student_id', $student->id)
                                ->where('date', $today)
                                ->first();

        // LOGIKA ABSEN PULANG (CHECK OUT)
        if ($attendance) {
            if (is_null($attendance->check_out)) {
                $attendance->update([
                    'check_out' => $now
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Absen Pulang Berhasil: ' . $student->name,
                    'student_name' => $student->name,
                    'student_photo' => $student->photo ? asset('storage/' . $student->photo) : asset('assets/images/photos/default-photo.svg'),
                    'attendance_status' => 'Pulang',
                    'barcode' => $student->rfid_uid,
                    'barcode_html' => $barcodeHtml
                ]);
            }

            return response()->json([
                'status' => 'info',
                'message' => $student->name . ' sudah menyelesaikan absensi hari ini.',
                'student_name' => $student->name,
                'student_photo' => $student->photo ? asset('storage/' . $student->photo) : asset('assets/images/photos/default-photo.svg'),
                'attendance_status' => 'Selesai',
                'barcode' => $student->rfid_uid,
                'barcode_html' => $barcodeHtml
            ]);
        }

        // LOGIKA ABSEN MASUK (CHECK IN)
        $setting = Setting::first();
        $jamMasukLimit = $setting ? $setting->start_time : '07:30:00';
        $status = ($now <= $jamMasukLimit) ? 'Hadir' : 'Telat';

        Attendance::create([
            'student_id' => $student->id,
            'date' => $today,
            'check_in' => $now,
            'status' => $status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absen Masuk Berhasil: ' . $student->name,
            'student_name' => $student->name,
            'student_photo' => $student->photo ? asset('storage/' . $student->photo) : asset('assets/images/photos/default-photo.svg'),
            'attendance_status' => $status,
            'barcode' => $student->rfid_uid,
            'barcode_html' => $barcodeHtml
        ]);
    }
}
