<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .stat-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 51, 102, 0.2), 0 10px 10px -5px rgba(255, 215, 0, 0.1);
        }
        .progress-bar {
            background: linear-gradient(90deg, #FFD700 0%, #FDB931 100%);
        }
        .status-badge {
            display: inline-flex; align-items: center;
            padding: 2px 10px; border-radius: 9999px;
            font-size: 11px; font-weight: 700; border-width: 1px;
        }
        .badge-pending    { background:rgba(254,240,138,.4); color:#854d0e; border-color:rgba(254,240,138,1); }
        .badge-approved   { background:rgba(187,247,208,.4); color:#166534; border-color:rgba(187,247,208,1); }
        .badge-rejected   { background:rgba(254,202,202,.4); color:#991b1b; border-color:rgba(254,202,202,1); }
        .badge-waitlisted { background:rgba(191,219,254,.4); color:#1e40af; border-color:rgba(191,219,254,1); }
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

        <!-- Header -->
        <div class="mb-8 flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#000035]">Dashboard</h1>
                <p class="text-gray-600 text-sm md:text-base">Welcome back, Administrator</p>
            </div>
            <div class="hidden sm:flex bg-white px-4 md:px-6 py-3 rounded-lg shadow-md items-center">
                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-gray-700 text-sm md:text-base">{{ now()->format('F d, Y') }}</span>
            </div>
        </div>

        <!-- Row 1: Main stat cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Applicants -->
            <div class="stat-card bg-white rounded-xl shadow-lg p-6 border-l-4 border-[#000035]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Applicants</p>
                        <p class="text-2xl md:text-3xl font-bold text-[#000035] mt-2">{{ $totalApplicants }}</p>
                        <p class="text-xs mt-2 flex items-center {{ $monthChange >= 0 ? 'text-green-600' : 'text-red-500' }}">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($monthChange >= 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                @endif
                            </svg>
                            {{ $monthChange >= 0 ? '+' : '' }}{{ $monthChange }}% vs last month
                        </p>
                    </div>
                    <div class="p-4 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-[#000035]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Regular -->
            <div class="stat-card bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Regular Students</p>
                        <p class="text-2xl md:text-3xl font-bold text-green-700 mt-2">{{ $regularStudents }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $totalApplicants > 0 ? round(($regularStudents/$totalApplicants)*100) : 0 }}% of total</p>
                    </div>
                    <div class="p-4 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Irregular -->
            <div class="stat-card bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Irregular Students</p>
                        <p class="text-2xl md:text-3xl font-bold text-yellow-600 mt-2">{{ $irregularStudents }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $totalApplicants > 0 ? round(($irregularStudents/$totalApplicants)*100) : 0 }}% of total</p>
                    </div>
                    <div class="p-4 bg-yellow-100 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Transferees -->
            <div class="stat-card bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Transferees</p>
                        <p class="text-2xl md:text-3xl font-bold text-purple-700 mt-2">{{ $transferees }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $totalApplicants > 0 ? round(($transferees/$totalApplicants)*100) : 0 }}% of total</p>
                    </div>
                    <div class="p-4 bg-purple-100 rounded-full">
                        <svg class="w-8 h-8 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Status breakdown cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-6">
            <!-- Pending -->
            <div class="stat-card bg-white rounded-xl shadow-lg p-5 border-l-4 border-yellow-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Pending</p>
                        <p class="text-2xl md:text-3xl font-bold text-yellow-500 mt-1">{{ $pendingCount }}</p>
                        <a href="{{ route('admin.applications', ['status' => 'Pending']) }}"
                           class="text-xs text-yellow-600 hover:underline mt-1 inline-block">Review now →</a>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-full">
                        <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Approved -->
            <div class="stat-card bg-white rounded-xl shadow-lg p-5 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Approved</p>
                        <p class="text-2xl md:text-3xl font-bold text-green-600 mt-1">{{ $approvedCount }}</p>
                        <a href="{{ route('admin.applications', ['status' => 'Approved']) }}"
                           class="text-xs text-green-600 hover:underline mt-1 inline-block">View list →</a>
                    </div>
                    <div class="p-3 bg-green-50 rounded-full">
                        <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rejected -->
            <div class="stat-card bg-white rounded-xl shadow-lg p-5 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Rejected</p>
                        <p class="text-2xl md:text-3xl font-bold text-red-600 mt-1">{{ $rejectedCount }}</p>
                        <a href="{{ route('admin.applications', ['status' => 'Rejected']) }}"
                           class="text-xs text-red-600 hover:underline mt-1 inline-block">View list →</a>
                    </div>
                    <div class="p-3 bg-red-50 rounded-full">
                        <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Waitlisted -->
            <div class="stat-card bg-white rounded-xl shadow-lg p-5 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Waitlisted</p>
                        <p class="text-2xl md:text-3xl font-bold text-blue-600 mt-1">{{ $waitlistedCount }}</p>
                        <a href="{{ route('admin.applications', ['status' => 'Waitlisted']) }}"
                           class="text-xs text-blue-600 hover:underline mt-1 inline-block">View list →</a>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Today + Approval Rate -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Today's Applications -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Today's Applications</h3>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">New</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-3xl md:text-4xl font-bold text-[#000035]">{{ $todayCount }}</p>
                        <p class="text-sm text-gray-500 mt-1">submitted today &nbsp;·&nbsp; {{ $thisMonthCount }} this month</p>
                    </div>
                    <a href="{{ route('admin.applications') }}"
                       class="px-4 py-2 bg-[#000035] text-white rounded-lg hover:bg-opacity-90 transition flex items-center">
                        View All
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Approval Rate -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Approval Rate</h3>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">Overall</span>
                </div>
                @php
                    $decided = $approvedCount + $rejectedCount + $waitlistedCount;
                    $approvalRate = $decided > 0 ? round(($approvedCount / $decided) * 100) : 0;
                @endphp
                <div class="flex items-center justify-between mb-3">
                    <p class="text-3xl md:text-4xl font-bold text-green-600">{{ $approvalRate }}%</p>
                    <p class="text-sm text-gray-500 text-right">{{ $approvedCount }} approved<br>out of {{ $decided }} decided</p>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="h-3 rounded-full bg-green-500" style="width: {{ $approvalRate }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-2">{{ $pendingCount }} application{{ $pendingCount != 1 ? 's' : '' }} still pending</p>
            </div>
        </div>

        <!-- Row 4: Recent Applications + Campus Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            <!-- Recent Applications Table (2/3 width) -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-yellow-400">
                <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                    <h2 class="text-lg font-bold text-[#000035]">Recent Applications</h2>
                    <a href="{{ route('admin.applications') }}"
                       class="text-sm text-[#000035] hover:underline font-medium">View all →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Applicant</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Course</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($recentApplications as $app)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-[#000035] rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                            <span class="text-yellow-400 font-bold text-xs">
                                                {{ substr($app->firstname,0,1) }}{{ substr($app->lastname,0,1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $app->firstname }} {{ $app->lastname }}</p>
                                            <p class="text-xs text-gray-400">{{ $app->campus }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-gray-700 font-medium max-w-[130px] truncate" title="{{ $app->course }}">{{ $app->course }}</p>
                                    <p class="text-xs text-gray-400">{{ $app->student_type }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="status-badge
                                        @if($app->status=='Pending') badge-pending
                                        @elseif($app->status=='Approved') badge-approved
                                        @elseif($app->status=='Rejected') badge-rejected
                                        @else badge-waitlisted @endif">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                                    {{ $app->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex space-x-1">
                                        <a href="{{ route('admin.view', $app->id) }}"
                                           class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition"
                                           title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        @if($app->status === 'Pending')
                                        <form action="{{ route('admin.approve', $app->id) }}" method="POST" class="inline"
                                              data-confirm-title="Approve Application"
                                              data-confirm-msg="Approve application for {{ $app->firstname }} {{ $app->lastname }}?"
                                              data-confirm-color="green">
                                            @csrf
                                            <button type="button"
                                                    onclick="showConfirmModal(this.closest('form'))"
                                                    class="p-1.5 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg transition"
                                                    title="Approve">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reject', $app->id) }}" method="POST" class="inline"
                                              data-confirm-title="Reject Application"
                                              data-confirm-msg="Reject application for {{ $app->firstname }} {{ $app->lastname }}?"
                                              data-confirm-color="red">
                                            @csrf
                                            <button type="button"
                                                    onclick="showConfirmModal(this.closest('form'))"
                                                    class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition"
                                                    title="Reject">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">No applications yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Campus Breakdown (1/3 width) -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-yellow-400">
                <h2 class="text-lg font-bold text-[#000035] mb-5">Applicants per Campus</h2>
                <div class="space-y-5">
                    @foreach($campusCounts as $campus => $count)
                    @php $pct = $totalApplicants > 0 ? ($count / $totalApplicants) * 100 : 0; @endphp
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">
                                {{ $campus }}{{ str_contains(strtolower($campus), 'campus') ? '' : ' Campus' }}
                            </span>
                            <span class="text-sm font-bold text-[#000035]">{{ $count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full progress-bar" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">{{ round($pct) }}% of total</p>
                    </div>
                    @endforeach
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 pt-5 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Quick Actions</p>
                    <div class="space-y-2">
                        <a href="{{ route('admin.applications', ['status' => 'Pending']) }}"
                           class="flex items-center p-2.5 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                            <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Review Pending ({{ $pendingCount }})</span>
                        </a>
                        <a href="{{ route('admin.applications', ['status' => 'Approved']) }}"
                           class="flex items-center p-2.5 bg-green-50 rounded-lg hover:bg-green-100 transition">
                            <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">View Approved ({{ $approvedCount }})</span>
                        </a>
                        <a href="{{ route('admin.applications') }}"
                           class="flex items-center p-2.5 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">All Applications</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /main content -->
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
