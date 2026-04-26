<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Get all unique campuses.
     */
    public function getCampuses()
    {
        $campuses = Course::select('campus')
            ->whereNotNull('campus')
            ->where('campus', '!=', '')
            ->distinct()
            ->orderBy('campus')
            ->pluck('campus');
        return response()->json($campuses);
    }

    /**
     * Get colleges for a specific campus.
     */
    public function getCollegesByCampus(Request $request)
    {
        $campus = $request->query('campus');
        $colleges = Course::where('campus', $campus)
            ->select('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');
            
        return response()->json($colleges);
    }

    /**
     * Get courses for a specific college and campus.
     */
    public function getCoursesByCollege(Request $request)
    {
        $campus = $request->query('campus');
        $college = $request->query('college');
        
        $courses = Course::where('campus', $campus)
            ->where('department', $college)
            ->orderBy('course_name')
            ->get(['course_name', 'course_code']);
            
        return response()->json($courses);
    }
}
