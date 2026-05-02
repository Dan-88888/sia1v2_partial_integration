<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Public routes — Landing Page
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->must_change_password) {
            return redirect()->route('password.force_change');
        }
        if ($user->role === 'admin') return redirect()->route('admin.dashboard');
        if ($user->role === 'teacher') return redirect()->route('teacher.dashboard');
        return redirect()->route('dashboard');
    }
    $app_settings = [
        'school_name' => \App\Models\Setting::getValue('school_name', 'Partido State University'),
        'active_semester' => \App\Models\Setting::getValue('active_semester', '1'),
        'active_school_year' => \App\Models\Setting::getValue('active_school_year', date('Y') . '-' . (date('Y') + 1)),
    ];
    return view('welcome', compact('app_settings'));
});

Route::get('/applications/status', [App\Http\Controllers\ApplicationController::class, 'checkStatus'])->name('applications.status');
Route::post('/applications/submit', [App\Http\Controllers\ApplicationController::class, 'store'])->name('applications.submit');

// Course Selection API
Route::get('/api/campuses', [App\Http\Controllers\CourseController::class, 'getCampuses']);
Route::get('/api/colleges', [App\Http\Controllers\CourseController::class, 'getCollegesByCampus']);
Route::get('/api/courses', [App\Http\Controllers\CourseController::class, 'getCoursesByCollege']);


// Authentication routes
Route::get('login', function() { return redirect('/'); })->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('api-login', [LoginController::class, 'apiLogin']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('auto-logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return response()->json(['ok' => true]);
});

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Force password change routes (must be accessible while logged in but before middleware blocks)
Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', [LoginController::class, 'showForcePasswordChange'])->name('password.force_change');
    Route::post('/password/change', [LoginController::class, 'forcePasswordChange'])->name('password.force_change.update');
    Route::post('/password/change/skip', [LoginController::class, 'skipForcePasswordChange'])->name('password.force_change.skip');
});

// Protected routes
Route::middleware(['auth', 'force.password'])->group(function () {
    // Profile routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('admitted');
    Route::get('/dashboard/{role}/{slug}', [DashboardController::class, 'showPage'])->name('dashboard.page')->middleware('admitted');
    Route::get('/departments', [DashboardController::class, 'departments'])->name('departments.index');

    
    // Admin - Academic Management
    // Admin routes — protected by admin middleware
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

        Route::prefix('admin')->name('admin.')->group(function() {
            Route::resource('courses', App\Http\Controllers\Admin\CourseController::class);
            Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class);
            Route::resource('rooms', App\Http\Controllers\Admin\RoomController::class);
            Route::resource('sections', App\Http\Controllers\Admin\SectionController::class);
            
            Route::get('students', [App\Http\Controllers\Admin\StudentManagementController::class, 'index'])->name('students.index');
            Route::get('students/{student}/edit', [App\Http\Controllers\Admin\StudentManagementController::class, 'edit'])->name('students.edit');
            Route::put('students/{student}', [App\Http\Controllers\Admin\StudentManagementController::class, 'update'])->name('students.update');
            Route::get('students/{student}/enrollment-data', [App\Http\Controllers\Admin\StudentManagementController::class, 'enrollmentData'])->name('students.enrollment_data');
            Route::post('students/{student}/enrollment-data', [App\Http\Controllers\Admin\StudentManagementController::class, 'updateEnrollmentData'])->name('students.enrollment_data.update');
            Route::delete('students/{id}', [App\Http\Controllers\Admin\StudentManagementController::class, 'destroy'])->name('students.destroy');
            Route::get('teachers', [App\Http\Controllers\Admin\TeacherManagementController::class, 'index'])->name('teachers.index');
            Route::get('teachers/{teacher}/edit', [App\Http\Controllers\Admin\TeacherManagementController::class, 'edit'])->name('teachers.edit');
            Route::put('teachers/{teacher}', [App\Http\Controllers\Admin\TeacherManagementController::class, 'update'])->name('teachers.update');
            Route::delete('teachers/{id}', [App\Http\Controllers\Admin\TeacherManagementController::class, 'destroy'])->name('teachers.destroy');
            Route::get('registration-records', [App\Http\Controllers\ApplicationController::class, 'index'])->name('registration.records');
            Route::get('applications', [App\Http\Controllers\ApplicationController::class, 'index'])->name('applications.index');
            
            // Admin User Management
            Route::get('users', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
            Route::get('users/create', [App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('users.create');
            Route::post('users', [App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('users.store');
            Route::get('users/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
            Route::delete('users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.destroy');

            // Admin Settings
            Route::get('settings', [App\Http\Controllers\DashboardController::class, 'settings'])->name('settings.index');
            Route::post('settings', [App\Http\Controllers\DashboardController::class, 'saveSettings'])->name('settings.save');

            // Finance & Audit
            Route::get('finance', [App\Http\Controllers\DashboardController::class, 'payments'])->name('finance.index');
            Route::post('finance/{payment}/status', [App\Http\Controllers\DashboardController::class, 'updatePaymentStatus'])->name('finance.status');
            Route::get('audit-logs', [App\Http\Controllers\DashboardController::class, 'auditLogs'])->name('audit.index');

            // Bulk Imports
            Route::post('students/import', [App\Http\Controllers\Admin\StudentManagementController::class, 'import'])->name('students.import');
            Route::post('subjects/import', [App\Http\Controllers\Admin\SubjectController::class, 'import'])->name('subjects.import');
        });
    });



    // Admin application actions — protected by admin middleware
    Route::middleware(['admin'])->group(function () {
        Route::post('/admin/applications/{id}/approve', [App\Http\Controllers\ApplicationController::class, 'approve'])->name('admin.applications.approve');
        Route::post('/admin/applications/{id}/reject', [App\Http\Controllers\ApplicationController::class, 'reject'])->name('admin.applications.reject');
        Route::post('/admin/applications/clear-all', [App\Http\Controllers\ApplicationController::class, 'clearAll'])->name('admin.applications.clear_all');
        Route::delete('/admin/applications/{id}', [App\Http\Controllers\ApplicationController::class, 'destroy'])->name('admin.applications.destroy');
    });

    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
    
    // Enrollment routes
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('/enrollments/enroll/{section}', [EnrollmentController::class, 'enroll'])->name('enrollments.enroll');
    Route::delete('/enrollments/drop/{section}', [EnrollmentController::class, 'drop'])->name('enrollments.drop');

    // Grades
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');

    // Certificate of Registration
    Route::get('/enrollments/cor', [EnrollmentController::class, 'viewCor'])->name('enrollments.cor');
    Route::get('/enrollments/cor/download', [EnrollmentController::class, 'downloadCor'])->name('enrollments.cor.download');
    // Teacher Routes
    Route::middleware(['teacher'])->group(function () {
        Route::get('/teacher/dashboard', [DashboardController::class, 'teacher'])->name('teacher.dashboard');
        Route::get('/teacher/teaching-load', [DashboardController::class, 'teachingLoad'])->name('teacher.teaching_load');
        Route::post('/teacher/announcements', [App\Http\Controllers\Teacher\SectionManagementController::class, 'storeAnnouncement'])->name('teacher.announcements.store');
        
        Route::prefix('teacher/sections/{section}')->name('teacher.sections.')->group(function() {
            Route::get('attendance', [App\Http\Controllers\Teacher\SectionManagementController::class, 'attendance'])->name('attendance');
            Route::get('attendance/history', [App\Http\Controllers\Teacher\SectionManagementController::class, 'attendanceHistory'])->name('attendance.history');
            Route::post('attendance', [App\Http\Controllers\Teacher\SectionManagementController::class, 'saveAttendance'])->name('attendance.save');
            Route::post('attendance/import', [App\Http\Controllers\Teacher\SectionManagementController::class, 'importAttendance'])->name('attendance.import');
            Route::get('grades', [App\Http\Controllers\Teacher\SectionManagementController::class, 'grades'])->name('grades');
            Route::post('grades', [App\Http\Controllers\Teacher\SectionManagementController::class, 'saveGrades'])->name('grades.save');
            Route::post('grades/import', [App\Http\Controllers\Teacher\SectionManagementController::class, 'importGrades'])->name('grades.import');
            Route::post('grades/publish', [App\Http\Controllers\Teacher\SectionManagementController::class, 'publishGrades'])->name('publish');
            Route::get('roster/download', [App\Http\Controllers\Teacher\SectionManagementController::class, 'downloadRoster'])->name('roster.download');
        });
    });
    // Student Transactions
    Route::middleware(['admitted'])->group(function () {
        Route::prefix('student/transactions')->name('student.transactions.')->group(function() {
            Route::get('pre-enlistment', [App\Http\Controllers\Student\TransactionController::class, 'preEnlistment'])->name('pre_enlistment');
            Route::post('pre-enlistment/add', [App\Http\Controllers\Student\TransactionController::class, 'addPreEnlistment'])->name('pre_enlistment.add');
            Route::post('pre-enlistment/remove', [App\Http\Controllers\Student\TransactionController::class, 'removePreEnlistment'])->name('pre_enlistment.remove');
            Route::post('enrollment-data/update', [App\Http\Controllers\Student\TransactionController::class, 'updateEnrollmentData'])->name('enrollment_data.update');
            Route::get('enrollment', [App\Http\Controllers\Student\TransactionController::class, 'enrollment'])->name('enrollment');
        });

        // Student Reports
        Route::prefix('student/reports')->name('student.reports.')->group(function() {
            Route::get('enrolled-subjects', [App\Http\Controllers\Student\ReportController::class, 'enrolledSubjects'])->name('enrolled_subjects');
            Route::get('term-grades', [App\Http\Controllers\Student\ReportController::class, 'termGrades'])->name('term_grades');
        });

        // Student specific finance
        Route::get('/finance', [App\Http\Controllers\DashboardController::class, 'payments'])->name('student.finance');
    });


});

// Public Application Submission
Route::post('/applications/student', [App\Http\Controllers\ApplicationController::class, 'storeStudent'])->name('applications.student.store');
Route::post('/applications/teacher', [App\Http\Controllers\ApplicationController::class, 'storeTeacher'])->name('applications.teacher.store');