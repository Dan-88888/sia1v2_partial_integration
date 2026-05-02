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
        .track-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .track-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 51, 102, 0.2), 0 10px 10px -5px rgba(255, 215, 0, 0.1);
        }
        .method-badge {
            @apply px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold;
        }
        .logo-container { width: 44px; height: 44px; }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
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
                        <p class="text-yellow-300 text-xs md:text-sm">Office of Admissions</p>
                    </div>
                </div>
                <div class="hidden sm:block text-right">
                    <p class="text-yellow-300 text-sm">Track Your Application Status</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-6 flex items-center text-sm bg-white p-3 rounded-lg shadow-sm">
                <a href="#" onclick="window.parent.postMessage({action:'navigate-home'}, '*'); return false;" class="text-gray-500 hover:text-[#000035] transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Home
                </a>
                <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('student.apply') }}" class="text-gray-500 hover:text-[#000035] transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                    Apply
                </a>
                <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#000035] font-semibold flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Track Application
                </span>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-t-4 border-yellow-400 track-card">
                <div class="psu-gold-bg px-4 md:px-8 py-4 md:py-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-xl md:text-2xl font-bold text-[#000035] flex items-center">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Find Your Application
                        </h2>
                        <span class="method-badge">Secure Lookup</span>
                    </div>
                    <p class="text-[#000035] mt-2 ml-11">Enter your Application ID to check your admission progress</p>
                </div>

                <div class="p-4 md:p-8">
                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg flex items-start">
                            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <strong class="font-bold">Error: </strong>
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg flex items-start">
                            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <strong class="font-bold">Success! </strong>
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="bg-blue-50 rounded-lg p-4 mb-6 flex items-start">
                        <svg class="w-5 h-5 text-[#000035] mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-[#000035]">Enter your Application ID to check your admission status. Your information is secure and encrypted.</p>
                    </div>

                    <form action="{{ route('student.lookup') }}" method="GET" class="space-y-6" id="trackForm" onsubmit="return validateForm()">
                        <!-- Application ID Field -->
                        <div class="bg-gray-50 p-6 rounded-xl border-2 border-transparent hover:border-yellow-400 transition" id="idField">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-[#000035]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                    Application ID
                                </span>
                            </label>
                            <input type="text"
                                   name="application_id"
                                   id="application_id"
                                   placeholder="e.g., 12345"
                                   value="{{ request('application_id') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition"
                                   oninput="hideError()">
                            <div id="appIdError" class="hidden mt-2 flex items-center gap-1.5 text-sm text-red-600 bg-red-50 border border-red-300 rounded-lg px-3 py-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span id="appIdErrorText"></span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Found in your application confirmation email
                            </p>
                        </div>


                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                            <a href="{{ route('student.apply') }}" class="text-gray-500 hover:text-[#000035] transition flex items-center order-2 sm:order-1">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Application Form
                            </a>
                            <button type="submit" class="psu-blue-bg text-white px-10 py-4 rounded-xl font-bold text-lg hover:bg-opacity-90 transition transform hover:scale-105 shadow-lg flex items-center order-1 sm:order-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Track Application
                            </button>
                        </div>
                    </form>

                    <!-- Quick Tips -->
                    <div class="mt-8 grid md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="font-semibold text-[#000035] mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"></path>
                                </svg>
                                Quick Tips
                            </h3>
                            <ul class="text-xs text-gray-600 space-y-2">
                                <li class="flex items-start">
                                    <span class="text-yellow-500 mr-2">•</span>
                                    Application ID is a number (e.g., 12345)
                                </li>
                                <li class="flex items-start">
                                    <span class="text-yellow-500 mr-2">•</span>
                                    Check your spam folder for confirmation email
                                </li>
                            </ul>
                        </div>

                        <div class="bg-yellow-50 rounded-lg p-4">
                            <h3 class="font-semibold text-[#000035] mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562c.34.621.552 1.305.552 2.033zm-3.659-2.125l-1.562 1.562a4.006 4.006 0 00-1.779-.874l.233-1.558c.967.102 1.886.462 2.634.958l.474-.088zM9.66 6.995l.233-1.558c-.967-.102-1.886-.462-2.634-.958l-.474.088 1.447 1.448c.534.15 1.04.4 1.479.734l.466-.022zm-3.83 1.298l.172 1.465c-.164.446-.264.92-.264 1.415 0 .994.242 1.928.668 2.753l-1.525 1.526A7.948 7.948 0 012 10c0-.993.24-1.928.666-2.753l1.18 1.18-.016.564c0 .333.025.67.075 1.004l1.604-1.604a3.99 3.99 0 01.141-2.368l-1.562 1.562a4.055 4.055 0 00-.57 1.643l1.41-1.41z" clip-rule="evenodd"/>
                                </svg>
                                Need Help?
                            </h3>
                            <div class="space-y-2 text-xs">
                                <p class="flex items-center text-gray-600">
                                    <svg class="w-3 h-3 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    admissions@psu.edu.ph
                                </p>
                                <p class="flex items-center text-gray-600">
                                    <svg class="w-3 h-3 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    (054) 123-4567
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Applications Preview (Optional) -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    By tracking your application, you agree to our 
                    <a href="#" class="text-[#000035] hover:underline">Privacy Policy</a> and 
                    <a href="#" class="text-[#000035] hover:underline">Terms of Service</a>.
                </p>
            </div>
        </div>
    </div>

    <script>

        function showError(msg) {
            const box = document.getElementById('appIdError');
            document.getElementById('appIdErrorText').textContent = msg;
            box.classList.remove('hidden');
            document.getElementById('application_id').focus();
        }

        function hideError() {
            document.getElementById('appIdError').classList.add('hidden');
        }

        function validateForm() {
            const appId = document.getElementById('application_id').value.trim();

            if (!appId) {
                showError('Please enter your Application ID.');
                return false;
            }

            if (!/^\d+$/.test(appId)) {
                showError('Application ID should contain only numbers.');
                return false;
            }

            // Prevent double-submit
            var btn = document.querySelector('#trackForm button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Looking up…';
            }

            return true;
        }

        // Add event listeners for highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const appIdField = document.getElementById('application_id');
            const idFieldDiv = document.getElementById('idField');

            if (appIdField) {
                appIdField.addEventListener('focus', function() {
                    idFieldDiv.classList.add('border-yellow-400');
                });
                
                appIdField.addEventListener('blur', function() {
                    idFieldDiv.classList.remove('border-yellow-400');
                });
            }
        });
    </script>
</body>
</html>