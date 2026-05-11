<?php

namespace App\Models;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    protected $fillable = [
        'teacher_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'keterangan',
        'catatan_keterangan',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
