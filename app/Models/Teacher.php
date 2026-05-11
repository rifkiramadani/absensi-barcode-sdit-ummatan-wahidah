<?php

namespace App\Models;

use App\Models\TeacherAttendance;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'name',
        'gender',
        'nip',
        'jabatan',
        'no_hp',
        'photo',
        'rfid_uid',
        'is_active',
    ];

    public function attendances()
    {
        return $this->hasMany(TeacherAttendance::class);
    }
}
