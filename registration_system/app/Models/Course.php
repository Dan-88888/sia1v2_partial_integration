<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'reg_courses';

    protected $fillable = [
        'course_name', 'course_code', 'description', 'department', 'campus'
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}