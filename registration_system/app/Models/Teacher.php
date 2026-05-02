<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'reg_teachers';

    protected $fillable = [
        'user_id',
        'teacher_id',
        'campus',
        'college',
        'department_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
