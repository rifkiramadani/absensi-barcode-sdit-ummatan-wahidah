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
        // 1. Validasi Input
        $request->validate(['rfid_uid' => 'required']);
        $rfid = trim($request->rfid_uid);

        // 2. Cari Siswa berdasarkan RFID
        $student = Student::where('rfid_uid', $rfid)->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kartu tidak terdaftar! Hubungi admin.'
            ], 404);
        }

        $today = Carbon::today()->toDateString();
        $now = Carbon::now(); // Mengambil object Carbon utuh agar lebih fleksibel
        $currentTimeString = $now->toTimeString();

        // Generate Barcode HTML untuk tampilan di modal/view
        $barcodeHtml = \DNS2D::getBarcodeHTML($student->rfid_uid, 'QRCODE', 3, 3);

        // 3. Cek apakah sudah ada record absensi hari ini
        $attendance = Attendance::where('student_id', $student->id)
                                ->where('date', $today)
                                ->first();

        // --- LOGIKA ABSEN PULANG (Jika sudah ada absen masuk hari ini) ---
        if ($attendance) {
            if (is_null($attendance->check_out)) {
                $attendance->update([
                    'check_out' => $currentTimeString
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

            // Jika sudah absen masuk & sudah absen pulang
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

        // --- LOGIKA ABSEN MASUK (Jika belum ada record hari ini) ---

        // Ambil settingan waktu maksimal (max_time)
        $setting = Setting::first();

        /* PERBAIKAN:
           Menggunakan 'max_time' sesuai database.
           Jika data setting kosong, default ke 07:30:00
        */
        $jamMasukLimit = $setting ? $setting->max_time : '07:30:00';

        // Bandingkan waktu sekarang dengan batas waktu
        // Jika 16:14 <= 18:00, maka 'Hadir'
        $status = ($currentTimeString <= $jamMasukLimit) ? 'Hadir' : 'Telat';

        Attendance::create([
            'student_id' => $student->id,
            'date' => $today,
            'check_in' => $currentTimeString,
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
