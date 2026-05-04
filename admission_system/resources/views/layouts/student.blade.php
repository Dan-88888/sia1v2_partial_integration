<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/png" href="{{ asset('images/PSU.png') }}">
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('extra_css')
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">
    <!-- Student Header -->
    <header class="psu-blue-bg shadow-xl sticky top-0 z-50">
        <div class="container mx-auto px-4 md:px-6 py-3 md:py-4">
            <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2">
                <a href="#" onclick="window.parent.postMessage({action:'navigate-home'}, '*'); return false;" class="flex items-center space-x-3 group flex-shrink-0">
                    <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-tr from-yellow-300 to-yellow-500 rounded-2xl p-0.5 shadow-lg group-hover:rotate-6 transition-transform">
                        <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center border-2 border-[#000035] overflow-hidden">
                            <img src="{{ asset('images/PSU_LOGO.png') }}" class="w-[85%] h-[85%] object-contain" alt="PSU Logo"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'text-xl font-bold text-[#000035]\'>PSU</span>';">
                        </div>
                    </div>
                    <div>
                        <h1 class="text-base md:text-2xl font-black text-white leading-tight">Partido State University</h1>
                        <p class="text-yellow-400 text-[9px] md:text-xs font-black uppercase tracking-[0.2em]">Admissions Portal</p>
                    </div>
                </a>

                <!-- Nav — always visible, wraps on small screens -->
                <nav class="flex flex-wrap items-center gap-x-4 gap-y-1">
                    <a href="#" onclick="window.parent.postMessage({action:'navigate-home'}, '*'); return false;" class="text-[11px] md:text-xs font-black uppercase tracking-widest text-blue-100 hover:text-yellow-300 transition">Home</a>
                    <a href="{{ route('student.apply') }}" class="text-[11px] md:text-xs font-black uppercase tracking-widest {{ request()->routeIs('student.apply') ? 'text-yellow-400' : 'text-blue-100 hover:text-yellow-300 transition' }}">New Application</a>
                    <a href="{{ route('student.track') }}" class="text-[11px] md:text-xs font-black uppercase tracking-widest {{ request()->is('student/track*') ? 'text-yellow-400' : 'text-blue-100 hover:text-yellow-300 transition' }}">Track Status</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="min-h-[calc(100vh-160px)]">
        @yield('content')
    </main>

    <!-- Student Footer -->
    <footer class="bg-white border-t border-gray-100 p-4 md:p-8">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <p class="text-xs font-black text-[#000035] uppercase tracking-[0.1em]">&copy; {{ date('Y') }} Partido State University</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Managed by the Office of Admissions</p>
                </div>
                <div class="flex space-x-6">
                    <a href="#" class="text-[10px] font-black text-gray-400 hover:text-[#000035] uppercase tracking-widest transition">Privacy Policy</a>
                    <a href="#" class="text-[10px] font-black text-gray-400 hover:text-[#000035] uppercase tracking-widest transition">Terms of Use</a>
                    <a href="{{ route('admin.login') }}" class="text-[10px] font-black text-blue-400 hover:text-blue-600 uppercase tracking-widest transition">Admin Login</a>
                </div>
            </div>
        </div>
    </footer>

    @yield('extra_js')
    <script>
    if (window !== window.parent) {
        window.parent.postMessage({ action: 'iframe-nav', url: window.location.href, status: 200 }, '*');
    }
    </script>
</body>
</html>
