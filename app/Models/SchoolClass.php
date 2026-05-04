<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{

    protected $fillable = [
        'name',
        'school_class_id',
        'rfid_uid'
    ];

    public function students() {
        return $this->hasMany(Student::class);
    }
}
