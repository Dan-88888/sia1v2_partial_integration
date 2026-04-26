<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ config('app.name') }}</title>
  <link rel="icon" type="image/png" href="{{ asset('images/nobgParsulogo.png') }}">
  <link rel="stylesheet" href="{{ asset('css/university_portal.css') }}" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  <div id="loginPage" style="display:flex;flex-direction:column;min-height:100vh">
    <div class="login-top-bar"></div>
    <div class="login-content">
      <div class="login-box">

        <div class="login-logo">
          <img src="{{ asset('images/nobgParsulogo.png') }}" alt="University Logo" />
        </div>

        <div class="login-university-name">{{ $app_settings['school_name'] ?? 'Partido State University' }}</div>
        <div class="login-location">Philippines Academic Portal</div>

        @if(session('error'))
          <div style="background:#fee2e2; color:#b91c1c; padding:10px; border-radius:8px; margin-bottom:15px; font-size:0.85rem; border:1px solid #fecaca; text-align:center;">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
          </div>
        @endif

        @if($errors->any())
          <div style="background:#fee2e2; color:#b91c1c; padding:10px; border-radius:8px; margin-bottom:15px; font-size:0.85rem; border:1px solid #fecaca; text-align:center;">
            @foreach($errors->all() as $error)
              <div><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</div>
            @endforeach
          </div>
        @endif

        @php $filteredRole = request('role'); @endphp
        <div class="role-toggle">
          @if(!$filteredRole || $filteredRole === 'student')
          <button class="role-toggle-btn {{ !$filteredRole || $filteredRole === 'student' ? 'active' : '' }}" data-role="student">Student</button>
          @endif
          @if(!$filteredRole || $filteredRole === 'teacher')
          <button class="role-toggle-btn {{ $filteredRole === 'teacher' ? 'active' : '' }}" data-role="teacher">Teacher</button>
          @endif
          @if(!$filteredRole || $filteredRole === 'admin')
          <button class="role-toggle-btn {{ $filteredRole === 'admin' ? 'active' : '' }}" data-role="admin">Admin</button>
          @endif
        </div>

        <div class="login-form">
          <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="role" id="selectedRole" value="{{ $filteredRole ?? 'student' }}">
            <div class="login-field">
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" required autocomplete="off" />
            </div>
            <div class="login-field">
              <label for="password">Password:</label>
              <input type="password" id="password" name="password" required autocomplete="new-password" />
            </div>
          </form>
        </div>

        <button class="login-btn" id="loginBtn">Login</button>
        <a href="{{ route('password.request') }}" class="forgot-btn" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">Forgot Password</a>

        <div class="apply-buttons">
          @if(!$filteredRole || $filteredRole === 'student')
          <button class="apply-btn" id="applyStudentBtn">Apply as New Student</button>
          @endif
          @if(!$filteredRole || $filteredRole === 'teacher')
          <button class="apply-btn" id="applyTeacherBtn">Apply as New Teacher</button>
          @endif
        </div>

        <button class="forgot-btn" id="trackStatusBtn" style="color: var(--navy); border-color: var(--navy); margin-top: 10px;">
          <i class="fas fa-search me-1"></i> Track Application Status
        </button>

        {{-- Demo Credentials Hint --}}
        @if(!$filteredRole || $filteredRole === 'admin')
        <div style="margin-top:16px; padding:12px 16px; background:#f0f4ff; border:1px solid #c7d2fe; border-radius:10px; text-align:center;">
          <p style="font-size:0.78rem; color:#3730a3; margin:0; line-height:1.7;">
            <strong>Demo Credentials (Admin)</strong><br>
            Email: <code style="background:#e0e7ff; padding:1px 5px; border-radius:4px;">admin@university.com</code><br>
            Password: <code style="background:#e0e7ff; padding:1px 5px; border-radius:4px;">admin123</code>
          </p>
        </div>
        @endif

        @if(!$filteredRole || $filteredRole === 'teacher')
        <div style="margin-top:16px; padding:12px 16px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; text-align:center;">
          <p style="font-size:0.78rem; color:#166534; margin:0; line-height:1.7;">
            <strong>Demo Credentials (Teacher)</strong><br>
            Email: <code style="background:#dcfce7; padding:1px 5px; border-radius:4px;">teacher@university.com</code><br>
            Password: <code style="background:#dcfce7; padding:1px 5px; border-radius:4px;">teacher123</code>
          </p>
        </div>
        @endif

        @if($filteredRole === 'student')
        <div style="margin-top:16px; padding:12px 16px; background:#fffbeb; border:1px solid #fde68a; border-radius:10px; text-align:center;">
          <p style="font-size:0.78rem; color:#92400e; margin:0; line-height:1.7;">
            <i class="fas fa-info-circle"></i>
            <strong> New here?</strong><br>
            Click <strong>"Apply as New Student"</strong> below to register.<br>
            Your login credentials will be provided upon approval.
          </p>
        </div>
        @endif

      </div>
    </div>
  </div>

  <!-- Student Application Overlay -->
  <div class="app-form-overlay" id="studentAppOverlay">
    <div class="app-form-box">
      <div class="app-form-title">Student Application Form</div>
      <form id="studentAppForm" autocomplete="off">
        <div class="form-grid">
          <div class="form-group" style="grid-column:1/-1"><label>Name *</label><input name="name" placeholder="e.g. Juan Cruz" required autocomplete="off"></div>
          <div class="form-group"><label>Birthdate</label><input name="birthdate" type="date" autocomplete="off"></div>
          <div class="form-group"><label>Contact Number</label><input name="contact" placeholder="e.g. 09123456789" autocomplete="off"></div>
          <div class="form-group" style="grid-column:1/-1"><label>Address</label><input name="address" placeholder="e.g. Goa, Camarines Sur" autocomplete="off"></div>
          <div class="form-group" style="grid-column:1/-1">
            <label>Personal Email *</label>
            <input name="email" type="email" placeholder="e.g. jcruz@gmail.com" required autocomplete="off">
            <small style="color:#666; display:block; margin-top:4px;">Official university email will be generated upon approval.</small>
          </div>
          <div class="form-group">
            <label>Campus *</label>
            <select id="studentCampus" name="campus" required style="padding:10px; border:1px solid #ddd; width:100%; border-radius:4px;" onchange="loadColleges('student')">
              <option value="">Select Campus</option>
            </select>
          </div>
          <div class="form-group">
            <label>College *</label>
            <select id="studentCollege" name="college" required style="padding:10px; border:1px solid #ddd; width:100%; border-radius:4px;" onchange="loadCourses('student')">
              <option value="">Select College</option>
            </select>
          </div>
          <div class="form-group" style="grid-column: 1 / -1; margin-top: 10px;">
            <label>Course *</label>
            <div class="searchable-select">
              <input type="text" id="studentCourseSearch" placeholder="Search Course..." autocomplete="off" style="width:100%;">
              <input type="hidden" id="studentCourse" name="course" required>
              <div id="courseResults" class="search-results"></div>
            </div>
          </div>
          <div class="form-group" style="grid-column: 1 / -1">
            <label>Year Level *</label>
            <select name="year_level" required style="padding:10px; border:1px solid #ddd; width:100%; border-radius:4px;">
              <option value="">Select Year</option>
              <option value="1">1st Year</option>
              <option value="2">2nd Year</option>
              <option value="3">3rd Year</option>
              <option value="4">4th Year</option>
            </select>
          </div>
          <div class="form-group" style="grid-column:1/-1">
            <label>Admission file (PDF/Images)</label>
            <input type="file" name="documents[]" multiple style="padding:10px; border:1px solid #ddd; width:100%; border-radius:4px;">
            <small style="color:#666">Upload Birth Certificate, Report Card, etc. (Max 5MB) - Optional</small>
          </div>
        </div>
      </form>
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px">
        <button class="btn" style="background:#e0e0e0;color:#333" onclick="closeAppForms()">Cancel</button>
        <button class="btn btn-navy" onclick="submitStudentApp()">Submit Application</button>
      </div>
    </div>
  </div>

  <!-- Teacher Application Overlay -->
  <div class="app-form-overlay" id="teacherAppOverlay">
    <div class="app-form-box">
      <div class="app-form-title">Teacher Application Form</div>
      <form id="teacherAppForm" autocomplete="off">
        <div class="form-grid">
          <div class="form-group" style="grid-column:1/-1"><label>Name *</label><input name="name" placeholder="e.g. Maria Santos" required autocomplete="off"></div>
          <div class="form-group"><label>Birthdate</label><input name="birthdate" type="date" autocomplete="off"></div>
          <div class="form-group"><label>Contact Number</label><input name="contact" placeholder="e.g. 09123456789" autocomplete="off"></div>
          <div class="form-group" style="grid-column:1/-1"><label>Address</label><input name="address" placeholder="e.g. Goa, Camarines Sur" autocomplete="off"></div>
          <div class="form-group" style="grid-column:1/-1"><label>Email *</label><input name="email" type="email" placeholder="e.g. maria@email.com" required autocomplete="off"></div>
          <div class="form-group">
            <label>Campus *</label>
            <select id="teacherCampus" name="campus" required style="padding:10px; border:1px solid #ddd; width:100%; border-radius:4px;" onchange="loadColleges('teacher')">
              <option value="">Select Campus</option>
            </select>
          </div>
          <div class="form-group">
            <label>Department/College *</label>
            <select id="teacherCollege" name="college" required style="padding:10px; border:1px solid #ddd; width:100%; border-radius:4px;">
              <option value="">Select College</option>
            </select>
          </div>
          <div class="form-group" style="grid-column:1/-1">
            <label>Credentials / Resume *</label>
            <input type="file" name="documents[]" multiple required style="padding:10px; border:1px solid #ddd; width:100%; border-radius:4px;">
          </div>
        </div>
      </form>
      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:16px">
        <button class="btn" style="background:#e0e0e0;color:#333" onclick="closeAppForms()">Cancel</button>
        <button class="btn btn-navy" onclick="submitTeacherApp()">Submit Application</button>
      </div>
    </div>
  </div>
  <!-- Track Application Overlay -->
  <div class="app-form-overlay" id="trackStatusOverlay">
    <div class="app-form-box" style="max-width: 400px;">
      <div class="app-form-title">Track Application</div>
      <p style="color:#666; font-size:0.9rem; margin-bottom:20px;">Enter your tracking number to check the status of your application.</p>
      <form action="{{ route('applications.status') }}" method="GET">
        <div class="form-group">
          <label>Tracking Number</label>
          <input name="tracking_number" placeholder="e.g. 26001" required autocomplete="off"                 style="padding:12px; border:2px solid #ddd; width:100%; border-radius:8px; font-size:1.1rem; text-align:center; font-family:monospace;">
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px">
          <button type="button" class="btn" style="background:#e0e0e0;color:#333" onclick="closeAppForms()">Cancel</button>
          <button type="submit" class="btn btn-navy">Check Status</button>
        </div>
      </form>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.querySelectorAll('.role-toggle-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.role-toggle-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('selectedRole').value = btn.dataset.role;
      });
    });

    document.getElementById('loginBtn').addEventListener('click', () => {
      document.getElementById('loginForm').submit();
    });

    function closeAppForms() {
      document.querySelectorAll('.app-form-overlay').forEach(el => el.classList.remove('show'));
      
      // Reset Student Form
      const studentForm = document.getElementById('studentAppForm');
      if (studentForm) {
        studentForm.reset();
        document.getElementById('studentCourse').value = '';
        document.getElementById('studentCourseSearch').value = '';
        document.getElementById('courseResults').classList.remove('show');
        allCourses = [];
      }

      // Reset Teacher Form
      const teacherForm = document.getElementById('teacherAppForm');
      if (teacherForm) {
        teacherForm.reset();
      }
    }

 
    

    const applyStudentBtn = document.getElementById('applyStudentBtn');
    if (applyStudentBtn) {
      applyStudentBtn.addEventListener('click', () => {
        document.getElementById('studentAppOverlay').classList.add('show');
      });
    }

    const applyTeacherBtn = document.getElementById('applyTeacherBtn');
    if (applyTeacherBtn) {
      applyTeacherBtn.addEventListener('click', () => {
        document.getElementById('teacherAppOverlay').classList.add('show');
      });
    }

    document.getElementById('trackStatusBtn').addEventListener('click', () => {
      document.getElementById('trackStatusOverlay').classList.add('show');
    });

    async function submitApp(formId, url) {
      const form = document.getElementById(formId);
      const formData = new FormData(form);

      Swal.fire({
        title: 'Submitting...',
        text: 'Please wait while we process your request.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
      });

      try {
        const res = await fetch(url, {
          method: "POST",
          headers: { 
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest"
          },
          body: formData
        });
        
        const data = await res.json();
        
        if (!res.ok) {
          throw new Error(data.message || 'Submission failed. Please check your data.');
        }
        
        Swal.fire({
          icon: 'success',
          title: 'Application Received!',
          html: `
            <div style="text-align:left; font-size:0.9rem;">
              <p style="margin-bottom:20px;">${data.message}</p>
              
              <div style="padding:15px; background:linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-radius:12px; border:1px solid #bae6fd; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom:15px;">
                <div style="display:flex; align-items:center; margin-bottom:10px;">
                  <div style="background:#0284c7; color:white; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:10px;">
                    <i class="fas fa-envelope" style="font-size:0.8rem;"></i>
                  </div>
                  <strong style="color:#0369a1; font-size:1rem;">Your Official University Email</strong>
                </div>
                <div style="background:white; padding:10px; border-radius:8px; border:1px solid #bae6fd; font-family:monospace; font-size:1rem; color:var(--navy); font-weight:700; text-align:center;">
                  ${data.university_email || 'Generating...'}
                </div>
                <p style="margin:8px 0 0 0; color:#075985; font-size:0.75rem; font-style:italic;">
                  * Use this to log in once your application is Approved.
                </p>
              </div>

              <div style="padding:15px; background:linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border-radius:12px; border:1px solid #fed7aa; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);">
                <div style="display:flex; align-items:center; margin-bottom:10px;">
                  <div style="background:#f97316; color:white; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:10px;">
                    <i class="fas fa-lock" style="font-size:0.8rem;"></i>
                  </div>
                  <strong style="color:#9a3412; font-size:1rem;">Access Credentials</strong>
                </div>
                
                <div style="background:white; padding:10px; border-radius:8px; border:1px solid #ffedd5; font-family:monospace; display:flex; flex-direction:column; gap:8px;">
                  <div>
                    <div style="font-size:0.8rem; color:#9a3412; margin-bottom:2px;">Tracking Number:</div>
                    <div style="font-size:1.2rem; color:var(--navy); font-weight:700; border-bottom: 2px dashed #fed7aa; padding-bottom:5px;">${data.tracking_number}</div>
                  </div>
                  <div>
                    <div style="font-size:0.8rem; color:#9a3412; margin-bottom:2px;">Login Password:</div>
                    <div style="font-size:1.1rem; color:var(--navy); font-weight:700;">${data.temp_password || 'Pending'}</div>
                  </div>
                </div>
              </div>
            </div>
          `,
          confirmButtonColor: 'var(--gold)',
          confirmButtonText: 'Got it, thank you!'
        });
        closeAppForms();
        form.reset();
      } catch (err) {
        Swal.fire({
          icon: 'error',
          title: 'Submission Issue',
          text: err.message
        });
      }
    }

    // --- Dynamic Course Selection ---
    async function loadCampuses() {
      const res = await fetch('{{ url("/api/campuses") }}');
      const campuses = await res.json();
      const selects = ['studentCampus', 'teacherCampus'];
      selects.forEach(sId => {
        const select = document.getElementById(sId);
        campuses.forEach(c => {
          const opt = document.createElement('option');
          opt.value = opt.textContent = c;
          select.appendChild(opt);
        });
      });
    }

    async function loadColleges(type) {
      const campus = document.getElementById(type + 'Campus').value;
      const collegeSelect = document.getElementById(type + 'College');
      collegeSelect.innerHTML = '<option value="">Select College</option>';
      if (type === 'student') {
        document.getElementById('studentCourse').value = '';
        document.getElementById('studentCourseSearch').value = '';
        document.getElementById('courseResults').innerHTML = '';
        document.getElementById('courseResults').classList.remove('show');
        allCourses = [];
      }

      if (!campus) return;

      const res = await fetch(`{{ url("/api/colleges") }}?campus=${encodeURIComponent(campus)}`);
      const colleges = await res.json();
      colleges.forEach(c => {
        const opt = document.createElement('option');
        opt.value = opt.textContent = c;
        collegeSelect.appendChild(opt);
      });
    }

    let allCourses = [];

    async function loadCourses(type) {
      if (type !== 'student') return;
      const campus = document.getElementById('studentCampus').value;
      const college = document.getElementById('studentCollege').value;
      const courseSearchInput = document.getElementById('studentCourseSearch');
      const courseHiddenInput = document.getElementById('studentCourse');
      const courseResults = document.getElementById('courseResults');
      
      courseSearchInput.value = '';
      courseHiddenInput.value = '';
      courseResults.innerHTML = '';
      allCourses = [];

      if (!campus || !college) return;

      try {
        const res = await fetch(`{{ url("/api/courses") }}?campus=${encodeURIComponent(campus)}&college=${encodeURIComponent(college)}`);
        allCourses = await res.json();
        renderCourseResults(allCourses);
      } catch (err) {
        console.error("Failed to load courses", err);
      }
    }

    function renderCourseResults(courses) {
      const resultsDiv = document.getElementById('courseResults');
      resultsDiv.innerHTML = '';
      
      if (courses.length === 0) {
        resultsDiv.innerHTML = '<div class="search-item no-results">No courses found</div>';
      } else {
        courses.forEach(c => {
          const div = document.createElement('div');
          div.className = 'search-item';
          div.textContent = c.course_name;
          div.onclick = () => selectCourse(c.course_name, c.course_code);
          resultsDiv.appendChild(div);
        });
      }
    }

    function selectCourse(name, code) {
      document.getElementById('studentCourseSearch').value = name;
      document.getElementById('studentCourse').value = code;
      document.getElementById('courseResults').classList.remove('show');
    }

    document.getElementById('studentCourseSearch').addEventListener('focus', () => {
      if (allCourses.length > 0) {
        document.getElementById('courseResults').classList.add('show');
      }
    });

    document.getElementById('studentCourseSearch').addEventListener('input', (e) => {
      const term = e.target.value.toLowerCase();
      const filtered = allCourses.filter(c => c.course_name.toLowerCase().includes(term));
      renderCourseResults(filtered);
      document.getElementById('courseResults').classList.add('show');
    });

    // Close results when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.searchable-select')) {
        document.getElementById('courseResults').classList.remove('show');
      }
    });

    // Initialize Campuses on load
    loadCampuses();

    function submitStudentApp() { 
      const form = document.getElementById('studentAppForm');
      if (!form.checkValidity()) {
        Swal.fire({
          icon: 'warning',
          title: 'Incomplete Information',
          text: 'Please fill in all required fields marked with * before submitting.',
          confirmButtonColor: 'var(--navy)'
        });
        return;
      }
      submitApp('studentAppForm', "{{ url('/applications/submit') }}?type=student");
    }
    
    function submitTeacherApp() { 
      const form = document.getElementById('teacherAppForm');
      if (!form.checkValidity()) {
        Swal.fire({
          icon: 'warning',
          title: 'Incomplete Information',
          text: 'Please fill in all required fields marked with * before submitting.',
          confirmButtonColor: 'var(--navy)'
        });
        return;
      }
      submitApp('teacherAppForm', "{{ url('/applications/submit') }}?type=teacher");
    }
  </script>
</body>
</html>
