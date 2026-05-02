<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Fallback while loading */
        body { visibility: hidden; }
        .loaded body { visibility: visible; }
        
        /* Updated PSU colors: Deep Navy #000035 + Gold */
        .psu-navy-bg {
            background: linear-gradient(135deg, #000035 0%, #000028 100%);
        }
        .psu-gold {
            color: #FFD966;
        }
        .psu-gold-bg {
            background-color: #FFD966;
        }
        .psu-navy {
            color: #000035;
        }
        .btn-gold {
            background: linear-gradient(135deg, #FFD966 0%, #F5C542 100%);
            color: #000035;
            border-radius: 0.75rem; /* rounded-xl */
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .btn-gold:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(245, 197, 66, 0.3);
            filter: brightness(110%);
        }
        .btn-navy {
            background: linear-gradient(135deg, #000035 0%, #000050 100%);
            color: white;
            border-radius: 0.75rem; /* rounded-xl */
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .btn-navy:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 53, 0.3);
            filter: brightness(120%);
        }
        .card-simple {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-simple:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0,0,0,0.12);
        }
        .hero-pattern {
            background-color: #000035;
            background-image: radial-gradient(circle at 10% 20%, rgba(255,217,102,0.06) 2px, transparent 2px);
            background-size: 28px 28px;
        }
        
        /* Circular Logo Container Styles */
        .logo-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFD966 0%, #F5C542 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .logo-circle:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }
        
        .logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        
        /* Footer logo circle - smaller */
        .logo-circle-footer {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFD966 0%, #F5C542 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        
        .logo-circle-footer img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .logo-circle {
                width: 48px;
                height: 48px;
            }
        }
        
        .psu-logo-img {
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Hero Section - PSU Navy #000035 -->
    <div class="psu-navy-bg hero-pattern relative overflow-hidden">
        <!-- Simple decorative -->
        <div class="absolute top-0 right-0 w-72 h-72 bg-yellow-400/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-yellow-400/5 rounded-full blur-2xl"></div>
        
        <!-- Navigation - with PSU Logo in Circle -->
        <nav class="relative z-10 container mx-auto px-6 py-5">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center space-x-3">
                    <!-- PSU Logo Circular Container -->
                    <div class="logo-circle">
                        <img src="{{ asset('images/PSU_LOGO.png') }}" 
                             alt="PSU Logo" 
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.parentElement.style.backgroundColor='#FFD966'; this.parentElement.innerHTML='<span class=\'text-2xl font-bold text-[#000035]\' style=\'font-size: 24px;\'>PSU</span>';">
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-white tracking-tight">Admission System</h2>
                        <p class="text-[#FFD966] text-xs">Excellence | Service | Leadership</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 text-sm">
                    <a href="#" class="text-white/80 hover:text-[#FFD966] transition px-3 py-1">About</a>
                    <a href="#" class="text-white/80 hover:text-[#FFD966] transition px-3 py-1">Contact</a>
                    <a href="#" class="bg-[#FFD966]/20 text-[#FFD966] px-4 py-1.5 rounded-lg text-sm hover:bg-[#FFD966] hover:text-[#000035] transition">Help</a>
                </div>
            </div>
        </nav>

        <!-- Hero Content - clean -->
        <div class="container mx-auto px-6 py-12 md:py-16 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-block bg-[#FFD966]/10 border border-[#FFD966]/30 text-[#FFD966] px-4 py-1.5 rounded-full text-sm font-medium mb-5">
                    🎓 AY 2025-2026 Now Open
                </div>
                <h1 class="text-2xl sm:text-4xl md:text-5xl font-bold text-white mb-4">
                    Welcome to <span class="text-[#FFD966]">Admission</span>
                </h1>
                <p class="text-blue-100/90 text-base md:text-lg mb-8">
                    Your journey to excellence starts here. Join Partido State University.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#student-portal" class="btn-gold px-8 py-4 shadow-lg flex items-center">
                        Apply Now
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="#admin-portal" class="border-2 border-[#FFD966] text-[#FFD966] px-8 py-4 rounded-xl font-bold hover:bg-[#FFD966] hover:text-[#000035] transition-all duration-300 transform hover:-translate-y-1">
                        Admin Login
                    </a>
                </div>
            </div>
        </div>

        <!-- Wave divider -->
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 80" class="w-full fill-white">
                <path d="M0,64L80,58.7C160,53,320,43,480,42.7C640,43,800,53,960,58.7C1120,64,1280,64,1360,64L1440,64L1440,80L1360,80C1280,80,1120,80,960,80C800,80,640,80,480,80C320,80,160,80,80,80L0,80Z"></path>
            </svg>
        </div>
    </div>

    <!-- Portals Section -->
    <div id="portals" class="container mx-auto px-6 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-[#000035] mb-3"></h2>
           
            <p class="text-gray-500 mt-4 text-sm"></p>
        </div>

        <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-8">
            <!-- Admin Portal Card -->
            <div id="admin-portal" class="bg-white rounded-xl shadow-md overflow-hidden card-simple border border-gray-100">
                <div class="h-2 bg-[#000035]"></div>
                <div class="p-8 text-center">
                    <div class="w-20 h-20 bg-[#000035]/10 rounded-full flex items-center justify-center mx-auto mb-5">
                        <svg class="w-10 h-10 text-[#000035]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#000035] mb-2">Admin</h3>
                    <p class="text-gray-500 text-sm mb-6"></p>
                    <a href="{{ route('admin.login') }}" 
                       class="inline-flex items-center justify-center gap-2 btn-navy w-full py-4 shadow-lg group">
                        Access Admin
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Student Portal Card -->
            <div id="student-portal" class="bg-white rounded-xl shadow-md overflow-hidden card-simple border border-gray-100">
                <div class="h-2 bg-[#FFD966]"></div>
                <div class="p-8 text-center">
                    <div class="w-20 h-20 bg-[#FFD966]/20 rounded-full flex items-center justify-center mx-auto mb-5">
                        <svg class="w-10 h-10 text-[#000035]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#000035] mb-2">Student</h3>
                    <p class="text-gray-500 text-sm mb-6"></p>
                    <a href="{{ route('student.apply') }}" 
                       class="inline-flex items-center justify-center gap-2 btn-gold w-full py-4 shadow-lg group">
                        Fill-up Form
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-[#000035] py-12 mt-8">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-2xl md:text-4xl font-bold text-[#FFD966]">5K+</div>
                    <div class="text-white/70 text-sm">Students</div>
                </div>
                <div>
                    <div class="text-2xl md:text-4xl font-bold text-[#FFD966]">50+</div>
                    <div class="text-white/70 text-sm">Programs</div>
                </div>
                <div>
                    <div class="text-2xl md:text-4xl font-bold text-[#FFD966]">94%</div>
                    <div class="text-white/70 text-sm">Success Rate</div>
                </div>
                <div>
                    <div class="text-2xl md:text-4xl font-bold text-[#FFD966]">3</div>
                    <div class="text-white/70 text-sm">Campuses</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer with PSU Logo in Circle -->
    <footer class="bg-[#000028] text-white py-10">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <!-- PSU Logo Circular Container in Footer -->
                        <div class="logo-circle-footer">
                            <img src="{{ asset('images/PSU_LOGO.png') }}" 
                                 alt="PSU Logo" 
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null; this.parentElement.style.backgroundColor='#FFD966'; this.parentElement.innerHTML='<span class=\'text-sm font-bold text-[#000035]\' style=\'font-size: 14px;\'>PSU</span>';">
                        </div>
                        <span class="font-semibold">Partido State University</span>
                    </div>
                    <p class="text-blue-200 text-sm">Excellence, Service, Leadership</p>
                </div>
                <div>
                    <h4 class="text-[#FFD966] font-semibold mb-3 text-sm">Quick Links</h4>
                    <ul class="space-y-1 text-sm text-blue-200">
                        <li><a href="#" class="hover:text-[#FFD966] transition">About</a></li>
                        <li><a href="#" class="hover:text-[#FFD966] transition">Admission</a></li>
                        <li><a href="#" class="hover:text-[#FFD966] transition">Academics</a></li>
                        <li><a href="#" class="hover:text-[#FFD966] transition">Research</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[#FFD966] font-semibold mb-3 text-sm">Campuses</h4>
                    <ul class="space-y-1 text-sm text-blue-200">
                        <li>Goa Campus (Main)</li>
                        <li>San Jose Campus</li>
                        <li>Lagonoy Campus</li>
                        <li>Caramoan Campus</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[#FFD966] font-semibold mb-3 text-sm">Contact Us</h4>
                    <ul class="space-y-1 text-sm text-blue-200">
                        <li>📞 (054) 123-4567</li>
                        <li>📧 admissions@psu.edu.ph</li>
                        <li>📍 Goa, Camarines Sur</li>
                        <li>🌐 www.psu.edu.ph</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 mt-8 pt-6 text-center text-blue-300 text-xs">
                <p>&copy; 2025 Partido State University. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.body.parentElement.classList.add('loaded');
        
        // Handle logo image loading errors gracefully
        document.querySelectorAll('.logo-circle img, .logo-circle-footer img').forEach(img => {
            img.addEventListener('error', function() {
                const container = this.parentElement;
                const text = container.classList.contains('logo-circle-footer') ? 'PSU' : 'PSU';
                const textSize = container.classList.contains('logo-circle-footer') ? '14px' : '24px';
                container.style.backgroundColor = '#FFD966';
                container.innerHTML = `<span class="font-bold text-[#000035]" style="font-size: ${textSize};">${text}</span>`;
            });
        });
    </script>
</body>
</html>