<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{

    protected $fillable = [
        'student_id',
        'date',      // Tambahkan ini
        'check_in',  // Tambahkan ini
        'check_out', // Tambahkan ini
        'status',
        'keterangan',        // BARU
        'catatan_keterangan', // BARU
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
