<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationSubmitted;
use App\Mail\ApplicationStatusUpdated;

class ApplicationController extends Controller
{
    /**
     * Unified store method for Student and Teacher applications.
     */
    public function store(Request $request)
    {
        try {
            $type = $request->query('type', 'student');

            $rules = [
                'name' => 'required|string|max:255',
                'birthdate' => 'nullable|date',
                'contact' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'email' => 'required|email|max:255',
                'campus' => 'required|string',
                'college' => 'required|string',
                'documents.*' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            ];

            if ($type === 'student') {
                $rules['course'] = 'required|string';
                $rules['year_level'] = 'required|string';
            }

            $validated = $request->validate($rules);

            // Check for duplicate application or existing user account
            $emailExistsInApplications = Application::where('email', $validated['email'])
                ->whereIn('status', ['Pending', 'Approved', 'Admitted'])
                ->exists();
            $emailExistsInUsers = User::where('email', $validated['email'])->exists();

            if ($emailExistsInApplications || $emailExistsInUsers) {
                return response()->json([
                    'success' => false,
                    'message' => 'An account or application with this email already exists and is strictly restricted to one user.'
                ], 422);
            }

            // Generate Numeric Tracking Number starting at 26001
            $lastNumericApp = Application::whereRaw('tracking_number REGEXP "^[0-9]+$"')
                ->orderByRaw('CAST(tracking_number AS UNSIGNED) DESC')
                ->first();
            $trackingNumber = $lastNumericApp ? (int)$lastNumericApp->tracking_number + 1 : 26001;
            
            $documentPaths = [];
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('applications', 'public');
                    $documentPaths[] = $path;
                }
            }

            $tempPassword = null;
            if ($type === 'student') {
                // Safe password generation logic for students
                $nameParts = explode(' ', trim($validated['name']));
                $firstName = strtolower($nameParts[0] ?? 'student');
                $yearLevel = $validated['year_level'];
                
                $activeSY = \App\Models\Setting::getValue('active_school_year', date('Y'));
                $syParts = explode('-', $activeSY);
                $endYear = trim(end($syParts));
                $shortSY = substr($endYear, -2);
                
                $tempPassword = $firstName . $yearLevel . $shortSY;
            }

            $application = Application::create([
                'tracking_number' => $trackingNumber,
                'type' => $type,
                'campus' => $validated['campus'],
                'college' => $validated['college'],
                'name' => $validated['name'],
                'course' => $validated['course'] ?? null,
                'year_level' => $validated['year_level'] ?? null,
                'birthdate' => $validated['birthdate'],
                'contact' => $validated['contact'],
                'address' => $validated['address'],
                'email' => $validated['email'],
                'documents' => $documentPaths,
                'status' => 'Pending',
                'temp_password' => $tempPassword,
            ]);

            try {
                Mail::to($application->email)->send(new ApplicationSubmitted($application));
            } catch (\Exception $e) {
                \Log::error("Failed to send application email: " . $e->getMessage());
            }
            
            $this->logAction(ucfirst($type) . ' Application Submitted', $application);

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully!',
                'tracking_number' => $trackingNumber,
                'university_email' => $application->university_email,
                'temp_password' => $tempPassword
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error("Application submission error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred during submission: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeStudent(Request $request) { 
        $request->merge(['type' => 'student']);
        return $this->store($request); 
    }
    
    public function storeTeacher(Request $request) { 
        $request->merge(['type' => 'teacher']);
        return $this->store($request); 
    }

    public function checkStatus(Request $request)
    {
        $request->validate(['tracking_number' => 'required|string']);
        
        $application = Application::where('tracking_number', $request->tracking_number)->first();
        
        if (!$application) {
            return redirect()->back()->with('error', 'Tracking number not found.');
        }

        return view('applications.status', compact('application'));
    }

    public function index(Request $request)
    {
        $query = Application::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('tracking_number', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return $applications;
        }
        return view('admin.registration.index', compact('applications'));
    }

    public function approve($id)
    {
        $application = Application::findOrFail($id);

        try {
            DB::transaction(function () use ($application) {
                // Determine the primary email (use generated university email if available)
                $primaryEmail = $application->university_email ?? $application->email;

                // Check if user already exists (idempotent check)
                $user = User::where('email', $primaryEmail)->first();

                $recordedPassword = null; // initialize so it is always defined for the mail call

                if (!$user) {
                    $user = User::create([
                        'name' => $application->name,
                        'email' => $primaryEmail,
                        'password' => Hash::make('placeholder'), // overwritten below per role
                        'role' => $application->type,
                        'birthdate' => $application->birthdate,
                        'contact' => $application->contact,
                        'address' => $application->address,
                        'must_change_password' => true,
                    ]);
                }

                if ($application->type === 'student') {
                    $suffix = str_pad($user->id, 4, '0', STR_PAD_LEFT);
                    $recordedPassword = $application->temp_password;

                    // Always resolve the active school year for student_number
                    $activeSY = \App\Models\Setting::getValue('active_school_year', date('Y'));
                    $syParts  = explode('-', $activeSY);
                    $shortSY  = substr(trim(end($syParts)), -2);

                    // Re-calculate if missing (legacy records)
                    if (!$recordedPassword) {
                        $fullNameParts    = explode(' ', trim($application->name));
                        $firstName        = strtolower($fullNameParts[0]);
                        $yearLevel        = $application->year_level;
                        $recordedPassword = $firstName . $yearLevel . $shortSY;
                    }

                    $user->update(['password' => Hash::make($recordedPassword)]);

                    Student::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'student_number' => 'STU-20' . $shortSY . '-' . $suffix,
                            'campus' => $application->campus,
                            'college' => $application->college,
                            'course' => $application->course,
                            'year_level' => $application->year_level,
                            'admission_status' => 'admitted',
                            'admission_date' => now(),
                        ]
                    );
                } else {
                    // Generate a deterministic temp password for teachers
                    $nameParts        = explode(' ', trim($application->name));
                    $firstName        = strtolower($nameParts[0] ?? 'teacher');
                    $recordedPassword = 'Tch@' . ucfirst($firstName) . date('Y');

                    $user->update(['password' => Hash::make($recordedPassword)]);

                    Teacher::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'teacher_id'    => 'TCH-' . date('Y') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                            'campus'        => $application->campus,
                            'college'       => $application->college,
                            'department_id' => $application->college,
                        ]
                    );
                }

                $application->update([
                    'status'        => 'Approved',
                    'email'         => $primaryEmail,
                    'temp_password' => $recordedPassword,
                ]);

                try {
                    Mail::to($application->email)->send(new ApplicationStatusUpdated($application, $recordedPassword));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Approval email failed: ' . $e->getMessage());
                }

                $this->logAction('Approved Application', $application);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Approval failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Application approved and student account synchronized!');
    }

    public function reject(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->update(['status' => 'Rejected']);
        
        try {
            Mail::to($application->email)->send(new ApplicationStatusUpdated($application));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Rejection email failed: ' . $e->getMessage());
        }

        $this->logAction('Rejected Application', $application);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Application rejected.']);
        }

        return redirect()->back()->with('success', 'Application has been rejected.');
    }

    public function destroy(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Application deleted.']);
        }

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }

    public function clearAll(Request $request)
    {
        // Safe batch delete — respects data integrity
        $count = \App\Models\Application::count();
        \App\Models\Application::query()->delete();

        $this->logAction('Cleared All Applications', null, ['deleted_count' => $count]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => "$count application records have been removed."]);
        }

        return redirect()->back()->with('success', "$count application records cleared successfully.");
    }
}
