<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolClassController;
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
});

require __DIR__.'/auth.php';
