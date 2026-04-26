<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\StudentApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationStatusUpdated;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(env('FRONTEND_URL', 'http://localhost:3000'));
    }

    public function dashboard()
    {
        $totalApplicants   = StudentApplication::count();
        $regularStudents   = StudentApplication::where('student_type', 'Regular')->count();
        $irregularStudents = StudentApplication::where('student_type', 'Irregular')->count();
        $transferees       = StudentApplication::where('student_type', 'Transferee')->count();

        $pendingCount    = StudentApplication::where('status', 'Pending')->count();
        $approvedCount   = StudentApplication::where('status', 'Approved')->count();
        $rejectedCount   = StudentApplication::where('status', 'Rejected')->count();
        $waitlistedCount = StudentApplication::where('status', 'Waitlisted')->count();

        $todayCount     = StudentApplication::whereDate('created_at', today())->count();
        $thisMonthCount = StudentApplication::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)->count();
        $lastMonthCount = StudentApplication::whereMonth('created_at', now()->subMonth()->month)
                            ->whereYear('created_at', now()->subMonth()->year)->count();
        $monthChange = $lastMonthCount > 0
            ? round((($thisMonthCount - $lastMonthCount) / $lastMonthCount) * 100)
            : ($thisMonthCount > 0 ? 100 : 0);

        $campusCounts = [];
        $campuses = Campus::all();
        foreach ($campuses as $campus) {
            $campusCounts[$campus->name] = StudentApplication::where('campus', $campus->name)->count();
        }

        $recentApplications = StudentApplication::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalApplicants',
            'regularStudents',
            'irregularStudents',
            'transferees',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'waitlistedCount',
            'todayCount',
            'thisMonthCount',
            'monthChange',
            'campusCounts',
            'recentApplications'
        ));
    }

    public function applications(Request $request)
    {
        $query = StudentApplication::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'LIKE', "%{$search}%")
                  ->orWhere('middlename', 'LIKE', "%{$search}%")
                  ->orWhere('lastname', 'LIKE', "%{$search}%")
                  ->orWhere('course', 'LIKE', "%{$search}%")
                  ->orWhere('campus', 'LIKE', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('campus')) {
            $query->where('campus', $request->campus);
        }
        if ($request->filled('course')) {
            $query->where('course', $request->course);
        }
        if ($request->filled('student_type')) {
            $query->where('student_type', $request->student_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15);
        $campuses = Campus::all();
        
        return view('admin.applications', compact('applications', 'campuses'));
    }

    public function viewApplication($id)
    {
        $application = StudentApplication::findOrFail($id);
        return view('admin.view', compact('application'));
    }

    public function approveApplication($id)
    {
        $application = StudentApplication::findOrFail($id);

        if ($application->status === 'Approved') {
            return redirect()->back()->with('info', 'Application is already approved.');
        }

        $application->update(['status' => 'Approved']);
        $this->sendStatusEmail($application);

        return redirect()->back()->with('success', 'Application approved successfully!');
    }

    public function rejectApplication($id)
    {
        $application = StudentApplication::findOrFail($id);

        if ($application->status === 'Rejected') {
            return redirect()->back()->with('info', 'Application is already rejected.');
        }

        $application->update(['status' => 'Rejected']);
        $this->sendStatusEmail($application);

        return redirect()->back()->with('success', 'Application rejected successfully!');
    }

    public function waitlistApplication($id)
    {
        $application = StudentApplication::findOrFail($id);

        if ($application->status === 'Waitlisted') {
            return redirect()->back()->with('info', 'Application is already waitlisted.');
        }

        $application->update(['status' => 'Waitlisted']);
        $this->sendStatusEmail($application);

        return redirect()->back()->with('success', 'Application waitlisted successfully!');
    }

    private function sendStatusEmail($application)
    {
        try {
            Mail::to($application->gmail_account)->send(new ApplicationStatusUpdated($application));
        } catch (\Exception $e) {
            \Log::error('Status mail failed: ' . $e->getMessage());
        }
    }

    public function editApplication($id)
    {
        $application = StudentApplication::findOrFail($id);
        $campuses = Campus::with('colleges.courses')->get();
        return view('admin.edit-application', compact('application', 'campuses'));
    }

    public function updateApplication(Request $request, $id)
    {
        $application = StudentApplication::findOrFail($id);
        
        $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'age' => 'required|integer|min:15|max:100',
            'contact_number' => 'required|string|max:20',
            'temporary_address' => 'required|string',
            'permanent_address' => 'required|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'student_type' => 'required|in:Regular,Irregular,Transferee',
            'campus' => 'required|string',
            'college' => 'required|string',
            'course' => 'required|string',
            'status' => 'required|in:Pending,Approved,Rejected,Waitlisted',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'birth_certificate' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048',
            'report_card' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['photo', 'birth_certificate', 'report_card']);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('documents/photos', 'public');
        }
        if ($request->hasFile('birth_certificate')) {
            $data['birth_certificate_path'] = $request->file('birth_certificate')->store('documents/birth_certificates', 'public');
        }
        if ($request->hasFile('report_card')) {
            $data['report_card_path'] = $request->file('report_card')->store('documents/report_cards', 'public');
        }

        $application->update($data);

        return redirect()->route('admin.applications')->with('success', 'Application updated successfully!');
    }

    public function deleteApplication($id)
    {
        $application = StudentApplication::findOrFail($id);
        $application->delete();
        return redirect()->back()->with('success', 'Application deleted successfully!');
    }
}