@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down" style="margin-bottom: 1.5rem;">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-graduation-cap me-2" style="color:var(--navy);"></i> Course Catalog</h1>
            <p class="page-subtitle">Manage degree programs and campus distributions</p>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-navy btn-sm px-4">
            <i class="fas fa-plus me-1"></i> Add Course
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert-modern alert-success alert mb-4" data-aos="fade-up">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif

<!-- Filters -->
<div class="glass-card mb-4" style="padding: 1rem 1.5rem;" data-aos="fade-up">
    <form method="GET" action="{{ route('admin.courses.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-modern-label" style="font-size: 0.8rem; margin-bottom:2px;">Search</label>
            <input type="text" name="search" class="form-modern-input py-1" style="font-size: 0.9rem;" placeholder="Course name..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label class="form-modern-label" style="font-size: 0.8rem; margin-bottom:2px;">Campus</label>
            <select name="campus" class="form-modern-input py-1" style="font-size: 0.9rem;">
                <option value="">All Campuses</option>
                @foreach($campuses as $campus)
                    <option value="{{ $campus }}" {{ request('campus') == $campus ? 'selected' : '' }}>{{ $campus }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-modern-label" style="font-size: 0.8rem; margin-bottom:2px;">College</label>
            <select name="college" class="form-modern-input py-1" style="font-size: 0.9rem;">
                <option value="">All Colleges</option>
                @foreach($colleges as $college)
                    <option value="{{ $college }}" {{ request('college') == $college ? 'selected' : '' }}>{{ $college }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-navy btn-sm py-1 px-3 w-100">
                <i class="fas fa-search me-1"></i> Search
            </button>
            @if(request()->hasAny(['search', 'campus', 'college']))
                <a href="{{ route('admin.courses.index') }}" class="btn btn-sm border text-secondary px-2 d-flex align-items-center" title="Clear Filters">
                    <i class="fas fa-undo"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Main Table Card -->
<div class="glass-card mb-5" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Course / Program</th>
                    <th>College</th>
                    <th>Campus</th>
                    <th class="text-end" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                <tr>
                    <td class="fw-bold text-navy">{{ $course->course_name }}</td>
                    <td><span class="text-muted small">{{ $course->college }}</span></td>
                    <td><span class="badge bg-light text-primary border">{{ $course->campus }}</span></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-navy btn-sm" style="padding: 4px 8px; font-size: 0.8rem;" title="Edit Course">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-modern btn-sm" style="padding: 4px 8px; font-size: 0.8rem;" title="Delete Course">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                        <span class="text-muted">No courses found.</span>
                    </td>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($courses->hasPages())
    <div class="px-4 py-3 border-top bg-light/30">
        {{ $courses->links() }}
    </div>
    @endif
</div>
@endsection
