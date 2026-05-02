<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'reg_grades';

    protected $fillable = [
        'enrollment_id', 'midterm_grade', 'final_grade', 'remarks'
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function getLetterGradeAttribute()
    {
        $grade = $this->final_grade;
        if (!$grade) return null;
        if ($grade >= 93) return 'A';
        if ($grade >= 90) return 'A-';
        if ($grade >= 87) return 'B+';
        if ($grade >= 83) return 'B';
        if ($grade >= 80) return 'B-';
        if ($grade >= 77) return 'C+';
        if ($grade >= 73) return 'C';
        if ($grade >= 70) return 'C-';
        if ($grade >= 67) return 'D+';
        if ($grade >= 63) return 'D';
        if ($grade >= 60) return 'D-';
        return 'F';
    }

    public function isPassed(): bool
    {
        return $this->final_grade >= 75;
    }
}