<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\StudentApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\ApplicationSubmitted;

class StudentController extends Controller
{
    public function checkDuplicate(Request $request)
    {
        $conflicts = [];

        if ($request->filled('gmail_account')) {
            $gmail = $request->gmail_account;
            if (!str_contains($gmail, '@')) {
                $gmail .= '@gmail.com';
            }
            $exists = StudentApplication::where('gmail_account', $gmail)->exists();
            if ($exists) {
                $conflicts['gmail_account'] = 'This Gmail address is already registered. Please use a different Gmail or track your existing application using your Application ID.';
            }
        }

        if ($request->filled('firstname') && $request->filled('lastname')) {
            $exists = StudentApplication::whereRaw('LOWER(firstname) = ?', [strtolower($request->firstname)])
                ->whereRaw('LOWER(lastname) = ?', [strtolower($request->lastname)])
                ->exists();
            if ($exists) {
                $conflicts['name'] = 'An application with this name already exists. If this is your application, use Track Application to check its status.';
            }
        }

        return response()->json(['conflicts' => $conflicts]);
    }

    public function showForm()
    {
        $campuses = Campus::with('colleges.courses')->get();
        return view('student.admission-form', compact('campuses'));
    }

    public function submitApplication(Request $request)
    {
        if ($request->filled('gmail_account') && !str_contains($request->gmail_account, '@')) {
            $request->merge(['gmail_account' => $request->gmail_account . '@gmail.com']);
        }

        $validator = Validator::make($request->all(), array_merge(
            $this->applicationRules(),
            [
                'terms' => 'nullable',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'birth_certificate' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048',
                'report_card' => 'nullable|mimes:pdf,jpeg,png,jpg|max:2048',
            ]
        ), [
            'gmail_account.unique' => 'This Gmail address is already registered. Please use a different Gmail or track your existing application using your Application ID.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('documents/photos', 'public') : null;
        $birthCertPath = $request->hasFile('birth_certificate') ? $request->file('birth_certificate')->store('documents/birth_certificates', 'public') : null;
        $reportCardPath = $request->hasFile('report_card') ? $request->file('report_card')->store('documents/report_cards', 'public') : null;

        $gmailInput = $request->gmail_account;
        $gmailFull = ($gmailInput && !str_contains($gmailInput, '@')) ? $gmailInput . '@gmail.com' : $gmailInput;

        $application = StudentApplication::create([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'name_extender' => $request->name_extender,
            'age' => $request->age,
            'sex' => $request->sex,
            'civil_status' => $request->civil_status,
            'date_of_birth' => $request->date_of_birth,
            'birth_place' => $request->birth_place,
            'contact_number' => $request->contact_number,
            'gmail_account' => $gmailFull,
            'temporary_address' => $request->temporary_address,
            'permanent_address' => $request->permanent_address,
            'guardian_name' => $request->guardian_name,
            'guardian_relationship' => $request->guardian_relationship,
            'guardian_phone' => $request->guardian_phone,
            'student_type' => $request->student_type,
            'campus' => $request->campus,
            'college' => $request->college,
            'course' => $request->course,
            'photo_path' => $photoPath,
            'birth_certificate_path' => $birthCertPath,
            'report_card_path' => $reportCardPath,
            'status' => 'Pending',
        ]);

        $this->grantOwnership($request, $application->id);

        try {
            Mail::to($application->gmail_account)->send(new ApplicationSubmitted($application));
        } catch (\Exception $e) {
            \Log::error('Mail failed: ' . $e->getMessage());
        }

        return redirect()->route('student.review', $application->id)
            ->with('success', 'Application submitted successfully! A confirmation email has been sent to your Gmail.');
    }

    public function reviewApplication(Request $request, $id)
    {
        $application = StudentApplication::findOrFail($id);
        $this->authorizeOwnership($request, (int) $id);
        return view('student.review', compact('application'));
    }

    public function editApplication(Request $request, $id)
    {
        $application = StudentApplication::findOrFail($id);
        $this->authorizeOwnership($request, (int) $id);

        if ($application->status !== 'Pending') {
            return redirect()->route('student.status', $id)
                ->with('error', 'Cannot edit application that is already ' . $application->status);
        }

        $campuses = Campus::with('colleges.courses')->get();
        return view('student.edit-form', compact('application', 'campuses'));
    }

    public function updateApplication(Request $request, $id)
    {
        $application = StudentApplication::findOrFail($id);
        $this->authorizeOwnership($request, (int) $id);

        if ($application->status !== 'Pending') {
            return redirect()->route('student.status', $id)
                ->with('error', 'Cannot edit application that is already ' . $application->status);
        }

        if ($request->filled('gmail_account') && !str_contains($request->gmail_account, '@')) {
            $request->merge(['gmail_account' => $request->gmail_account . '@gmail.com']);
        }

        $validator = Validator::make($request->all(), $this->applicationRules((int) $id));

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $gmailInput = $request->gmail_account;
        $gmailFull = ($gmailInput && !str_contains($gmailInput, '@')) ? $gmailInput . '@gmail.com' : $gmailInput;

        $application->update([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'name_extender' => $request->name_extender,
            'age' => $request->age,
            'sex' => $request->sex,
            'civil_status' => $request->civil_status,
            'date_of_birth' => $request->date_of_birth,
            'birth_place' => $request->birth_place,
            'contact_number' => $request->contact_number,
            'gmail_account' => $gmailFull,
            'temporary_address' => $request->temporary_address,
            'permanent_address' => $request->permanent_address,
            'guardian_name' => $request->guardian_name,
            'guardian_relationship' => $request->guardian_relationship,
            'guardian_phone' => $request->guardian_phone,
            'student_type' => $request->student_type,
            'campus' => $request->campus,
            'college' => $request->college,
            'course' => $request->course,
        ]);

        return redirect()->route('student.review', $id)
            ->with('success', 'Application updated successfully!');
    }

    public function checkStatus(Request $request, $id)
    {
        $application = StudentApplication::findOrFail($id);
        $this->authorizeOwnership($request, (int) $id);
        return view('student.status', compact('application'));
    }

    public function showTrackPage()
    {
        return view('student.track');
    }

    public function lookupApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $appId = (int) $request->application_id;

        $application = StudentApplication::find($appId);

        if ($application) {
            $this->grantOwnership($request, $application->id);
            return redirect()->route('student.status', $application->id)
                ->with('success', 'Application found!');
        }

        return redirect()->back()
            ->with('error', 'No application found. Please check your Application ID and try again.')
            ->withInput();
    }

    private function applicationRules(int $excludeId = null): array
    {
        $emailRule = [
            'nullable',
            'email',
            Rule::unique('adm_applications', 'gmail_account')
                ->whereNull('deleted_at')
                ->when($excludeId, fn ($rule) => $rule->ignore($excludeId)),
        ];

        return [
            'firstname' => 'nullable|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'name_extender' => 'nullable|string|max:10',
            'age' => 'nullable|integer|min:15|max:100',
            'sex' => 'nullable|in:Male,Female',
            'civil_status' => 'nullable|in:Single,Married,Widowed,Divorced,Separated',
            'date_of_birth' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'contact_number' => ['nullable', 'regex:/^[0-9\+\-\s\(\)]{7,20}$/'],
            'gmail_account' => $emailRule,
            'temporary_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|in:Mother,Father,Brother,Sister,Grandmother,Grandfather,Auntie,Uncle,Legal Guardian',
            'guardian_phone' => ['nullable', 'regex:/^[0-9\+\-\s\(\)]{7,20}$/'],
            'student_type' => 'required|in:Regular,Irregular,Transferee',
            'campus' => 'required|string',
            'college' => 'required|string',
            'course' => 'required|string',
        ];
    }

    private function grantOwnership(Request $request, int $id): void
    {
        $owned = $request->session()->get('owned_applications', []);
        if (!in_array($id, $owned)) {
            $owned[] = $id;
            $request->session()->put('owned_applications', $owned);
        }
    }

    private function authorizeOwnership(Request $request, int $id): void
    {
        $owned = array_map('intval', $request->session()->get('owned_applications', []));
        if (!in_array($id, $owned)) {
            abort(403, 'You are not authorized to access this application.');
        }
    }
}
