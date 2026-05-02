<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Student;
use App\Models\Section;

class Attendance extends Model
{
    protected $table = 'reg_attendances';

    protected $fillable = [
        'student_id', 'section_id', 'date', 'status', 'remarks'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
