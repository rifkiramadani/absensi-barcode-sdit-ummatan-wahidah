<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
});

require __DIR__.'/auth.php';
