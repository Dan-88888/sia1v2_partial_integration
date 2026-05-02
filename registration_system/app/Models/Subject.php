<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'reg_subjects';

    protected $fillable = [
        'course_id', 'subject_code', 'subject_name', 'units', 'description'
    ];

    protected $casts = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function prerequisites()
    {
        return $this->belongsToMany(Subject::class, 'reg_subject_prerequisites', 'subject_id', 'prerequisite_id')
                    ->withTimestamps();
    }

    public function prerequisite_of()
    {
        return $this->belongsToMany(Subject::class, 'reg_subject_prerequisites', 'prerequisite_id', 'subject_id')
                    ->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
                    ->withPivot('semester', 'school_year', 'status')
                    ->withTimestamps();
    }

    public function getCurrentEnrollmentCount()
    {
        return $this->enrollments()
                    ->where('status', 'enrolled')
                    ->count();
    }

    public function hasAvailableSlots()
    {
        return $this->getCurrentEnrollmentCount() < $this->capacity;
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function grades()
    {
        return $this->hasManyThrough(Grade::class, Enrollment::class);
    }
}