<?php

namespace App\Models;

use App\Models\Attendance;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name'
    ];

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }
}
