<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentData extends Model
{
    protected $table = 'reg_enrollment_data';

    protected $fillable = [
        'student_id',
        'academic_year',
        'semester',
        'year_level',
        'status',
        'max_units',
        'check_prerequisites',
        'payment_plan',
        'course_code',
        'curriculum',
        'section_no',
        'section_name',
        'dept',
        'tf_level',
        'late_enrollee_days',
        'check_enrollment_count',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
