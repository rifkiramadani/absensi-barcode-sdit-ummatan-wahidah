<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentCaseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //ROUTE FOR PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ROUTE FOR STUDENTS
    Route::get('/student', [StudentController::class, 'index'])->name('student.index');
    Route::get('/student/create', [StudentController::class, 'create'])->name('student.create');
    Route::post('/student', [StudentController::class, 'store'])->name('student.store');
    Route::get('/student/{student}/edit', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('/student/{student}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('/student/{student}', [StudentController::class, 'destroy'])->name('student.destroy');

    // ROUTE FOR SCHOOL CLASSES
    Route::get('/schoolClass', [SchoolClassController::class, 'index'])->name('school_class.index');
    Route::get('/schoolClass/create', [SchoolClassController::class, 'create'])->name('school_class.create');
    Route::post('/schoolClass', [SchoolClassController::class, 'store'])->name('school_class.store');
    Route::get('/schoolClass/{schoolClass}/edit', [SchoolClassController::class, 'edit'])->name('school_class.edit');
    Route::put('/schoolClass/{schoolClass}', [SchoolClassController::class, 'update'])->name('school_class.update');
    Route::delete('schoolClass/{schoolClass}', [SchoolClassController::class, 'destroy'])->name('school_class.destroy');

    //ROUTE FOR TIME SETTINGS
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');

    // ROUTE FOR SCAN ABSENSI
    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
    Route::post('/scan/store', [ScanController::class, 'store'])->name('scan.store');

    // ROUTE FOR ATTENDANCE RECAP
    Route::get('/attendance/recap', [AttendanceController::class, 'recap'])->name('attendance.recap');
    Route::get('/attendance/export', [AttendanceController::class, 'exportExcel'])->name('attendance.export');
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');

    // ROUTE STUDENT CASES (BUKU KASUS)
    Route::get('/student-case', [StudentCaseController::class, 'index'])->name('student_case.index');
    Route::get('/student-case/create', [StudentCaseController::class, 'create'])->name('student_case.create');
    Route::post('/student-case', [StudentCaseController::class, 'store'])->name('student_case.store');
    Route::get('/student-case/{studentCase}/edit', [StudentCaseController::class, 'edit'])->name('student_case.edit');
    Route::put('/student-case/{studentCase}', [StudentCaseController::class, 'update'])->name('student_case.update');
    Route::delete('/student-case/{studentCase}', [StudentCaseController::class, 'destroy'])->name('student_case.destroy');

    // ROUTE TEACHERS (DATA GURU)
    Route::get('/teacher', [TeacherController::class, 'index'])->name('teacher.index');
    Route::get('/teacher/create', [TeacherController::class, 'create'])->name('teacher.create');
    Route::post('/teacher', [TeacherController::class, 'store'])->name('teacher.store');
    Route::get('/teacher/{teacher}/edit', [TeacherController::class, 'edit'])->name('teacher.edit');
    Route::put('/teacher/{teacher}', [TeacherController::class, 'update'])->name('teacher.update');
    Route::delete('/teacher/{teacher}', [TeacherController::class, 'destroy'])->name('teacher.destroy');

    // ROUTE TEACHER ATTENDANCES (ABSENSI GURU)
    Route::get('/teacher-attendance/recap', [TeacherAttendanceController::class, 'recap'])->name('teacher_attendance.recap');
    Route::post('/teacher-attendance/keterangan', [TeacherAttendanceController::class, 'storeKeterangan'])->name('teacher_attendance.keterangan');
    Route::delete('/teacher-attendance/{teacherAttendance}', [TeacherAttendanceController::class, 'destroy'])->name('teacher_attendance.destroy');
    Route::get('/teacher-attendance/export', [TeacherAttendanceController::class, 'exportExcel'])->name('teacher_attendance.export');

    // ROUTE KETERANGAN MANUAL SISWA
    Route::post('/attendance/keterangan', [AttendanceController::class, 'storeKeterangan'])->name('attendance.keterangan');
    });

// COLOR PALLET
// #773DCE UNGU
// #1DF01A HIJAU

require __DIR__.'/auth.php';
