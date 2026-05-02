<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreEnlistment extends Model
{
    protected $table = 'reg_pre_enlistments';

    protected $fillable = [
        'student_id',
        'subject_id',
        'semester',
        'school_year'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
