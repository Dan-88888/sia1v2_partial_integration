@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-graduation-cap me-2" style="color:var(--navy);"></i> Add New Course</h1>
            <p class="page-subtitle">Add a degree program to the course catalog</p>
        </div>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-navy">
            <i class="fas fa-arrow-left me-2"></i> Back to Courses
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert-modern alert-danger alert mb-4" data-aos="fade-up">
        <i class="fas fa-exclamation-circle me-2"></i>
        <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
    </div>
@endif

<!-- Main Form Card -->
<div class="glass-card" style="max-width: 700px; margin: 0 auto;" data-aos="fade-up">
    <form action="{{ route('admin.courses.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-12">
                <div class="form-modern-group">
                    <label class="form-modern-label">Course / Program Name <span class="text-danger">*</span></label>
                    <input type="text" name="course_name" class="form-modern-input" value="{{ old('course_name') }}"
                           placeholder="e.g. Bachelor of Science in Information Technology" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-modern-group">
                    <label class="form-modern-label">Campus <span class="text-danger">*</span></label>
                    <select id="campusSelect" class="form-modern-input" required>
                        <option value="">— Select Campus —</option>
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-modern-group">
                    <label class="form-modern-label">College <span class="text-danger">*</span></label>
                    <select name="college_id" id="collegeSelect" class="form-modern-input" required>
                        <option value="">— Select Campus First —</option>
                        @foreach($colleges as $college)
                            <option value="{{ $college->id }}" data-campus="{{ $college->campus_id }}" style="display:none">
                                {{ $college->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-5 d-flex gap-3 justify-content-end">
            <a href="{{ route('admin.courses.index') }}" class="btn btn-navy" style="background:transparent; color:var(--text-secondary); border:1px solid var(--border);">
                Cancel
            </a>
            <button type="submit" class="btn btn-navy">
                <i class="fas fa-save me-2"></i> Save Course
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('campusSelect').addEventListener('change', function () {
    const campusId = this.value;
    const collegeSelect = document.getElementById('collegeSelect');
    const options = collegeSelect.querySelectorAll('option[data-campus]');
    collegeSelect.value = '';
    options.forEach(opt => {
        opt.style.display = opt.dataset.campus === campusId ? '' : 'none';
    });
    collegeSelect.querySelector('option[value=""]').textContent = campusId ? '— Select College —' : '— Select Campus First —';
});
</script>
@endsection
