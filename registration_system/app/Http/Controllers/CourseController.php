<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function getCampuses()
    {
        $campuses = DB::table('campuses')
            ->orderBy('name')
            ->pluck('name');
        return response()->json($campuses);
    }

    public function getCollegesByCampus(Request $request)
    {
        $campus = $request->query('campus');
        $colleges = DB::table('colleges')
            ->join('campuses', 'colleges.campus_id', '=', 'campuses.id')
            ->where('campuses.name', $campus)
            ->orderBy('colleges.name')
            ->pluck('colleges.name');
        return response()->json($colleges);
    }

    public function getCoursesByCollege(Request $request)
    {
        $college = $request->query('college');
        $courses = DB::table('adm_courses')
            ->join('colleges', 'adm_courses.college_id', '=', 'colleges.id')
            ->where('colleges.name', $college)
            ->orderBy('adm_courses.name')
            ->get(['adm_courses.name as course_name', 'adm_courses.name as course_code']);
        return response()->json($courses);
    }
}
