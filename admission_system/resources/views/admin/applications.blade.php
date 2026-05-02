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
            @apply flex items-center px-3 py-1 rounded-full text-[10px] font-bold shadow-sm border transition-all duration-300;
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
            @apply w-1.5 h-1.5 rounded-full mr-1.5;
        }
        .pulse {
            animation: pulse-animation 2s infinite;
        }
        @keyframes pulse-animation {
            0% { box-shadow: 0 0 0 0px rgba(234, 179, 8, 0.4); }
            100% { box-shadow: 0 0 0 4px rgba(234, 179, 8, 0); }
        }
        .action-icon-btn {
            @apply p-2 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md;
        }
        .table-header {
            @apply px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50;
        }
        .export-btn {
            @apply px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center;
        }
        .quick-stat-card {
            @apply bg-white rounded-lg shadow p-4 border-l-4 hover:shadow-md transition;
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
            <!-- Header with Quick Stats -->
            <div class="mb-8">
                <div class="flex flex-wrap justify-between items-center mb-4 gap-3">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-[#000035]">Applications Management</h1>
                        <p class="text-gray-600 text-sm md:text-base">View and manage all student applications</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <!-- Export Button -->
                        <button onclick="exportTableToCSV()" class="export-btn text-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export CSV
                        </button>
                        <!-- Print Button -->
                        <button onclick="window.print()" class="px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center text-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>

                <!-- Quick Statistics Cards -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4 mb-6">
                    <div class="quick-stat-card border-l-yellow-400">
                        <p class="text-sm text-gray-500">Total Applicants</p>
                        <p class="text-2xl font-bold text-[#000035]">{{ $totalApplicants ?? $applications->total() }}</p>
                    </div>
                    <div class="quick-stat-card border-l-yellow-400">
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ \App\Models\StudentApplication::where('status', 'Pending')->count() }}</p>
                    </div>
                    <div class="quick-stat-card border-l-green-500">
                        <p class="text-sm text-gray-500">Approved</p>
                        <p class="text-2xl font-bold text-green-600">{{ \App\Models\StudentApplication::where('status', 'Approved')->count() }}</p>
                    </div>
                    <div class="quick-stat-card border-l-red-500">
                        <p class="text-sm text-gray-500">Rejected</p>
                        <p class="text-2xl font-bold text-red-600">{{ \App\Models\StudentApplication::where('status', 'Rejected')->count() }}</p>
                    </div>
                    <div class="quick-stat-card border-l-blue-500">
                        <p class="text-sm text-gray-500">Waitlisted</p>
                        <p class="text-2xl font-bold text-blue-600">{{ \App\Models\StudentApplication::where('status', 'Waitlisted')->count() }}</p>
                    </div>
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

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Search and Filter Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-t-4 border-yellow-400">
                <h2 class="text-lg font-semibold text-[#000035] mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter Applications
                </h2>
                <form method="GET" action="{{ route('admin.applications') }}" class="space-y-4" id="filterForm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Name, course, campus..."
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Campus</label>
                            <select name="campus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400">
                                <option value="">All Campuses</option>
                                @foreach($campuses as $campus)
                                    <option value="{{ $campus->name }}" {{ request('campus') == $campus->name ? 'selected' : '' }}>{{ $campus->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student Type</label>
                            <select name="student_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400">
                                <option value="">All Types</option>
                                <option value="Regular" {{ request('student_type') == 'Regular' ? 'selected' : '' }}>Regular</option>
                                <option value="Irregular" {{ request('student_type') == 'Irregular' ? 'selected' : '' }}>Irregular</option>
                                <option value="Transferee" {{ request('student_type') == 'Transferee' ? 'selected' : '' }}>Transferee</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400">
                                <option value="">All Status</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>🟡 Pending</option>
                                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>✅ Approved</option>
                                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>❌ Rejected</option>
                                <option value="Waitlisted" {{ request('status') == 'Waitlisted' ? 'selected' : '' }}>⏳ Waitlisted</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap justify-end gap-2">
                        <a href="{{ route('admin.applications') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Clear Filters
                        </a>
                        <button type="submit" class="px-4 py-2 psu-blue-bg text-white rounded-lg hover:bg-opacity-90 transition flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Active Filters -->
            @if(request()->anyFilled(['search', 'campus', 'student_type', 'status']))
            <div class="mb-4 flex items-center space-x-2 flex-wrap gap-2">
                <span class="text-sm text-gray-600">Active Filters:</span>
                @if(request('search'))
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm flex items-center">
                    Search: "{{ request('search') }}"
                    <a href="{{ route('admin.applications', array_merge(request()->except('search'), ['page' => null])) }}" class="ml-2 hover:text-blue-900 font-bold">×</a>
                </span>
                @endif
                @if(request('campus'))
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm flex items-center">
                    Campus: {{ request('campus') }}
                    <a href="{{ route('admin.applications', array_merge(request()->except('campus'), ['page' => null])) }}" class="ml-2 hover:text-blue-900 font-bold">×</a>
                </span>
                @endif
                @if(request('student_type'))
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm flex items-center">
                    Type: {{ request('student_type') }}
                    <a href="{{ route('admin.applications', array_merge(request()->except('student_type'), ['page' => null])) }}" class="ml-2 hover:text-blue-900 font-bold">×</a>
                </span>
                @endif
                @if(request('status'))
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm flex items-center">
                    Status: {{ request('status') }}
                    <a href="{{ route('admin.applications', array_merge(request()->except('status'), ['page' => null])) }}" class="ml-2 hover:text-blue-900 font-bold">×</a>
                </span>
                @endif
            </div>
            @endif
            
            <!-- Applications Table Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-yellow-400">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="applicationsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="table-header">Student Name</th>
                                <th class="table-header hidden sm:table-cell">Campus</th>
                                <th class="table-header hidden md:table-cell">Course</th>
                                <th class="table-header hidden lg:table-cell">Type</th>
                                <th class="table-header hidden lg:table-cell">Contact</th>
                                <th class="table-header hidden xl:table-cell">Gmail</th>
                                <th class="table-header">Status</th>
                                <th class="table-header hidden sm:table-cell">Date</th>
                                <th class="table-header">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($applications as $application)
                            <tr class="transition">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                            <span class="text-blue-800 font-semibold text-sm">
                                                {{ substr($application->firstname, 0, 1) }}{{ substr($application->lastname, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate max-w-[130px]" title="{{ $application->firstname }} {{ $application->lastname }}">
                                                {{ $application->firstname }} {{ $application->lastname }}
                                            </div>
                                            <div class="text-xs text-gray-500 truncate">{{ $application->middlename ?: '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap hidden sm:table-cell">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-lg text-[10px] font-semibold">
                                        {{ $application->campus }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 max-w-[180px] hidden md:table-cell">
                                    <div class="text-sm font-medium text-gray-900 truncate" title="{{ $application->course }}">{{ $application->course }}</div>
                                    <div class="text-xs text-gray-500 truncate" title="{{ $application->college }}">{{ $application->college }}</div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-500 hidden lg:table-cell">
                                    {{ $application->student_type }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-500 font-mono hidden lg:table-cell">
                                    {{ $application->contact_number }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-xs hidden xl:table-cell">
                                    <div class="max-w-[160px] truncate text-blue-600" title="{{ $application->gmail_account }}">
                                        {{ $application->gmail_account }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-xs text-gray-500 hidden sm:table-cell">
                                    <div class="font-bold">{{ $application->created_at->format('M d, Y') }}</div>
                                    <div class="text-[10px] opacity-75">{{ $application->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.view', $application->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 bg-blue-50 action-icon-btn hover:bg-blue-100"
                                           title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.edit', $application->id) }}" 
                                           class="text-green-600 hover:text-green-900 bg-green-50 action-icon-btn hover:bg-green-100"
                                           title="Edit Application">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.delete', $application->id) }}"
                                              method="POST"
                                              class="inline confirm-form"
                                              data-confirm-title="Delete Application"
                                              data-confirm-msg="Are you sure you want to delete this application? This action cannot be undone."
                                              data-confirm-color="red">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="showConfirmModal(this.closest('form'))"
                                                    class="text-red-600 hover:text-red-900 bg-red-50 action-icon-btn hover:bg-red-100"
                                                    title="Delete Application">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg">No applications found</p>
                                    <p class="text-gray-400">Try adjusting your filters or check back later</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination and Info -->
                <div class="px-6 py-4 border-t bg-gray-50 flex flex-wrap justify-between items-center gap-3">
                    <div class="text-sm text-gray-500">
                        Showing {{ $applications->firstItem() ?? 0 }} to {{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }} entries
                    </div>
                    <div class="flex space-x-2">
                        {{ $applications->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Export to CSV function
        function exportTableToCSV() {
            const table = document.getElementById('applicationsTable');
            const rows = table.querySelectorAll('tr');
            const csv = [];
            
            // Get headers (excluding Actions column)
            const headers = [];
            table.querySelectorAll('thead th').forEach((th, index) => {
                if (index < 8) {
                    headers.push('"' + th.textContent.trim() + '"');
                }
            });
            csv.push(headers.join(','));
            
            // Get data rows (excluding Actions column)
            rows.forEach((row, rowIndex) => {
                if (rowIndex === 0) return;
                const rowData = [];
                row.querySelectorAll('td').forEach((td, colIndex) => {
                    if (colIndex < 8) {
                        let text = td.textContent.trim();
                        text = text.replace(/\s+/g, ' ').trim();
                        rowData.push('"' + text + '"');
                    }
                });
                if (rowData.length) csv.push(rowData.join(','));
            });
            
            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'applications_export_' + new Date().toISOString().split('T')[0] + '.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        document.querySelectorAll('#filterForm select').forEach(select => {
            select.addEventListener('change', () => {
                document.getElementById('filterForm').submit();
            });
        });
    </script>

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
    <style>
        @keyframes modalIn { from { opacity:0; transform:scale(.95) translateY(-8px); } to { opacity:1; transform:none; } }
    </style>
    <script>
        var _pendingForm = null;
        function showConfirmModal(form) {
            _pendingForm = form;
            var color   = form.dataset.confirmColor || 'red';
            var colors  = { red:'#dc2626', blue:'#2563eb', green:'#16a34a', yellow:'#d97706' };
            var bg      = colors[color] || colors.red;
            document.getElementById('modal-title').textContent   = form.dataset.confirmTitle || 'Confirm Action';
            document.getElementById('modal-msg').textContent     = form.dataset.confirmMsg   || 'Are you sure?';
            document.getElementById('modal-icon').style.background   = bg;
            document.getElementById('modal-confirm-btn').style.background = bg;
            var modal = document.getElementById('confirm-modal');
            modal.style.display = 'flex';
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