<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'reg_applications';

    protected $fillable = [
        'tracking_number',
        'name',
        'type',
        'campus',
        'college',
        'course',
        'year_level',
        'birthdate',
        'contact',
        'address',
        'email',
        'department_id',
        'documents',
        'remarks',
        'status',
        'temp_password',
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    /**
     * Generate the official university email address for students.
     * Format: [firstLetterOfName][lastName][last3DigitsOfTracking].pbox@parsu.edu.ph
     */
    public function getUniversityEmailAttribute()
    {
        $nameParts = explode(' ', trim($this->name));
        $firstName = $nameParts[0] ?? '';
        $lastName = end($nameParts) ?? '';

        if ($this->type === 'student') {
            $firstInitial = substr($firstName, 0, 1);
            $trackingLast3 = substr($this->tracking_number, -3);
            return strtolower($firstInitial . $lastName . $trackingLast3) . '.pbox@parsu.edu.ph';
        } elseif ($this->type === 'teacher') {
            return strtolower($firstName . '.' . $lastName) . '.pbox@parsu.edu.ph';
        }

        return null;
    }
}
