<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    private function baseQuery()
    {
        return DB::table('adm_courses')
            ->join('colleges', 'adm_courses.college_id', '=', 'colleges.id')
            ->join('campuses', 'colleges.campus_id', '=', 'campuses.id')
            ->select(
                'adm_courses.id',
                'adm_courses.name as course_name',
                'colleges.id as college_id',
                'colleges.name as college',
                'campuses.id as campus_id',
                'campuses.name as campus'
            );
    }

    public function index(Request $request)
    {
        $query = $this->baseQuery();

        if ($request->filled('search')) {
            $query->where('adm_courses.name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('campus')) {
            $query->where('campuses.name', $request->campus);
        }
        if ($request->filled('college')) {
            $query->where('colleges.name', $request->college);
        }

        $courses = $query
            ->orderBy('campuses.name')
            ->orderBy('colleges.name')
            ->orderBy('adm_courses.name')
            ->paginate(25)
            ->withQueryString();

        $campuses = DB::table('campuses')->orderBy('name')->pluck('name');
        $colleges = DB::table('colleges')->orderBy('name')->pluck('name');

        return view('admin.courses.index', compact('courses', 'campuses', 'colleges'));
    }

    public function create()
    {
        $campuses = DB::table('campuses')->orderBy('name')->get(['id', 'name']);
        $colleges = DB::table('colleges')->orderBy('name')->get(['id', 'name', 'campus_id']);

        return view('admin.courses.create', compact('campuses', 'colleges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'college_id'  => 'required|integer|exists:colleges,id',
        ]);

        DB::table('adm_courses')->insert([
            'name'       => $request->course_name,
            'college_id' => $request->college_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit($id)
    {
        $course = $this->baseQuery()->where('adm_courses.id', $id)->first();
        if (!$course) abort(404);

        $campuses = DB::table('campuses')->orderBy('name')->get(['id', 'name']);
        $colleges = DB::table('colleges')->orderBy('name')->get(['id', 'name', 'campus_id']);

        return view('admin.courses.edit', compact('course', 'campuses', 'colleges'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'college_id'  => 'required|integer|exists:colleges,id',
        ]);

        DB::table('adm_courses')->where('id', $id)->update([
            'name'       => $request->course_name,
            'college_id' => $request->college_id,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy($id)
    {
        DB::table('adm_courses')->where('id', $id)->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
