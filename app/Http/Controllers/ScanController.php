<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
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

        // ==============================
        // STEP 1: Cek apakah kartu milik SISWA
        // ==============================
        $student = Student::where('rfid_uid', $rfid)->first();
        if ($student) {
            return $this->processStudentAttendance($student);
        }

        // ==============================
        // STEP 2: Cek apakah kartu milik GURU
        // ==============================
        $teacher = Teacher::where('rfid_uid', $rfid)->where('is_active', true)->first();
        if ($teacher) {
            return $this->processTeacherAttendance($teacher);
        }

        // ==============================
        // STEP 3: Kartu tidak ditemukan
        // ==============================
        return response()->json([
            'status'  => 'error',
            'message' => 'Kartu tidak terdaftar! Hubungi admin.',
        ], 404);
    }

    // ==============================
    // PROSES ABSENSI SISWA
    // ==============================
    private function processStudentAttendance(Student $student)
    {
        $today             = Carbon::today()->toDateString();
        $now               = Carbon::now();
        $currentTimeString = $now->toTimeString();
        $barcodeHtml       = \DNS2D::getBarcodeHTML($student->rfid_uid, 'QRCODE', 3, 3);

        $attendance = Attendance::where('student_id', $student->id)
                                ->where('date', $today)
                                ->first();

        // Sudah ada record hari ini → proses absen pulang
        if ($attendance) {
            if (is_null($attendance->check_out)) {
                $attendance->update(['check_out' => $currentTimeString]);

                return response()->json([
                    'status'            => 'success',
                    'type'              => 'siswa',
                    'message'           => 'Absen Pulang Berhasil: ' . $student->name,
                    'student_name'      => $student->name,
                    'student_photo'     => $student->photo ? asset('storage/' . $student->photo) : asset('assets/images/photos/default-photo.svg'),
                    'attendance_status' => 'Pulang',
                    'barcode'           => $student->rfid_uid,
                    'barcode_html'      => $barcodeHtml,
                ]);
            }

            return response()->json([
                'status'            => 'info',
                'type'              => 'siswa',
                'message'           => $student->name . ' sudah menyelesaikan absensi hari ini.',
                'student_name'      => $student->name,
                'student_photo'     => $student->photo ? asset('storage/' . $student->photo) : asset('assets/images/photos/default-photo.svg'),
                'attendance_status' => 'Selesai',
                'barcode'           => $student->rfid_uid,
                'barcode_html'      => $barcodeHtml,
            ]);
        }

        // Belum ada record → proses absen masuk
        $setting  = Setting::first();
        $maxTime  = $setting ? $setting->max_time : '07:00:00';
        $status   = ($currentTimeString <= $maxTime) ? 'Hadir' : 'Telat';

        Attendance::create([
            'student_id' => $student->id,
            'date'       => $today,
            'check_in'   => $currentTimeString,
            'status'     => $status,
        ]);

        return response()->json([
            'status'            => 'success',
            'type'              => 'siswa',
            'message'           => 'Absen Masuk Berhasil: ' . $student->name,
            'student_name'      => $student->name,
            'student_photo'     => $student->photo ? asset('storage/' . $student->photo) : asset('assets/images/photos/default-photo.svg'),
            'attendance_status' => $status,
            'barcode'           => $student->rfid_uid,
            'barcode_html'      => $barcodeHtml,
        ]);
    }

    // ==============================
    // PROSES ABSENSI GURU
    // ==============================
    private function processTeacherAttendance(Teacher $teacher)
    {
        $today             = Carbon::today()->toDateString();
        $now               = Carbon::now();
        $currentTimeString = $now->toTimeString();
        $barcodeHtml       = \DNS2D::getBarcodeHTML($teacher->rfid_uid, 'QRCODE', 3, 3);

        $attendance = TeacherAttendance::where('teacher_id', $teacher->id)
                                       ->where('date', $today)
                                       ->first();

        // Sudah ada record hari ini → proses absen pulang
        if ($attendance) {
            if (is_null($attendance->check_out)) {
                $attendance->update(['check_out' => $currentTimeString]);

                return response()->json([
                    'status'            => 'success',
                    'type'              => 'guru',
                    'message'           => 'Absen Pulang Berhasil: ' . $teacher->name,
                    'student_name'      => $teacher->name . ' (Guru)',
                    'student_photo'     => $teacher->photo ? asset('storage/' . $teacher->photo) : asset('assets/images/photos/default-photo.svg'),
                    'attendance_status' => 'Pulang',
                    'barcode'           => $teacher->rfid_uid,
                    'barcode_html'      => $barcodeHtml,
                ]);
            }

            return response()->json([
                'status'            => 'info',
                'type'              => 'guru',
                'message'           => $teacher->name . ' sudah menyelesaikan absensi hari ini.',
                'student_name'      => $teacher->name . ' (Guru)',
                'student_photo'     => $teacher->photo ? asset('storage/' . $teacher->photo) : asset('assets/images/photos/default-photo.svg'),
                'attendance_status' => 'Selesai',
                'barcode'           => $teacher->rfid_uid,
                'barcode_html'      => $barcodeHtml,
            ]);
        }

        // Belum ada record → proses absen masuk guru
        $setting         = Setting::first();
        $teacherMaxTime  = $setting ? $setting->teacher_max_time : '07:15:00';
        $status          = ($currentTimeString <= $teacherMaxTime) ? 'Hadir' : 'Telat';

        TeacherAttendance::create([
            'teacher_id' => $teacher->id,
            'date'       => $today,
            'check_in'   => $currentTimeString,
            'status'     => $status,
        ]);

        return response()->json([
            'status'            => 'success',
            'type'              => 'guru',
            'message'           => 'Absen Masuk Berhasil: ' . $teacher->name,
            'student_name'      => $teacher->name . ' (Guru)',
            'student_photo'     => $teacher->photo ? asset('storage/' . $teacher->photo) : asset('assets/images/photos/default-photo.svg'),
            'attendance_status' => $status,
            'barcode'           => $teacher->rfid_uid,
            'barcode_html'      => $barcodeHtml,
        ]);
    }
}
