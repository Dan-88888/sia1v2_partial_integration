<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function enrolledSubjects(Request $request)
    {
        $student = Auth::user()->student;
        if (!$student) abort(404);

        $activeSemester = $request->get('semester', Setting::getValue('active_semester', '1'));
        $activeSY = $request->get('school_year', Setting::getValue('active_school_year', '2024-2025'));

        $enrollments = $student->enrollments()
            ->with(['section.subject', 'section.teacher.user', 'section.room'])
            ->where('status', 'enrolled')
            ->whereHas('section', function($q) use ($activeSemester, $activeSY) {
                $q->where('semester', $activeSemester)->where('school_year', $activeSY);
            })
            ->get();

        return view('student.reports.enrolled_subjects', compact('student', 'enrollments', 'activeSemester', 'activeSY'));
    }

    public function termGrades(Request $request)
    {
        $student = Auth::user()->student;
        if (!$student) abort(404);

        // For historical view, get all distinct semesters/years student has enrollments for
        $periods = Enrollment::where('student_id', $student->id)
            ->join('reg_sections', 'reg_enrollments.section_id', '=', 'reg_sections.id')
            ->select('reg_sections.semester', 'reg_sections.school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        $activeSemester = $request->get('semester', Setting::getValue('active_semester', '1'));
        $activeSY = $request->get('school_year', Setting::getValue('active_school_year', '2024-2025'));

        $grades = Grade::whereHas('enrollment', function($q) use ($student, $activeSemester, $activeSY) {
            $q->where('student_id', $student->id)
              ->whereHas('section', function($sq) use ($activeSemester, $activeSY) {
                  $sq->where('semester', $activeSemester)->where('school_year', $activeSY);
              });
        })->with('enrollment.section.subject')->get();

        return view('student.reports.term_grades', compact('student', 'grades', 'periods', 'activeSemester', 'activeSY'));
    }
}
