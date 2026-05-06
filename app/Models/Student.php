<?php

namespace App\Models;

use App\Models\Attendance;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'nik',
        'nisn',
        'entry_year',
        'photo',
        'school_class_id',
        'rfid_uid',
    ];

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }
}
