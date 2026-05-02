<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
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
            @apply px-6 py-3 rounded-xl font-bold transition-all duration-300 flex items-center shadow-md transform hover:-translate-y-1 hover:shadow-xl;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">

        <!-- Mobile Header -->
        <div class="lg:hidden fixed top-0 inset-x-0 z-40 h-14 psu-blue-bg flex items-center px-4 shadow-lg">
            <button id="sidebar-open" type="button" class="text-white p-1.5 mr-3 rounded-lg hover:bg-white/10 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="logo-container">
                <img src="{{ asset('images/PSU_LOGO.png') }}" alt="PSU Logo" onerror="this.onerror=null;this.parentElement.innerHTML='<span class=\'font-bold text-[#000035]\'>PSU</span>';">
            </div>
            <span class="text-white font-bold text-sm ml-2">PSU Admin Panel</span>
        </div>

        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" class="hidden fixed inset-0 z-30 bg-black/50 lg:hidden"></div>

        <!-- Sidebar Navigation -->
        @include('partials.admin-sidebar')
        
        <!-- Main Content -->
        <div class="lg:ml-64 pt-14 lg:pt-0 p-4 md:p-8 overflow-y-auto min-h-screen">
            <!-- Header with Navigation -->
            <div class="mb-8 flex flex-wrap justify-between items-start gap-4">
                <div>
                    <div class="flex items-center mb-2">
                        <a href="{{ route('admin.applications') }}" class="text-gray-500 hover:text-[#000035] transition mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <h1 class="text-xl md:text-3xl font-bold text-[#000035]">Application Details</h1>
                    </div>
                    <p class="text-gray-600 ml-9">Viewing application #{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.edit', $application->id) }}"
                       class="bg-[#000035] text-white px-5 py-2.5 rounded-xl hover:bg-opacity-90 transition-all duration-300 transform hover:-translate-y-1 flex items-center font-bold shadow-md hover:shadow-xl text-sm md:text-base">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Application
                    </a>
                </div>
            </div>
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mb-6 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('info') }}
                </div>
            @endif
            
            <!-- Main Content Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-yellow-400">
                <!-- Status Header with Gold Background -->
                <div class="psu-gold-bg px-4 md:px-8 py-4">
                    <div class="flex flex-wrap justify-between items-start gap-3">
                        <div class="flex items-center space-x-4">
                            <div class="bg-[#000035] p-2 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="text-sm text-[#000035] font-medium">Current Status</span>
                                <div class="flex items-center mt-1">
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
                        
                        <!-- Status Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <form action="{{ route('admin.approve', $application->id) }}" method="POST" class="inline"
                                  data-confirm-title="Approve Application"
                                  data-confirm-msg="Approve this application for {{ $application->firstname }} {{ $application->lastname }}?"
                                  data-confirm-color="green">
                                @csrf
                                <button type="button" onclick="showConfirmModal(this.closest('form'))" class="bg-green-600 text-white px-5 py-2.5 rounded-xl hover:bg-green-700 transition-all duration-300 transform hover:-translate-y-1 flex items-center text-sm font-bold shadow-md hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.waitlist', $application->id) }}" method="POST" class="inline"
                                  data-confirm-title="Waitlist Application"
                                  data-confirm-msg="Move this application for {{ $application->firstname }} {{ $application->lastname }} to the waitlist?"
                                  data-confirm-color="blue">
                                @csrf
                                <button type="button" onclick="showConfirmModal(this.closest('form'))" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 transition-all duration-300 transform hover:-translate-y-1 flex items-center text-sm font-bold shadow-md hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Waitlist
                                </button>
                            </form>
                            <form action="{{ route('admin.reject', $application->id) }}" method="POST" class="inline"
                                  data-confirm-title="Reject Application"
                                  data-confirm-msg="Reject this application for {{ $application->firstname }} {{ $application->lastname }}? This will notify the applicant."
                                  data-confirm-color="red">
                                @csrf
                                <button type="button" onclick="showConfirmModal(this.closest('form'))" class="bg-red-600 text-white px-5 py-2.5 rounded-xl hover:bg-red-700 transition-all duration-300 transform hover:-translate-y-1 flex items-center text-sm font-bold shadow-md hover:shadow-xl">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Applicant Summary Card -->
                <div class="p-4 md:p-8 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-yellow-50">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 md:w-20 md:h-20 bg-[#000035] rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-xl md:text-3xl font-bold text-yellow-400">
                                    {{ substr($application->firstname, 0, 1) }}{{ substr($application->lastname, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h2 class="text-base md:text-2xl font-bold text-[#000035] leading-tight">{{ $application->firstname }} {{ $application->middlename }} {{ $application->lastname }}</h2>
                                <p class="text-gray-600 flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $application->gmail_account }}
                                </p>
                                <p class="text-gray-600 flex items-center mt-1 text-sm">
                                    <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $application->contact_number }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs md:text-sm text-gray-500">Application ID</p>
                            <p class="text-lg md:text-2xl font-mono font-bold text-[#000035]">#{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Details Grid -->
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
                                    <div>
                                        <p class="info-label">Student Type</p>
                                        <p class="info-value">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                                {{ $application->student_type }}
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="info-label">Preferred Campus</p>
                                        <p class="info-value">{{ $application->campus }}{{ str_contains(strtolower($application->campus ?? ''), 'campus') ? '' : ' Campus' }}</p>
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

                            <!-- Application Timeline -->
                            <div class="info-card">
                                <h3 class="text-lg font-bold text-[#000035] mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    Application Timeline
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">Submitted</p>
                                            <p class="text-sm text-gray-500">{{ $application->created_at->format('F d, Y') }} at {{ $application->created_at->format('h:i A') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">Last Updated</p>
                                            <p class="text-sm text-gray-500">{{ $application->updated_at->format('F d, Y') }} at {{ $application->updated_at->format('h:i A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="mt-8 md:mt-12 bg-gray-50 rounded-xl p-4 md:p-8 border border-gray-200">
                        <h3 class="text-lg md:text-xl font-bold text-[#000035] mb-4 md:mb-6 flex items-center border-b pb-4">
                            <svg class="w-6 h-6 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Submitted Documents
                        </h3>
                        
                        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-8">
                            <!-- Photo -->
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center">
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4">Applicant Photo</p>
                                @if($application->photo_path)
                                    <div class="w-28 h-28 md:w-40 md:h-40 mb-4 rounded-xl overflow-hidden shadow-lg border-4 border-white">
                                        <img src="{{ asset('storage/' . $application->photo_path) }}" class="w-full h-full object-cover">
                                    </div>
                                    <a href="{{ asset('storage/' . $application->photo_path) }}" target="_blank" 
                                       class="inline-flex items-center text-[#000035] hover:text-yellow-600 font-bold text-sm transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        View Full Size
                                    </a>
                                @else
                                    <div class="w-40 h-40 mb-4 rounded-xl bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300">
                                        <span class="text-gray-400 italic text-sm">No Photo Uploaded</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Birth Certificate -->
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center">
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4">Birth Certificate (PSA)</p>
                                @if($application->birth_certificate_path)
                                    <div class="w-20 h-20 mb-6 bg-red-50 rounded-2xl flex items-center justify-center text-red-500">
                                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                        </svg>
                                    </div>
                                    <a href="{{ asset('storage/' . $application->birth_certificate_path) }}" target="_blank" 
                                       class="w-full bg-[#000035] text-white py-3 rounded-lg text-sm font-bold hover:bg-opacity-90 transition text-center shadow-lg">
                                        View PSA Certificate
                                    </a>
                                @else
                                    <p class="text-gray-400 italic text-sm">Not provided</p>
                                @endif
                            </div>

                            <!-- Report Card -->
                            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center">
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4">Report Card (Form 137)</p>
                                @if($application->report_card_path)
                                    <div class="w-20 h-20 mb-6 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500">
                                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                        </svg>
                                    </div>
                                    <a href="{{ asset('storage/' . $application->report_card_path) }}" target="_blank" 
                                       class="w-full bg-[#000035] text-white py-3 rounded-lg text-sm font-bold hover:bg-opacity-90 transition text-center shadow-lg">
                                        View Report Card
                                    </a>
                                @else
                                    <p class="text-gray-400 italic text-sm">Not provided</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-wrap justify-between items-center gap-3">
                        <a href="{{ route('admin.applications') }}"
                           class="text-gray-500 hover:text-[#000035] transition flex items-center text-sm">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Applications List
                        </a>
                        <a href="{{ route('admin.edit', $application->id) }}"
                           class="bg-[#000035] text-white px-5 py-2.5 rounded-lg hover:bg-opacity-90 transition flex items-center text-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Application
                        </a>
                    </div>
                </div>
            </div>

            <!-- Print Button -->
            <div class="mt-6 flex justify-end">
                <button onclick="window.print()" 
                        class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print Application
                </button>
            </div>
        </div>
    </div>

    <script>
    (function() {
        var sidebar = document.getElementById('admin-sidebar');
        var overlay = document.getElementById('sidebar-overlay');
        var openBtn = document.getElementById('sidebar-open');
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
        if (openBtn) openBtn.addEventListener('click', openSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);
    })();
    </script>

<!-- Custom Confirm Modal -->
<div id="confirm-modal" style="display:none;position:fixed;inset:0;z-index:99999;align-items:center;justify-content:center;background:rgba(0,0,0,0.45);backdrop-filter:blur(2px);">
    <div style="background:#fff;border-radius:16px;padding:32px 28px;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.25);animation:modalIn .18s ease;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
            <div id="modal-icon" style="width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            </div>
            <h3 id="modal-title" style="font-size:16px;font-weight:700;color:#111827;margin:0;"></h3>
        </div>
        <p id="modal-msg" style="font-size:14px;color:#6b7280;margin:0 0 24px 52px;line-height:1.6;"></p>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <button onclick="closeConfirmModal()" style="padding:9px 20px;border-radius:8px;border:1.5px solid #d1d5db;background:#fff;color:#374151;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button id="modal-confirm-btn" style="padding:9px 20px;border-radius:8px;border:none;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">Confirm</button>
        </div>
    </div>
</div>
<style>@keyframes modalIn { from { opacity:0;transform:scale(.95) translateY(-8px); } to { opacity:1;transform:none; } }</style>
<script>
    var _pendingForm = null;
    function showConfirmModal(form) {
        _pendingForm = form;
        var colors = { red:'#dc2626', blue:'#2563eb', green:'#16a34a', yellow:'#d97706' };
        var bg = colors[form.dataset.confirmColor] || '#dc2626';
        document.getElementById('modal-title').textContent = form.dataset.confirmTitle || 'Confirm Action';
        document.getElementById('modal-msg').textContent   = form.dataset.confirmMsg   || 'Are you sure?';
        document.getElementById('modal-icon').style.background = bg;
        document.getElementById('modal-confirm-btn').style.background = bg;
        document.getElementById('confirm-modal').style.display = 'flex';
    }
    function closeConfirmModal() {
        document.getElementById('confirm-modal').style.display = 'none';
        _pendingForm = null;
    }
    document.getElementById('modal-confirm-btn').addEventListener('click', function () {
        if (_pendingForm) { var f = _pendingForm; closeConfirmModal(); f.submit(); }
    });
    document.getElementById('confirm-modal').addEventListener('click', function (e) {
        if (e.target === this) closeConfirmModal();
    });
</script>
</body>
</html>