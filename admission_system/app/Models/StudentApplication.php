<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'adm_applications';

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->firstname,
            $this->middlename,
            $this->lastname,
            $this->name_extender,
        ]);
        return implode(' ', $parts);
    }

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'name_extender',
        'age',
        'sex',
        'civil_status',
        'date_of_birth',
        'birth_place',
        'contact_number',
        'gmail_account',
        'temporary_address',
        'permanent_address',
        'guardian_name',
        'guardian_relationship',
        'guardian_phone',
        'student_type',
        'campus',
        'college',
        'course',
        'photo_path',
        'birth_certificate_path',
        'report_card_path',
        'status'
    ];
}