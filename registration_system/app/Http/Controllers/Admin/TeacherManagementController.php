<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('teacher_id', 'like', "%$search%")
                  ->orWhere('college', 'like', "%$search%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('campus')) {
            $query->where('campus', $request->campus);
        }

        if ($request->filled('college')) {
            $query->where('college', $request->college);
        }

        $teachers = $query->orderBy('campus')
            ->orderBy('college')
            ->orderBy('id', 'desc')
            ->paginate(50)
            ->withQueryString();

        $campuses = Teacher::select('campus')->whereNotNull('campus')->distinct()->orderBy('campus')->pluck('campus');
        $colleges = Teacher::select('college')->whereNotNull('college')->distinct()->orderBy('college')->pluck('college');

        return view('admin.teachers.index', compact('teachers', 'campuses', 'colleges'));
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        $campuses = \DB::table('campuses')->orderBy('name')->pluck('name');
        $colleges = \DB::table('colleges')->orderBy('name')->pluck('name');
        return view('admin.teachers.edit', compact('teacher', 'campuses', 'colleges'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $teacher->user_id,
            'campus'        => 'nullable|string|max:255',
            'college'       => 'nullable|string|max:255',
            'department_id' => 'nullable|string|max:255',
        ]);

        \DB::transaction(function () use ($request, $teacher) {
            $teacher->user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            $teacher->update([
                'campus'        => $request->campus,
                'college'       => $request->college,
                'department_id' => $request->college ?? $request->department_id,
            ]);
        });

        $this->logAction('Updated Teacher Profile', $teacher);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher profile updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user    = $teacher->user;
        $email   = $user?->email;

        \DB::transaction(function () use ($teacher, $user, $email) {
            $teacher->delete();
            $user?->delete();
            if ($email) {
                \App\Models\Application::where('email', $email)->delete();
            }
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Teacher and associated user account deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Teacher and associated accounts removed successfully.');
    }
}
