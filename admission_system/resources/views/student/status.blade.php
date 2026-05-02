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
        .timeline-dot {
            @apply absolute left-2 w-4 h-4 rounded-full border-2 border-white;
        }
        .timeline-line {
            @apply absolute left-4 top-0 bottom-0 w-0.5;
        }
        .summary-card {
            @apply p-4 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition;
        }
        .action-btn {
            @apply px-5 py-3 rounded-xl font-bold transition-all duration-300 flex items-center shadow-md transform hover:-translate-y-1 hover:shadow-xl text-sm;
        }
        .logo-container { width: 44px; height: 44px; }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header with PSU Branding -->
    <div class="psu-blue-bg shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center space-x-3">
                <!-- PSU Logo Image -->
                <div class="logo-container flex-shrink-0">
                    <img src="{{ asset('images/PSU_LOGO.png') }}"
                         alt="PSU Logo"
                         onerror="this.onerror=null; this.parentElement.style.backgroundColor='#FFD700'; this.parentElement.innerHTML='<span class=\'text-2xl font-bold text-[#000035]\'>PSU</span>';">
                </div>
                <div>
                    <h1 class="text-lg md:text-2xl font-bold text-white">Partido State University</h1>
                    <p class="text-yellow-300 text-xs md:text-sm">Application Status Tracker</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <!-- Navigation Breadcrumb -->
            <div class="mb-6 flex items-center text-sm">
                <a href="#" onclick="window.parent.postMessage({action:'navigate-home'}, '*'); return false;" class="text-gray-500 hover:text-[#000035] transition">Home</a>
                <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#000035] font-semibold">Application Status</span>
            </div>
            
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
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
                                <h1 class="text-lg md:text-2xl font-bold text-[#000035]">Application Status</h1>
                                <p class="text-[#000035] text-xs md:text-sm">Track your application progress</p>
                            </div>
                        </div>
                        <div class="flex items-center">
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
                
                <div class="p-4 md:p-8">
                    <!-- Applicant Information -->
                    <div class="text-center mb-6 md:mb-8">
                        <div class="w-16 h-16 md:w-24 md:h-24 bg-[#000035] rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl md:text-4xl font-bold text-yellow-400">
                                {{ substr($application->firstname ?? '?', 0, 1) }}{{ substr($application->lastname ?? '', 0, 1) }}
                            </span>
                        </div>
                        <h2 class="text-xl md:text-3xl font-bold text-[#000035]">{{ $application->full_name }}</h2>
                        <div class="flex items-center justify-center mt-2 space-x-4 flex-wrap gap-2">
                            <p class="text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ $application->gmail_account }}
                            </p>
                            <p class="text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $application->contact_number }}
                            </p>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Application ID: #{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    
                    <!-- Status Timeline Card -->
                    <div class="mb-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                        <h3 class="text-lg font-bold text-[#000035] mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Application Timeline
                        </h3>
                        
                        <div class="relative">
                            <!-- Timeline Line -->
                            <div class="timeline-line bg-gray-300"></div>
                            
                            <!-- Step 1: Submitted -->
                            <div class="relative pl-12 pb-8">
                                <div class="timeline-dot bg-green-500 ring-4 ring-green-100"></div>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <div class="flex items-center mb-2">
                                        <span class="font-bold text-gray-800">Application Submitted</span>
                                        <span class="ml-auto text-sm text-green-600 font-semibold">✓ Completed</span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $application->created_at->format('F d, Y') }} at {{ $application->created_at->format('h:i A') }}</p>
                                </div>
                            </div>
                            
                            <!-- Step 2: Under Review -->
                            <div class="relative pl-12 pb-8">
                                <div class="timeline-dot 
                                    @if($application->status != 'Pending') bg-green-500 ring-4 ring-green-100
                                    @else bg-yellow-500 ring-4 ring-yellow-100
                                    @endif">
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <div class="flex items-center mb-2">
                                        <span class="font-bold text-gray-800">Under Review</span>
                                        <span class="ml-auto text-sm 
                                            @if($application->status != 'Pending') text-green-600
                                            @else text-yellow-600
                                            @endif font-semibold">
                                            @if($application->status != 'Pending') ✓ Completed
                                            @else ● In Progress
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">Your application is being processed by the admissions committee</p>
                                    @if($application->status == 'Pending')
                                    <p class="text-xs text-gray-500 mt-2">Estimated review time: 3-5 business days</p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Step 3: Final Decision -->
                            <div class="relative pl-12">
                                <div class="timeline-dot 
                                    @if($application->status == 'Approved') bg-green-500 ring-4 ring-green-100
                                    @elseif($application->status == 'Rejected') bg-red-500 ring-4 ring-red-100
                                    @elseif($application->status == 'Waitlisted') bg-blue-500 ring-4 ring-blue-100
                                    @else bg-gray-400 ring-4 ring-gray-100
                                    @endif">
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <div class="flex items-center mb-2">
                                        <span class="font-bold text-gray-800">Final Decision</span>
                                        <span class="ml-auto text-sm 
                                            @if($application->status == 'Approved') text-green-600
                                            @elseif($application->status == 'Rejected') text-red-600
                                            @elseif($application->status == 'Waitlisted') text-blue-600
                                            @else text-gray-500
                                            @endif font-semibold">
                                            @if($application->status == 'Approved') ✓ Approved
                                            @elseif($application->status == 'Rejected') ✗ Rejected
                                            @elseif($application->status == 'Waitlisted') ⏳ Waitlisted
                                            @else ○ Pending
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        @if($application->status == 'Approved')
                                            <span class="text-green-700">Congratulations! Your application has been approved. You will receive enrollment instructions via email.</span>
                                        @elseif($application->status == 'Rejected')
                                            <span class="text-red-700">We regret to inform you that your application was not approved for this academic year.</span>
                                        @elseif($application->status == 'Waitlisted')
                                            <span class="text-blue-700">Your application has been waitlisted. We will contact you if a slot becomes available.</span>
                                        @else
                                            Awaiting final decision from the admissions committee.
                                        @endif
                                    </p>
                                    @if($application->status == 'Approved')
                                    <div class="mt-3 p-2 bg-green-50 rounded-lg">
                                        <p class="text-xs text-green-800 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            Next steps: Enrollment procedures will be sent to your email within 3-5 business days.
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Summary Grid -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="summary-card">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <p class="text-sm font-medium text-gray-500">Campus</p>
                            </div>
                            <p class="text-base md:text-lg font-bold text-[#000035]">{{ $application->campus }}{{ str_contains(strtolower($application->campus ?? ''), 'campus') ? '' : ' Campus' }}</p>
                        </div>
                        <div class="summary-card">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                </svg>
                                <p class="text-sm font-medium text-gray-500">Course</p>
                            </div>
                            <p class="text-base md:text-lg font-bold text-[#000035]">{{ $application->course }}</p>
                            <p class="text-xs text-gray-500">{{ $application->college }}</p>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="summary-card">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <p class="text-sm font-medium text-gray-500">Student Type</p>
                            </div>
                            <p class="text-base md:text-lg font-bold text-[#000035]">{{ $application->student_type }}</p>
                        </div>
                        <div class="summary-card">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm font-medium text-gray-500">Last Updated</p>
                            </div>
                            <p class="text-base md:text-lg font-bold text-[#000035]">{{ $application->updated_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $application->updated_at->format('h:i A') }}</p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-wrap justify-center gap-4">
                        @if($application->status == 'Pending')
                            <a href="{{ route('student.edit', $application->id) }}" 
                               class="action-btn bg-[#000035] text-white hover:bg-opacity-90">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Application
                            </a>
                        @endif
                        
                        <a href="{{ route('student.review', $application->id) }}" 
                           class="action-btn bg-yellow-500 text-[#000035] hover:bg-yellow-400">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View Full Details
                        </a>
                        
                        <a href="#"
                           onclick="window.parent.postMessage({action:'navigate-home'}, '*'); return false;"
                           class="action-btn bg-gray-600 text-white hover:bg-gray-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="mt-6 bg-blue-50 border-l-4 border-[#000035] p-4 rounded-r-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-[#000035] mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="font-bold text-[#000035] mb-1">Need Assistance?</h4>
                        <p class="text-sm text-gray-600">
                            If you have questions about your application status, please contact the Office of Admissions at 
                            <span class="font-semibold">admissions@psu.edu.ph</span> or call <span class="font-semibold">(054) 123-4567</span>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>