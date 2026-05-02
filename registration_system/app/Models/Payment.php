<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'reg_payments';

    protected $fillable = [
        'student_id', 'amount', 'status', 'reference_number', 'payment_date', 'semester', 'school_year'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
