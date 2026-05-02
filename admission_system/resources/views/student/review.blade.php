<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .psu-blue-bg {
            background: linear-gradient(135deg, #000035 0%, #00004d 100%);
        }
        .psu-gold-bg {
            background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%);
        }
        .psu-gold-text {
            color: #FFD700;
        }
        .psu-blue-text {
            color: #000035;
        }
        .status-badge {
            @apply flex items-center px-4 py-2 rounded-full text-sm font-bold shadow-sm border transition-all duration-300;
        }
        .status-pending {
            background-color: rgba(254, 240, 138, 0.4);
            color: #854d0e;
            border-color: rgba(254, 240, 138, 1);
        }
        .status-approved {
            background-color: rgba(187, 247, 208, 0.4);
            color: #166534;
            border-color: rgba(187, 247, 208, 1);
        }
        .status-rejected {
            background-color: rgba(254, 202, 202, 0.4);
            color: #991b1b;
            border-color: rgba(254, 202, 202, 1);
        }
        .status-waitlisted {
            background-color: rgba(191, 219, 254, 0.4);
            color: #1e40af;
            border-color: rgba(191, 219, 254, 1);
        }
        .status-dot {
            @apply w-2.5 h-2.5 rounded-full mr-2;
        }
        .pulse {
            animation: pulse-animation 2s infinite;
        }
        @keyframes pulse-animation {
            0% { box-shadow: 0 0 0 0px rgba(234, 179, 8, 0.4); }
            100% { box-shadow: 0 0 0 10px rgba(234, 179, 8, 0); }
        }
        .info-card {
            @apply bg-gray-50 rounded-xl p-6 border border-gray-200 hover:shadow-md transition;
        }
        .info-label {
            @apply text-sm font-medium text-gray-500 mb-1;
        }
        .info-value {
            @apply text-base font-semibold text-gray-800;
        }
        .action-btn {
            @apply px-5 py-3 rounded-xl font-bold transition-all duration-300 flex items-center shadow-md transform hover:-translate-y-1 hover:shadow-xl text-sm;
        }
        .logo-container { width: 44px; height: 44px; }
        .id-reminder {
            background: linear-gradient(135deg, #FFD700 15%, #FDB931 100%);
            border-radius: 12px;
            padding: 10px 14px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }
        .id-number {
            font-family: monospace;
            font-size: clamp(14px, 3vw, 20px);
            font-weight: bold;
            letter-spacing: 2px;
            background: #000035;
            padding: 4px 12px;
            border-radius: 8px;
            color: #FFD700;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header with PSU Branding -->
    <div class="psu-blue-bg shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center space-x-3">
                    <!-- PSU Logo Image -->
                    <div class="logo-container flex-shrink-0">
                        <img src="{{ asset('images/PSU_LOGO.png') }}"
                             alt="PSU Logo"
                             onerror="this.onerror=null; this.parentElement.style.backgroundColor='#FFD700'; this.parentElement.innerHTML='<span class=\'text-2xl font-bold text-[#000035]\'>PSU</span>';">
                    </div>
                    <div>
                        <h1 class="text-lg md:text-2xl font-bold text-white">Partido State University</h1>
                        <p class="text-yellow-300 text-xs md:text-sm">Application Review</p>
                    </div>
                </div>
                <div class="hidden sm:block text-right">
                    <p class="text-yellow-300 text-sm">Academic Year 2024-2025</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            <!-- Navigation Breadcrumb -->
            <div class="mb-6 flex items-center text-sm">
                <a href="#" onclick="window.parent.postMessage({action:'navigate-home'}, '*'); return false;" class="text-gray-500 hover:text-[#000035] transition">Home</a>
                <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('student.apply') }}" class="text-gray-500 hover:text-[#000035] transition">Application Form</a>
                <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#000035] font-semibold">Review Application</span>
            </div>
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Main Content Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-t-4 border-yellow-400">
                <!-- Card Header with Status -->
                <div class="psu-gold-bg px-4 md:px-8 py-4">
                    <div class="flex flex-wrap justify-between items-start gap-3">
                        <div class="flex items-center">
                            <div class="bg-[#000035] p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg md:text-2xl font-bold text-[#000035]">Application Review</h2>
                                <p class="text-[#000035] text-xs md:text-sm">Please review your information carefully</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-[#000035]">Status:</span>
                            <span class="status-badge 
                                @if($application->status == 'Pending') status-pending
                                @elseif($application->status == 'Approved') status-approved
                                @elseif($application->status == 'Rejected') status-rejected
                                @else status-waitlisted
                                @endif">
                                <span class="status-dot 
                                    @if($application->status == 'Pending') bg-yellow-500 pulse
                                    @elseif($application->status == 'Approved') bg-green-500
                                    @elseif($application->status == 'Rejected') bg-red-500
                                    @else bg-blue-500
                                    @endif"></span>
                                {{ $application->status }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Applicant Summary with Application ID -->
                <div class="p-4 md:p-8 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-yellow-50">
                    <div class="flex items-start justify-between flex-wrap gap-4">
                        <div class="flex items-center min-w-0">
                            <div class="w-12 h-12 md:w-20 md:h-20 bg-[#000035] rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-lg md:text-3xl font-bold text-yellow-400">
                                    {{ substr($application->firstname, 0, 1) }}{{ substr($application->lastname, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-3 md:ml-6 min-w-0">
                                <h3 class="text-base md:text-2xl font-bold text-[#000035] leading-tight">{{ $application->firstname }} {{ $application->middlename }} {{ $application->lastname }}</h3>
                                <p class="text-gray-600 flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $application->gmail_account }}
                                </p>
                                <p class="text-gray-600 flex items-center text-sm">
                                    <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $application->contact_number }}
                                </p>
                            </div>
                        </div>
                        <!-- Application ID Badge -->
                        <div class="id-reminder">
                            <svg class="w-6 h-6 text-[#000035]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-[#000035] font-medium">Application ID</p>
                                <p class="id-number">#{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- Reminder Note -->
                    <div class="mt-4 p-3 bg-yellow-100 rounded-lg border border-yellow-300">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-yellow-800">
                                <strong>Important:</strong> Please save your Application ID <span class="font-mono font-bold">#{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</span> for tracking your application status.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 md:p-8">
                    <div class="grid md:grid-cols-2 gap-6 md:gap-8">
                        <!-- Left Column -->
                        <div>
                            <!-- Personal Information -->
                            <div class="info-card mb-6">
                                <h3 class="text-lg font-bold text-[#000035] mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Personal Information
                                </h3>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="info-label">Last Name</p>
                                            <p class="info-value">{{ $application->lastname }}</p>
                                        </div>
                                        <div>
                                            <p class="info-label">First Name</p>
                                            <p class="info-value">{{ $application->firstname }}</p>
                                        </div>
                                        <div>
                                            <p class="info-label">Middle Name</p>
                                            <p class="info-value">{{ $application->middlename ?: 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="info-label">Name Extender</p>
                                            <p class="info-value">{{ $application->name_extender ?: 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="info-label">Age</p>
                                            <p class="info-value">{{ $application->age }}</p>
                                        </div>
                                        <div>
                                            <p class="info-label">Sex</p>
                                            <p class="info-value">{{ $application->sex }}</p>
                                        </div>
                                        <div>
                                            <p class="info-label">Civil Status</p>
                                            <p class="info-value">{{ $application->civil_status }}</p>
                                        </div>
                                        <div>
                                            <p class="info-label">Date of Birth</p>
                                            <p class="info-value">{{ $application->date_of_birth ? date('F d, Y', strtotime($application->date_of_birth)) : 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="info-label">Place of Birth</p>
                                        <p class="info-value">{{ $application->birth_place }}</p>
                                    </div>
                                    <div>
                                        <p class="info-label">Temporary Address</p>
                                        <p class="info-value">{{ $application->temporary_address }}</p>
                                    </div>
                                    <div>
                                        <p class="info-label">Permanent Address</p>
                                        <p class="info-value">{{ $application->permanent_address }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Guardian Information -->
                            <div class="info-card">
                                <h3 class="text-lg font-bold text-[#000035] mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                    </svg>
                                    Guardian Information
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <p class="info-label">Guardian Name</p>
                                        <p class="info-value">{{ $application->guardian_name }}</p>
                                    </div>
                                    <div>
                                        <p class="info-label">Relationship</p>
                                        <p class="info-value">{{ $application->guardian_relationship }}</p>
                                    </div>
                                    <div>
                                        <p class="info-label">Guardian Phone</p>
                                        <p class="info-value">{{ $application->guardian_phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div>
                            <!-- Academic Information -->
                            <div class="info-card mb-6">
                                <h3 class="text-lg font-bold text-[#000035] mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                </svg>
                                Academic Information
                            </h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="info-label">Student Type</p>
                                        <p class="info-value">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                                {{ $application->student_type }}
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="info-label">Campus</p>
                                        <p class="info-value">{{ $application->campus }}{{ str_contains(strtolower($application->campus ?? ''), 'campus') ? '' : ' Campus' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="info-label">College</p>
                                    <p class="info-value">{{ $application->college }}</p>
                                </div>
                                <div>
                                    <p class="info-label">Course</p>
                                    <p class="info-value text-lg font-bold text-[#000035]">{{ $application->course }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between items-center flex-wrap gap-4">
                        <a href="#"
                           onclick="window.parent.postMessage({action:'navigate-home'}, '*'); return false;"
                           class="text-gray-500 hover:text-[#000035] transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Back to Home
                        </a>
                        <div class="flex flex-wrap gap-3">
                            @if($application->status == 'Pending')
                                <a href="{{ route('student.edit', $application->id) }}" 
                                   class="action-btn bg-[#000035] text-white hover:bg-opacity-90">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit Application
                                </a>
                            @endif
                            
                            <a href="{{ route('student.status', $application->id) }}" 
                               class="action-btn bg-yellow-500 text-[#000035] hover:bg-yellow-400">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Check Status
                            </a>
                            
                            <button onclick="window.print()" 
                                    class="action-btn bg-gray-600 text-white hover:bg-gray-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Note -->
            <div class="mt-6 bg-blue-50 border-l-4 border-[#000035] p-4 rounded-r-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-[#000035] mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="font-bold text-[#000035] mb-1">Important Reminder</h4>
                        <p class="text-sm text-gray-600">
                            Please ensure all information is accurate. Once your application is approved, you will no longer be able to edit it. 
                            For any concerns, contact the Office of Admissions at <span class="font-semibold">admissions@psu.edu.ph</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>