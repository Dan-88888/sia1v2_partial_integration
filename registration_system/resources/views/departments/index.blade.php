@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-university me-2" style="color:var(--navy);"></i> Academic Departments</h1>
            <p class="page-subtitle">Overview of colleges and available degree programs</p>
        </div>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('admin.courses.index') }}" class="btn btn-navy">
            <i class="fas fa-cog me-2"></i> Manage Courses
        </a>
        @endif
    </div>
</div>

<div class="row g-4" data-aos="fade-up">
    @forelse($coursesByDept as $dept => $courses)
    <div class="col-lg-6 col-xl-4">
        <div class="glass-card h-100 p-0 overflow-hidden d-flex flex-column">
            <!-- Department Header -->
            <div class="p-4" style="background: var(--navy); color: white;">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="stat-icon gold" style="width: 40px; height: 40px; font-size: 1rem; background: rgba(255,215,0,0.15);">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h5 class="mb-0 fw-bold" style="font-size: 0.95rem; line-height: 1.4;">{{ $dept }}</h5>
                </div>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <span class="badge" style="background: rgba(255,255,255,0.15); font-size: 0.75rem;">
                        {{ $courses->count() }} Program{{ $courses->count() > 1 ? 's' : '' }}
                    </span>
                    @if($courses->first()->campus)
                    <span class="badge" style="background: rgba(255,215,0,0.2); color: #ffd700; font-size: 0.7rem;">
                        <i class="fas fa-map-marker-alt me-1"></i>{{ $courses->first()->campus }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Program List -->
            <div class="p-4 flex-grow-1">
                <div class="d-flex flex-column gap-2">
                    @foreach($courses as $course)
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #edf2f7;">
                        <div style="width: 32px; height: 32px; background: var(--navy); border-radius: 8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <i class="fas fa-book-open" style="color:#ffd700; font-size:0.75rem;"></i>
                        </div>
                        <div class="fw-semibold text-dark" style="font-size: 0.85rem; line-height: 1.4;">
                            {{ $course->course_name }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="px-4 pb-4 pt-0 mt-auto">
                <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    <span>Admission: Open</span>
                    <i class="fas fa-arrow-right text-gold"></i>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="glass-card text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <h4>No Departments Found</h4>
            <p class="text-muted">Academic programs have not been configured yet.</p>
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.courses.create') }}" class="btn btn-navy mt-2">
                <i class="fas fa-plus me-2"></i> Add First Course
            </a>
            @endif
        </div>
    </div>
    @endforelse
</div>
@endsection
