@extends('layouts.app')

@section('content')
<div class="section-header" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="fas fa-user-edit me-2" style="color:var(--navy);"></i> Edit Faculty Profile</h1>
        <p class="page-subtitle">Modify profile information for <strong>{{ $teacher->user->name }}</strong> ({{ $teacher->teacher_id }})</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-navy bg-transparent text-secondary border">
            <i class="fas fa-arrow-left me-2"></i> Back to Faculty List
        </a>
    </div>
</div>

<div class="glass-card p-0" style="max-width: 800px; margin: 0 auto; overflow: hidden;" data-aos="fade-up">
    <div class="p-4" style="background: var(--navy); color: white;">
        <h5 class="mb-0 text-center fw-bold">FACULTY PROFILE — {{ strtoupper($teacher->user->name) }}</h5>
    </div>

    <div class="p-5 bg-white">
        @if($errors->any())
            <div class="alert-modern alert-danger alert mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Personal Information -->
                <div class="col-12">
                    <h6 class="fw-bold text-navy mb-0"><i class="fas fa-user me-2"></i>Personal Information</h6>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Full Name</label>
                        <input type="text" name="name" class="form-modern-input" value="{{ old('name', $teacher->user->name) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Email Address</label>
                        <input type="email" name="email" class="form-modern-input" value="{{ old('email', $teacher->user->email) }}" required>
                    </div>
                </div>

                <div class="col-12"><hr class="my-1"></div>

                <!-- Professional Information -->
                <div class="col-12">
                    <h6 class="fw-bold text-navy mb-0"><i class="fas fa-building me-2"></i>Professional Information</h6>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Teacher ID</label>
                        <input type="text" class="form-modern-input bg-light" value="{{ $teacher->teacher_id }}" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Department</label>
                        <input type="text" name="department_id" class="form-modern-input" value="{{ old('department_id', $teacher->department_id) }}" placeholder="e.g. College of Engineering">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Campus</label>
                        <select name="campus" class="form-modern-input">
                            <option value="">— Select Campus —</option>
                            @foreach($campuses as $campus)
                                <option value="{{ $campus }}" {{ old('campus', $teacher->campus) == $campus ? 'selected' : '' }}>
                                    {{ $campus }}
                                </option>
                            @endforeach
                            @if($teacher->campus && !$campuses->contains($teacher->campus))
                                <option value="{{ $teacher->campus }}" selected>{{ $teacher->campus }}</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">College</label>
                        <select name="college" class="form-modern-input">
                            <option value="">— Select College —</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college }}" {{ old('college', $teacher->college) == $college ? 'selected' : '' }}>
                                    {{ $college }}
                                </option>
                            @endforeach
                            @if($teacher->college && !$colleges->contains($teacher->college))
                                <option value="{{ $teacher->college }}" selected>{{ $teacher->college }}</option>
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-12 mt-4">
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-navy bg-transparent text-secondary border">
                            Cancel Changes
                        </a>
                        <button type="submit" class="btn btn-navy">
                            <i class="fas fa-save me-2"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
