<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class StudentCase extends Model
{
    protected $fillable = [
        'student_id',
        'tanggal_kejadian',
        'kategori',
        'judul',
        'deskripsi',
        'tindak_lanjut',
        'dicatat_oleh',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
