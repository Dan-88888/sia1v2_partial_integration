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
        .border-gold {
            border-color: #FFD700;
        }
        .focus-ring-gold:focus {
            ring-color: #FFD700;
        }
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
        input[type="password"]::-webkit-credentials-auto-fill-button,
        input[type="password"]::-webkit-strong-password-auto-fill-button {
            display: none !important;
        }
        .hero-pattern {
            background-color: #000035;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23FFD700' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .logo-container {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            overflow: hidden;
            background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 hero-pattern">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-yellow-300 to-yellow-500 opacity-10 rounded-full transform translate-x-32 -translate-y-32"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-yellow-300 to-yellow-500 opacity-10 rounded-full transform -translate-x-48 translate-y-48"></div>
    
    <div class="max-w-md w-full relative z-10">
        <!-- PSU Branding with Logo -->
        <div class="text-center mb-6">
            <div class="flex items-center justify-center space-x-3 mb-4">
                <!-- PSU Logo Image -->
                <div class="logo-container">
                    <img src="{{ asset('images/PSU_LOGO.png') }}" 
                         alt="PSU Logo" 
                         onerror="this.onerror=null; this.parentElement.style.backgroundColor='#FFD700'; this.parentElement.innerHTML='<span class=\'text-3xl font-bold text-[#000035]\'>PSU</span>';">
                </div>
            </div>
            <h2 class="text-2xl font-bold text-white">Partido State University</h2>
            <p class="text-yellow-300">Administrator Portal</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="psu-blue-bg p-6 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23FFD700' fill-opacity='0.3' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E');"></div>
                <div class="relative z-10">
                    <div class="w-20 h-20 mx-auto bg-yellow-400 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-[#000035]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Admin Login</h1>
                    <p class="text-yellow-300">Access the administration panel</p>
                </div>
            </div>
            
            <!-- Form -->
            <div class="p-5 md:p-8">
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">Login Failed</span>
                            </div>
                            <ul class="list-disc list-inside ml-6 text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-[#000035]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Email Address
                            </span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="admin@parsu.edu.ph"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition">
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-[#000035]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Password
                            </span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                   placeholder="••••••••"
                                   autocomplete="current-password"
                                   class="w-full px-4 py-3 pr-11 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition">
                            <button type="button" onclick="togglePassword()" tabindex="-1"
                                    style="position:absolute; top:50%; right:10px; transform:translateY(-50%); background:none; border:none; padding:0; cursor:pointer; color:#9ca3af; line-height:0;">
                                <svg id="eye-icon" style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eye-off-icon" style="width:20px;height:20px;display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-yellow-400 focus:ring-yellow-400">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-[#000035] hover:text-yellow-600 transition">
                            Forgot Password?
                        </a>
                    </div>
                    
                    <button type="submit"
                            class="w-full psu-blue-bg text-white px-6 py-4 rounded-xl font-bold text-lg hover:bg-opacity-90 transition transform hover:scale-105 shadow-lg flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login to Dashboard
                    </button>
                </form>
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="text-center">
                        <a href="#" onclick="window.parent.postMessage({action:'navigate-home'}, '*'); return false;" class="text-sm text-gray-600 hover:text-[#000035] transition flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Home
                        </a>
                    </div>
                    
                    @if(config('app.debug'))
                    <div class="mt-3 p-2 rounded-lg text-center mx-auto" style="background-color:#eef2ff; border: 1.5px solid #a5b4fc; max-width:260px;">
                        <p class="text-xs font-bold" style="color:#000035 !important; margin:0 0 1px 0;">Demo Credentials:</p>
                        <div style="font-size:11px;" class="inline-flex flex-col items-start gap-0.5">
                            <div class="flex gap-1 w-fit">
                                <span class="w-14 text-right font-medium" style="color:#000035 !important;">Email:</span>
                                <span style="color:#000000 !important; white-space:nowrap;">admin@parsu.edu.ph</span>
                            </div>
                            <div class="flex gap-1 w-fit">
                                <span class="w-14 text-right font-medium" style="color:#000035 !important;">Password:</span>
                                <span style="color:#000000 !important; white-space:nowrap;">@admin#2026</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-white text-sm">
            <p>&copy; 2024 Partido State University. All rights reserved.</p>
        </div>
    </div>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeOn = document.getElementById('eye-icon');
            const eyeOff = document.getElementById('eye-off-icon');
            if (input.type === 'password') {
                input.type = 'text';
                eyeOn.style.display = 'none';
                eyeOff.style.display = 'inline';
            } else {
                input.type = 'password';
                eyeOn.style.display = 'inline';
                eyeOff.style.display = 'none';
            }
        }
        if (window !== window.parent) {
            window.parent.postMessage({ action: 'iframe-nav', url: window.location.href, status: 200 }, '*');
        }
    </script>
</body>
</html>