<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/png" href="{{ asset('images/PSU.png') }}">
    
    <!-- Alpine JS for Accordion -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @yield('extra_css')
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">
    <div class="min-h-screen">
        <!-- Mobile Header -->
        <div class="lg:hidden fixed top-0 inset-x-0 z-50 h-14 bg-[#000035] flex items-center px-4 shadow-lg">
            <button id="sidebar-open" type="button" class="text-white p-1.5 mr-3 rounded-lg hover:bg-white/10 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="text-white font-bold text-sm">PSU Admin Panel</span>
        </div>

        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" class="hidden fixed inset-0 z-30 bg-black/50 lg:hidden"></div>

        <!-- Sidebar Navigation -->
        @include('partials.admin-sidebar')

        <!-- Main Content Area -->
        <div class="lg:ml-64 pt-14 lg:pt-0 flex flex-col min-h-screen overflow-x-hidden">
            <!-- Top Navbar (Standardized) -->
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 md:px-8 sticky top-0 z-10 shadow-sm">
                <div class="flex items-center space-x-4">
                    <h2 class="text-sm font-black text-gray-400 uppercase tracking-widest">
                        Admin / <span class="text-[#000035]">@yield('breadcrumb', 'Dashboard')</span>
                    </h2>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2 text-right">
                        <span class="text-sm font-bold text-[#000035]">{{ Auth::user()->username ?? 'Administrator' }}</span>
                        <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center font-black text-[#000035]">
                            {{ substr(Auth::user()->username ?? 'A', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-xl flex items-center shadow-sm animate-pulse">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-xl flex items-center shadow-sm">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-bold">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="bg-white px-8 py-4 border-t border-gray-100 text-center text-xs text-gray-400 font-medium tracking-wide">
                &copy; {{ date('Y') }} Partido State University - Admission Management System
            </footer>
        </div>
    </div>

    @yield('extra_js')
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
</body>
</html>
