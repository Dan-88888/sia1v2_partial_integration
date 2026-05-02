@extends('layouts.app')

@section('content')
<div class="section-header" data-aos="fade-down">
    <div>
        <h2 class="page-title">
            <i class="fas fa-chalkboard-teacher me-2" style="color:var(--gold);"></i>
            Teaching Load
        </h2>
        <p class="page-subtitle">All assigned sections for <strong>Prof. {{ Auth::user()->name }}</strong></p>
    </div>
</div>

<div class="glass-card p-4" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table-modern w-100">
            <thead>
                <tr>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>Schedule</th>
                    <th>Room</th>
                    <th>Enrolled</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sections as $section)
                <tr>
                    <td class="fw-bold">{{ $section->section_name }}</td>
                    <td>
                        <div class="fw-bold">{{ $section->subject->subject_code ?? '—' }}</div>
                        <small class="text-muted">{{ $section->subject->subject_name ?? '—' }}</small>
                    </td>
                    <td>
                        <div class="small fw-bold"><i class="far fa-calendar-alt text-primary me-1"></i> {{ $section->day }}</div>
                        <div class="small text-muted">
                            <i class="far fa-clock me-1"></i>
                            {{ date('h:i A', strtotime($section->start_time)) }} – {{ date('h:i A', strtotime($section->end_time)) }}
                        </div>
                    </td>
                    <td>{{ $section->room->name ?? 'TBA' }}</td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            {{ $section->enrollments()->where('status', 'enrolled')->count() }} Students
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('teacher.sections.attendance', $section) }}" class="btn btn-sm btn-outline-navy" title="Attendance">
                                <i class="fas fa-user-check"></i>
                            </a>
                            <a href="{{ route('teacher.sections.grades', $section) }}" class="btn btn-sm btn-navy" title="Grades">
                                <i class="fas fa-star-half-alt"></i>
                            </a>
                            <a href="{{ route('teacher.sections.roster.download', $section) }}" class="btn btn-sm btn-light border" title="Download Roster">
                                <i class="fas fa-file-download"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-2x mb-3 d-block opacity-50"></i>
                        No sections assigned yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
