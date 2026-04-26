<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(SettingSeeder::class);
        $this->call(UniversityDataSeeder::class);

        // Create admin user
        User::firstOrCreate(['email' => 'admin@university.com'], [
            'name'     => 'Admin User',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // Create teacher user
        $teacherUser = User::firstOrCreate(['email' => 'teacher@university.com'], [
            'name'     => 'Prof. Maria Santos',
            'password' => Hash::make('teacher123'),
            'role'     => 'teacher',
        ]);

        $teacher = \App\Models\Teacher::firstOrCreate(['user_id' => $teacherUser->id], [
            'teacher_id'    => 'TCH-2024-001',
            'department_id' => 1,
        ]);

        // Create student user
        $studentUser = User::firstOrCreate(['email' => 'student@university.com'], [
            'name'     => 'John Doe',
            'password' => Hash::make('student123'),
            'role'     => 'student',
        ]);

        Student::firstOrCreate(['user_id' => $studentUser->id], [
            'student_number'   => '2024-00001',
            'course'           => 'Bachelor of Science in Computer Science',
            'year_level'       => 2,
            'admission_status' => 'admitted',
            'admission_date'   => now(),
        ]);

        // Create rooms
        $room101 = \App\Models\Room::firstOrCreate(['name' => 'Room 101'], ['capacity' => 40]);

        // Use BSCS from UniversityDataSeeder (Goa Campus)
        $csCourse = Course::where('course_code', 'BSCS')->first();
        if (!$csCourse) return;

        $subjects = [
            ['course_id' => $csCourse->id, 'subject_code' => 'CS101', 'subject_name' => 'Intro to Programming', 'units' => 3],
            ['course_id' => $csCourse->id, 'subject_code' => 'CS102', 'subject_name' => 'Data Structures', 'units' => 3],
        ];

        foreach ($subjects as $sData) {
            $subject = \App\Models\Subject::firstOrCreate(
                ['subject_code' => $sData['subject_code']],
                $sData
            );

            \App\Models\Section::firstOrCreate(
                ['section_name' => $subject->subject_code . '-A'],
                [
                    'subject_id'  => $subject->id,
                    'teacher_id'  => $teacher->id,
                    'room_id'     => $room101->id,
                    'day'         => 'Monday',
                    'start_time'  => '08:00:00',
                    'end_time'    => '10:00:00',
                    'capacity'    => 40,
                    'semester'    => 1,
                    'school_year' => '2024-2025',
                ]
            );
        }
    }
}
