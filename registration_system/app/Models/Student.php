<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'reg_students';

    protected $fillable = [
        'user_id', 'student_number', 'campus', 'college', 'course', 'year_level',
        'admission_status', 'admission_date', 'admission_reference'
    ];

    protected $casts = [
        'admission_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function latestEnrollmentData()
    {
        return $this->hasOne(EnrollmentData::class)->latest('id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'reg_enrollments', 'student_id', 'section_id')
                    ->join('reg_sections', 'reg_enrollments.section_id', '=', 'reg_sections.id')
                    ->join('reg_subjects', 'reg_sections.subject_id', '=', 'reg_subjects.id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function totalUnits(): int
    {
        return $this->enrollments()
            ->where('status', 'enrolled')
            ->get()
            ->sum(fn($e) => $e->section->subject->units ?? 0);
    }

    public function totalTuition(): float
    {
        // Simple calculation: 500 per unit + 1500 miscellaneous
        $units = $this->totalUnits();
        if ($units === 0) return 0;
        return ($units * 500) + 1500;
    }

    public function grades()
    {
        return $this->hasManyThrough(Grade::class, Enrollment::class);
    }

    public function preEnlistments()
    {
        return $this->hasMany(PreEnlistment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if the student has been admitted by the Admission System.
     */
    public function isAdmitted(): bool
    {
        return $this->admission_status === 'admitted';
    }

    /**
     * Check if admission is still pending.
     */
    public function isPending(): bool
    {
        return $this->admission_status === 'pending';
    }

    /**
     * Get a formatted admission status label.
     */
    public function getAdmissionBadgeAttribute(): string
    {
        return match($this->admission_status) {
            'admitted' => 'Admitted',
            'pending'  => 'Pending Admission',
            'rejected' => 'Not Admitted',
            default    => 'Unknown',
        };
    }
}