<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Setting;
use App\Models\Payment;
use App\Models\AuditLog;
use App\Models\EnrollmentData;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Teacher;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }
        
        $student = $user->student;

        if (!$student) {
            Auth::logout();
            return redirect('/')->withErrors(['email' => 'Student profile not found. Please contact the administrator.']);
        }

        $activeSemester = Setting::getValue('active_semester');
        $activeSY = Setting::getValue('active_school_year');

        $enrolledSubjects = $student->enrollments()
            ->with(['section.subject', 'section.room', 'section.teacher.user'])
            ->where('status', 'enrolled')
            ->whereHas('section', function($q) use ($activeSemester, $activeSY) {
                if ($activeSemester) $q->where('semester', $activeSemester);
                if ($activeSY) $q->where('school_year', $activeSY);
            })
            ->get();
        
        $schedule = $enrolledSubjects->map(function($enrollment) {
            return $enrollment->section;
        });
        
        $totalUnits = $enrolledSubjects->sum(function($e) { return $e->section->subject->units ?? 0; });
        $totalSubjects = $enrolledSubjects->count();
        
        $enrollmentData = EnrollmentData::where('student_id', $student->id)
            ->where('semester', $activeSemester)
            ->where('academic_year', $activeSY)
            ->first();

        return view('dashboard', compact(
            'student', 'enrolledSubjects', 'schedule', 
            'totalUnits', 'totalSubjects', 'enrollmentData'
        ));
    }
    
    public function admin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        $applications = \App\Models\Application::all();
        $studentsCount = \App\Models\Student::count();
        $teachersCount = \App\Models\Teacher::count();
        $sectionsCount = \App\Models\Section::count();

        // Current semester enrollments
        $activeSemester = Setting::getValue('active_semester');
        $activeSY = Setting::getValue('active_school_year');
        
        $enrollmentsCount = Enrollment::where('status', 'enrolled')
            ->whereHas('section', function($q) use ($activeSemester, $activeSY) {
                if ($activeSemester) $q->where('semester', $activeSemester);
                if ($activeSY) $q->where('school_year', $activeSY);
            })->count();

        // Payment stats
        $pendingPayments = Payment::where('status', 'pending')->count();
        $pendingAmount = Payment::where('status', 'pending')->sum('amount');

        // Application pipeline
        $appPending = $applications->where('status', 'Pending')->count();
        $appApproved = $applications->where('status', 'Approved')->count();
        $appRejected = $applications->where('status', 'Rejected')->count();

        // Enrollment by course (for chart)
        $enrollmentByCourse = \App\Models\Student::select('course', \DB::raw('count(*) as total'))
            ->groupBy('course')
            ->orderBy('total', 'desc')
            ->limit(8)
            ->get();

        // Recent enrollments
        $recentEnrollments = Enrollment::with(['student.user', 'section.subject'])
            ->where('status', 'enrolled')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'applications', 'studentsCount', 'teachersCount', 'sectionsCount',
            'enrollmentsCount', 'pendingPayments', 'pendingAmount',
            'appPending', 'appApproved', 'appRejected',
            'enrollmentByCourse', 'recentEnrollments'
        ));
    }

    public function teachingLoad()
    {
        if (Auth::user()->role !== 'teacher') abort(403);

        $teacher = Auth::user()->teacher;
        if (!$teacher) {
            Auth::logout();
            return redirect('/')->withErrors(['email' => 'Teacher profile not found.']);
        }

        $sections = $teacher->sections()->with(['subject', 'room'])->get();

        return view('teacher.teaching_load', compact('teacher', 'sections'));
    }

    public function teacher()
    {
        if (Auth::user()->role !== 'teacher') {
            abort(403, 'Unauthorized access.');
        }

        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            Auth::logout();
            return redirect('/')->withErrors(['email' => 'Teacher profile not found. Please contact the administrator.']);
        }

        $sections = $teacher->sections()->with(['subject', 'room'])->get();
        $sectionIds = $sections->pluck('id');

        // Analytics
        $totalStudents = Enrollment::whereIn('section_id', $sectionIds)
            ->where('status', 'enrolled')
            ->distinct('student_id')
            ->count();

        // Today's classes
        $dayOfWeek = now()->format('l'); // e.g., "Monday"
        $todayClasses = $teacher->sections()
            ->where('day', 'like', "%$dayOfWeek%")
            ->with(['subject', 'room'])
            ->orderBy('start_time')
            ->get();

        // Average Attendance %
        $totalAttendanceRecords = Attendance::whereIn('section_id', $sectionIds)->count();
        $presentCount = Attendance::whereIn('section_id', $sectionIds)->where('status', 'Present')->count();
        $avgAttendance = $totalAttendanceRecords > 0 ? ($presentCount / $totalAttendanceRecords) * 100 : 0;

        // Pass/Fail distribution
        $enrolledIds = Enrollment::whereIn('section_id', $sectionIds)->where('status', 'enrolled')->pluck('id');
        $passingCount = Grade::whereIn('enrollment_id', $enrolledIds)->where('final_grade', '>=', 75)->count();
        $failingCount = Grade::whereIn('enrollment_id', $enrolledIds)->where('final_grade', '<', 75)->whereNotNull('final_grade')->count();
        $ungradedCount = $enrolledIds->count() - ($passingCount + $failingCount);

        // Announcements
        try {
            $announcements = \App\Models\Announcement::where('teacher_id', $teacher->id)
                ->with('section')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            $announcements = collect();
        }

        return view('dashboard.teacher', compact(
            'teacher', 'sections', 'totalStudents', 'todayClasses', 
            'avgAttendance', 'passingCount', 'failingCount', 'ungradedCount', 'announcements'
        ));
    }

    public function showPage($role, $slug)
    {
        $user = Auth::user();
        if ($user->role !== $role) {
            abort(403);
        }

        // Redirect legacy routes to new specific routes
        if ($role === 'student') {
            switch ($slug) {
                case 'pre-enlistment':
                    return redirect()->route('student.transactions.pre_enlistment');
                case 'enrollment':
                    return redirect()->route('student.transactions.enrollment');
                case 'enrolled-subjects':
                    return redirect()->route('student.reports.enrolled_subjects');
                case 'term-grades':
                    return redirect()->route('student.reports.term_grades');
            }
        }

        $title = ucwords(str_replace('-', ' ', $slug));
        
        // Contextual data based on role
        $data = [];
        if ($role === 'student') {
            $data['student'] = $user->student;
        } elseif ($role === 'teacher') {
            $data['teacher'] = $user->teacher;
        }

        $viewName = "dashboard.pages.{$slug}";
        if (!view()->exists($viewName)) {
            $viewName = 'dashboard.pages.placeholder';
        }
        return view($viewName, array_merge(['title' => $title], $data));
    }

    public function settings()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $all_settings = \App\Models\Setting::all();
        return view('admin.settings', compact('all_settings'));
    }

    public function saveSettings(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        foreach ($request->settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->logAction('Updated System Settings', null, $request->settings);

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }

    public function payments()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $payments = Payment::with('student.user')->latest()->get();
            return view('admin.finance.index', compact('payments'));
        }
        
        $payments = $user->student->payments()->latest()->get();
        return view('student.finance.index', compact('payments'));
    }

    public function updatePaymentStatus(Request $request, Payment $payment)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $payment->update([
            'status' => $request->status,
            'payment_date' => $request->status === 'paid' ? now() : null,
        ]);

        $this->logAction('Updated Payment Status', $payment, ['status' => $request->status]);

        return redirect()->back()->with('success', 'Payment status updated.');
    }

    public function auditLogs()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $logs = AuditLog::with('user')->latest()->paginate(50);
        return view('admin.audit.index', compact('logs'));
    }

    public function departments()
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'teacher') {
            abort(403, 'Unauthorized access.');
        }

        $courses = DB::table('adm_courses')
            ->join('colleges', 'adm_courses.college_id', '=', 'colleges.id')
            ->join('campuses', 'colleges.campus_id', '=', 'campuses.id')
            ->select(
                'adm_courses.id',
                'adm_courses.name as course_name',
                'colleges.name as college',
                'campuses.name as campus'
            )
            ->orderBy('campuses.name')
            ->orderBy('colleges.name')
            ->orderBy('adm_courses.name')
            ->get();

        $coursesByDept = $courses->groupBy('college');

        return view('departments.index', compact('coursesByDept'));
    }
}