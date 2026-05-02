<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Room;
use App\Models\Enrollment;

class Section extends Model
{
    protected $table = 'reg_sections';

    protected $fillable = [
        'subject_id', 'teacher_id', 'room_id', 'section_name', 
        'day', 'start_time', 'end_time', 'capacity', 'semester', 'school_year'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
