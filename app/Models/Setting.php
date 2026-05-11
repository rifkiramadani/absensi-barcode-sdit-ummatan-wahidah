<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    protected $fillable = [
        'max_time',
        'teacher_max_time', // BARU;
        ];
}
