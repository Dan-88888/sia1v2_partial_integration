<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .psu-blue-bg { background: linear-gradient(135deg, #000035 0%, #00004d 100%); }
        .psu-gold-bg { background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%); }

        /* Progress */
        .step-circle {
            width: 2.5rem; height: 2.5rem; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.875rem;
            transition: all .2s ease; flex-shrink: 0; user-select: none;
        }
        .step-circle.done     { background: #16a34a; color: #fff; }
        .step-circle.current  { background: #000035; color: #FFD700; box-shadow: 0 0 0 4px rgba(0,0,53,.15); }
        .step-circle.upcoming { background: #e5e7eb; color: #9ca3af; }
        .step-circle:hover    { opacity: .8; transform: scale(1.08); }
        .step-label { font-size: .75rem; line-height: 1.375; }
        @media (min-width: 640px) { .step-label { font-size: .875rem; } }
        .step-label.done     { color: #16a34a; }
        .step-label.current  { color: #000035; font-weight: 700; }
        .step-label.upcoming { color: #9ca3af; }
        .step-line { flex: 1; height: 3px; border-radius: 2px; margin: 0 .5rem; transition: background .3s; }
        .step-line.done { background: #16a34a; }
        .step-line.upcoming { background: #e5e7eb; }

        /* Form steps */
        .form-step { display: none; animation: fadeIn .25s ease; }
        .form-step.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

        /* Section cards */
        .section-card {
            background: #f9fafb; border: 1px solid #e5e7eb;
            border-radius: .75rem; padding: 1.25rem 1.5rem; margin-bottom: 1.25rem;
        }
        .section-title {
            font-size: .9375rem; font-weight: 700; color: #000035;
            display: flex; align-items: center; gap: .5rem; margin-bottom: 1rem;
        }
        .section-title svg { color: #d97706; flex-shrink: 0; }

        /* Inputs */
        .field-label {
            display: block; font-size: .8125rem; font-weight: 600;
            color: #374151; margin-bottom: .375rem;
        }
        .field-input {
            width: 100%; padding: .625rem .875rem;
            border: 1.5px solid #d1d5db; border-radius: .5rem;
            font-size: .875rem; background: #fff;
            transition: border-color .2s, box-shadow .2s;
        }
        .field-input:focus {
            outline: none; border-color: #FFD700;
            box-shadow: 0 0 0 3px rgba(255,215,0,.2);
        }
        .field-input.is-error { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.1); }

        /* File upload */
        .upload-zone {
            position: relative; border: 2px dashed #d1d5db; border-radius: .75rem;
            padding: 1.25rem; text-align: center; cursor: pointer;
            background: #fff; transition: border-color .2s, background .2s;
        }
        .upload-zone:hover  { border-color: #FFD700; background: #fffbeb; }
        .upload-zone.has-file { border-color: #16a34a; border-style: solid; background: #f0fdf4; }
        .upload-zone.is-error { border-color: #ef4444; background: #fff5f5; }
        .upload-zone input[type="file"] {
            position: absolute; inset: 0; opacity: 0; width: 100%; height: 100%; cursor: pointer;
        }

        /* Logo */
        .logo-container {
            width: 50px; height: 50px; border-radius: 50%; overflow: hidden;
            background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .logo-container img { width: 100%; height: 100%; object-fit: cover; }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #000035 0%, #00004d 100%);
            color: #FFD700; font-weight: 700; padding: .75rem 2rem;
            border-radius: .625rem; display: inline-flex; align-items: center; gap: .5rem;
            transition: opacity .2s, transform .15s; border: none; cursor: pointer;
        }
        .btn-primary:hover { opacity: .9; transform: translateY(-1px); }
        .btn-secondary {
            background: #f3f4f6; color: #374151; font-weight: 600;
            padding: .75rem 1.5rem; border-radius: .625rem; border: 1.5px solid #d1d5db;
            display: inline-flex; align-items: center; gap: .5rem;
            transition: background .2s; cursor: pointer;
        }
        .btn-secondary:hover { background: #e5e7eb; }
        .btn-submit {
            background: linear-gradient(135deg, #000035 0%, #00004d 100%);
            color: #FFD700; font-weight: 700; font-size: 1rem;
            padding: .875rem 2.5rem; border-radius: .75rem;
            display: inline-flex; align-items: center; gap: .625rem;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(0,0,53,.3);
        }
        .btn-submit:hover { opacity: .92; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,53,.35); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="psu-blue-bg shadow-lg sticky top-0 z-10">
        <div class="container mx-auto px-4 py-3.5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="logo-container">
                        <img src="{{ asset('images/PSU_LOGO.png') }}" alt="PSU Logo"
                             onerror="this.onerror=null;this.parentElement.innerHTML='<span class=\'font-bold text-[#000035] text-lg\'>PSU</span>'">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white leading-tight">Partido State University</h1>
                        <p class="text-yellow-300 text-xs">Online Admission Application</p>
                    </div>
                </div>
                <div class="hidden sm:flex items-center gap-4 text-sm">
                    <a href="{{ route('student.track') }}"
                       class="text-yellow-300 hover:text-white transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Track Application
                    </a>
                    <a href="#"
                       onclick="window.parent.postMessage({action:'navigate-home'}, '*'); return false;"
                       class="text-gray-300 hover:text-white transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">

            <!-- Alerts -->
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
                    <div class="flex items-center gap-2 font-semibold mb-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Please fix the following errors:
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-sm ml-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Progress Steps -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-4 sm:px-6 py-4 sm:py-5 mb-6">
                <div class="flex items-center">
                    <!-- Step 1 -->
                    <div class="flex items-center gap-1.5 sm:gap-2.5 flex-shrink-0">
                        <div id="circle-1" class="step-circle current">1</div>
                        <div>
                            <p class="text-[10px] sm:text-xs text-gray-400 leading-none">Step 1</p>
                            <p id="label-1" class="step-label current">
                                <span class="hidden sm:inline">Personal Info</span>
                                <span class="sm:hidden">Personal</span>
                            </p>
                        </div>
                    </div>
                    <div id="line-1" class="step-line upcoming"></div>
                    <!-- Step 2 -->
                    <div class="flex items-center gap-1.5 sm:gap-2.5 flex-shrink-0">
                        <div id="circle-2" class="step-circle upcoming">2</div>
                        <div>
                            <p class="text-[10px] sm:text-xs text-gray-400 leading-none">Step 2</p>
                            <p id="label-2" class="step-label upcoming">
                                <span class="hidden sm:inline">Guardian Info</span>
                                <span class="sm:hidden">Guardian</span>
                            </p>
                        </div>
                    </div>
                    <div id="line-2" class="step-line upcoming"></div>
                    <!-- Step 3 -->
                    <div class="flex items-center gap-1.5 sm:gap-2.5 flex-shrink-0">
                        <div id="circle-3" class="step-circle upcoming">3</div>
                        <div>
                            <p class="text-[10px] sm:text-xs text-gray-400 leading-none">Step 3</p>
                            <p id="label-3" class="step-label upcoming">
                                <span class="hidden sm:inline">Academic &amp; Docs</span>
                                <span class="sm:hidden">Academic</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <!-- Card header bar -->
                <div class="psu-gold-bg px-6 py-3 flex items-center justify-between">
                    <h2 id="step-title" class="text-lg font-bold text-[#000035]">Personal Information</h2>
                    <span class="text-xs font-semibold bg-[#000035] text-yellow-400 px-3 py-1 rounded-full">
                        <span id="step-indicator">1</span> / 3
                    </span>
                </div>

                <form action="{{ route('student.submit') }}" method="POST"
                      enctype="multipart/form-data" id="admissionForm" novalidate>
                    @csrf
                    <div class="p-6 md:p-8">

                        <!-- ─── STEP 1 : Personal Information ─── -->
                        <div id="step-1" class="form-step active">

                            <!-- Full Name -->
                            <div class="section-card">
                                <h3 class="section-title">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Full Name
                                </h3>
                                <div class="grid sm:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label class="field-label">Last Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="lastname" value="{{ old('lastname') }}" required
                                               placeholder="e.g. Dela Cruz"
                                               class="field-input @error('lastname') is-error @enderror">
                                        @error('lastname')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="field-label">First Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="firstname" value="{{ old('firstname') }}" required
                                               placeholder="e.g. Juan"
                                               class="field-input @error('firstname') is-error @enderror">
                                        @error('firstname')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="field-label">Middle Name</label>
                                        <input type="text" name="middlename" value="{{ old('middlename') }}"
                                               placeholder="e.g. Santos"
                                               class="field-input @error('middlename') is-error @enderror">
                                    </div>
                                </div>
                                <div class="sm:w-48">
                                    <label class="field-label">Name Extender</label>
                                    <select name="name_extender" class="field-input">
                                        <option value="">None</option>
                                        @foreach(['Jr.','Sr.','I','II','III'] as $ext)
                                            <option value="{{ $ext }}" {{ old('name_extender') == $ext ? 'selected' : '' }}>{{ $ext }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Personal Details -->
                            <div class="section-card">
                                <h3 class="section-title">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    Personal Details
                                </h3>
                                <div class="grid sm:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label class="field-label">Age <span class="text-red-500">*</span></label>
                                        <input type="number" name="age" value="{{ old('age') }}" required
                                               min="15" max="100" placeholder="e.g. 18"
                                               class="field-input @error('age') is-error @enderror">
                                        @error('age')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="field-label">Sex <span class="text-red-500">*</span></label>
                                        <select name="sex" required class="field-input @error('sex') is-error @enderror">
                                            <option value="">Select</option>
                                            <option value="Male"   {{ old('sex') == 'Male'   ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('sex')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="field-label">Civil Status <span class="text-red-500">*</span></label>
                                        <select name="civil_status" required class="field-input @error('civil_status') is-error @enderror">
                                            <option value="">Select</option>
                                            @foreach(['Single','Married','Widowed','Divorced','Separated'] as $cs)
                                                <option value="{{ $cs }}" {{ old('civil_status') == $cs ? 'selected' : '' }}>{{ $cs }}</option>
                                            @endforeach
                                        </select>
                                        @error('civil_status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="field-label">Date of Birth <span class="text-red-500">*</span></label>
                                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                               class="field-input @error('date_of_birth') is-error @enderror">
                                        @error('date_of_birth')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="field-label">Place of Birth <span class="text-red-500">*</span></label>
                                        <input type="text" name="birth_place" value="{{ old('birth_place') }}" required
                                               placeholder="City / Municipality, Province"
                                               class="field-input @error('birth_place') is-error @enderror">
                                        @error('birth_place')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="section-card">
                                <h3 class="section-title">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    Address
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="field-label">Temporary / Current Address <span class="text-red-500">*</span></label>
                                        <textarea name="temporary_address" rows="2" required
                                                  placeholder="House No., Street, Barangay, Municipality, Province"
                                                  class="field-input @error('temporary_address') is-error @enderror">{{ old('temporary_address') }}</textarea>
                                        @error('temporary_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="field-label">Permanent Address <span class="text-red-500">*</span></label>
                                        <textarea name="permanent_address" rows="2" required
                                                  placeholder="House No., Street, Barangay, Municipality, Province"
                                                  class="field-input @error('permanent_address') is-error @enderror">{{ old('permanent_address') }}</textarea>
                                        @error('permanent_address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contact -->
                            <div class="section-card">
                                <h3 class="section-title">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                    Contact Information
                                </h3>
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="field-label">Cellphone Number <span class="text-red-500">*</span></label>
                                        <input type="text" name="contact_number" value="{{ old('contact_number') }}" required
                                               placeholder="09XXXXXXXXX"
                                               class="field-input @error('contact_number') is-error @enderror">
                                        @error('contact_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="field-label">Gmail Account <span class="text-red-500">*</span></label>
                                        <div class="flex">
                                            <input type="text" name="gmail_account"
                                                   value="{{ preg_replace('/@gmail\.com$/i', '', old('gmail_account', '')) }}"
                                                   required placeholder="youremail"
                                                   class="field-input rounded-r-none border-r-0 @error('gmail_account') is-error @enderror">
                                            <span class="inline-flex items-center px-3 bg-gray-100 border-l-0 border-y border-r border-gray-300 rounded-r-lg text-gray-500 text-sm font-medium whitespace-nowrap" style="border-width:1.5px">
                                                @gmail.com
                                            </span>
                                        </div>
                                        @error('gmail_account')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                        <p class="text-xs text-gray-400 mt-1">Admission updates will be sent here</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Nav -->
                            <div class="flex justify-end pt-2">
                                <button type="button" data-goto="2" class="btn-primary">
                                    Next: Guardian Info
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div><!-- /step-1 -->


                        <!-- ─── STEP 2 : Guardian Information ─── -->
                        <div id="step-2" class="form-step">

                            <div class="section-card">
                                <h3 class="section-title">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                    </svg>
                                    Guardian / Parent Details
                                </h3>
                                <div class="grid sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-1">
                                        <label class="field-label">Complete Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" required
                                               placeholder="Last Name, First Name"
                                               class="field-input @error('guardian_name') is-error @enderror">
                                        @error('guardian_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="field-label">Relationship <span class="text-red-500">*</span></label>
                                        <select name="guardian_relationship" required
                                                class="field-input @error('guardian_relationship') is-error @enderror">
                                            <option value="">Select</option>
                                            @foreach(['Mother','Father','Brother','Sister','Grandmother','Grandfather','Auntie','Uncle','Legal Guardian'] as $rel)
                                                <option value="{{ $rel }}" {{ old('guardian_relationship') == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                            @endforeach
                                        </select>
                                        @error('guardian_relationship')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="field-label">Contact Number <span class="text-red-500">*</span></label>
                                        <input type="text" name="guardian_phone" value="{{ old('guardian_phone') }}" required
                                               placeholder="09XXXXXXXXX"
                                               class="field-input @error('guardian_phone') is-error @enderror">
                                        @error('guardian_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Info note -->
                            <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-100 rounded-xl mb-4">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm text-blue-700">
                                    Guardian information is used for official correspondence and emergency contact purposes.
                                    Please ensure the contact number is reachable.
                                </p>
                            </div>

                            <!-- Nav -->
                            <div class="flex justify-between pt-2">
                                <button type="button" data-goto="1" class="btn-secondary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Back
                                </button>
                                <button type="button" data-goto="3" class="btn-primary">
                                    Next: Academic &amp; Docs
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div><!-- /step-2 -->


                        <!-- ─── STEP 3 : Academic Info + Documents ─── -->
                        <div id="step-3" class="form-step">

                            <!-- Academic -->
                            <div class="section-card">
                                <h3 class="section-title">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762z"/>
                                    </svg>
                                    Academic Information
                                </h3>
                                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="field-label">Student Type <span class="text-red-500">*</span></label>
                                        <select name="student_type" id="student_type" required
                                                class="field-input @error('student_type') is-error @enderror">
                                            <option value="">Select Student Type</option>
                                            @foreach(['Regular','Irregular','Transferee'] as $type)
                                                <option value="{{ $type }}" {{ old('student_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                            @endforeach
                                        </select>
                                        @error('student_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="field-label">Preferred Campus <span class="text-red-500">*</span></label>
                                        <select name="campus" id="campus" required
                                                class="field-input @error('campus') is-error @enderror">
                                            <option value="">Select Campus</option>
                                            @foreach($campuses as $campus)
                                                <option value="{{ $campus->name }}" {{ old('campus') == $campus->name ? 'selected' : '' }}>
                                                    {{ $campus->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('campus')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="field-label">College <span class="text-red-500">*</span></label>
                                        <select name="college" id="college" required
                                                class="field-input @error('college') is-error @enderror">
                                            <option value="">Select Campus first</option>
                                        </select>
                                        @error('college')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label class="field-label">Course / Program <span class="text-red-500">*</span></label>
                                        <select name="course" id="course" required
                                                class="field-input @error('course') is-error @enderror">
                                            <option value="">Select College first</option>
                                        </select>
                                        @error('course')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Documents -->
                            <div class="section-card">
                                <h3 class="section-title">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Required Documents
                                </h3>
                                <p class="text-xs text-gray-500 mb-4">
                                    Upload clear copies — max <strong>2 MB</strong> per file. Accepted formats: JPG, PNG, PDF.
                                </p>

                                <div class="grid sm:grid-cols-3 gap-4">
                                    <!-- Photo -->
                                    <div>
                                        <label class="field-label">2x2 Photo <span class="text-red-500">*</span></label>
                                        <div class="upload-zone @error('photo') is-error @enderror" id="zone-photo">
                                            <input type="file" name="photo" id="input-photo" required accept="image/*"
                                                   onchange="handleFileChange(this,'zone-photo','label-photo')">
                                            <div class="pointer-events-none">
                                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <p id="label-photo" class="text-xs text-gray-500 font-medium">Click or drag to upload</p>
                                                <p class="text-xs text-gray-400 mt-0.5">JPG or PNG only</p>
                                            </div>
                                        </div>
                                        @error('photo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>

                                    <!-- Birth Certificate -->
                                    <div>
                                        <label class="field-label">Birth Certificate (PSA) <span class="text-red-500">*</span></label>
                                        <div class="upload-zone @error('birth_certificate') is-error @enderror" id="zone-bc">
                                            <input type="file" name="birth_certificate" id="input-bc" required accept=".pdf,image/*"
                                                   onchange="handleFileChange(this,'zone-bc','label-bc')">
                                            <div class="pointer-events-none">
                                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                                </svg>
                                                <p id="label-bc" class="text-xs text-gray-500 font-medium">Click or drag to upload</p>
                                                <p class="text-xs text-gray-400 mt-0.5">PDF, JPG or PNG</p>
                                            </div>
                                        </div>
                                        @error('birth_certificate')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>

                                    <!-- Report Card -->
                                    <div>
                                        <label class="field-label">Report Card (Form 137) <span class="text-red-500">*</span></label>
                                        <div class="upload-zone @error('report_card') is-error @enderror" id="zone-rc">
                                            <input type="file" name="report_card" id="input-rc" required accept=".pdf,image/*"
                                                   onchange="handleFileChange(this,'zone-rc','label-rc')">
                                            <div class="pointer-events-none">
                                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                                </svg>
                                                <p id="label-rc" class="text-xs text-gray-500 font-medium">Click or drag to upload</p>
                                                <p class="text-xs text-gray-400 mt-0.5">PDF, JPG or PNG</p>
                                            </div>
                                        </div>
                                        @error('report_card')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Terms -->
                            <div class="flex items-start gap-3 p-4 bg-yellow-50 border border-yellow-200 rounded-xl mb-6">
                                <input type="checkbox" name="terms" id="terms" required
                                       class="mt-0.5 w-4 h-4 accent-yellow-500 flex-shrink-0">
                                <label for="terms" class="text-sm text-gray-700 cursor-pointer">
                                    I hereby certify that all information provided is <strong>true and correct</strong> to the best of my knowledge.
                                    I understand that any false information may result in the cancellation of my application.
                                    <span class="text-red-500">*</span>
                                </label>
                            </div>

                            <!-- Nav -->
                            <div class="flex justify-between items-center pt-2">
                                <button type="button" data-goto="2" class="btn-secondary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Back
                                </button>
                                <button type="submit" class="btn-submit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Submit Application
                                </button>
                            </div>
                        </div><!-- /step-3 -->

                    </div>
                </form>
            </div>

            <!-- Footer info -->
            <div class="mt-6 bg-blue-50 border-l-4 border-[#000035] p-4 rounded-r-xl">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-[#000035] mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="font-bold text-[#000035] text-sm mb-1">Important Reminders</h4>
                        <ul class="text-sm text-gray-600 list-disc list-inside space-y-0.5">
                            <li>All fields marked with <span class="text-red-500 font-bold">*</span> are required</li>
                            <li>Make sure your Gmail is active — admission updates will be sent there</li>
                            <li>Save your Application ID after submission to track your status</li>
                            <li>You can edit your application while it is still <strong>Pending</strong></li>
                            <li>For assistance: <span class="font-semibold">admissions@psu.edu.ph</span> | (054) 123-4567</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
    (function () {
        /* ── Campus data (built server-side) ── */
        var campusData = @json(
            $campuses->mapWithKeys(fn($c) => [
                $c->name => $c->colleges->mapWithKeys(fn($col) => [
                    $col->name => $col->courses->pluck('name')
                ])
            ])
        );

        /* ── Campus → College → Course cascade ── */
        function initCascade() {
            var campusSelect  = document.getElementById('campus');
            var collegeSelect = document.getElementById('college');
            var courseSelect  = document.getElementById('course');
            if (!campusSelect) return;

            campusSelect.addEventListener('change', function () {
                var campus = this.value;
                collegeSelect.innerHTML = '<option value="">Select College</option>';
                courseSelect.innerHTML  = '<option value="">Select Course</option>';
                if (campus && campusData[campus]) {
                    Object.keys(campusData[campus]).forEach(function (col) {
                        var o = document.createElement('option');
                        o.value = col; o.textContent = col;
                        collegeSelect.appendChild(o);
                    });
                }
            });

            collegeSelect.addEventListener('change', function () {
                var campus  = campusSelect.value;
                var college = this.value;
                courseSelect.innerHTML = '<option value="">Select Course</option>';
                if (campus && college && campusData[campus] && campusData[campus][college]) {
                    campusData[campus][college].forEach(function (c) {
                        var o = document.createElement('option');
                        o.value = c; o.textContent = c;
                        courseSelect.appendChild(o);
                    });
                }
            });

            /* Restore old values after a server-side validation failure */
            var oldCampus  = @json(old('campus',  ''));
            var oldCollege = @json(old('college', ''));
            var oldCourse  = @json(old('course',  ''));

            if (oldCampus) {
                campusSelect.value = oldCampus;
                campusSelect.dispatchEvent(new Event('change'));
                if (oldCollege) {
                    setTimeout(function () {
                        collegeSelect.value = oldCollege;
                        collegeSelect.dispatchEvent(new Event('change'));
                        setTimeout(function () { courseSelect.value = oldCourse; }, 80);
                    }, 80);
                }
            }
        }

        /* ── Multi-step navigation ── */
        var TITLES = {
            1: 'Personal Information',
            2: 'Guardian Information',
            3: 'Academic Info & Documents'
        };
        var current = 1;

        /* Required field names per step */
        var STEP_REQUIRED = {
            1: ['lastname','firstname','age','sex','civil_status','date_of_birth',
                'birth_place','temporary_address','permanent_address','contact_number','gmail_account'],
            2: ['guardian_name','guardian_relationship','guardian_phone'],
            3: ['student_type','campus','college','course']
        };

        function validateStep(stepNum) {
            var fields = STEP_REQUIRED[stepNum];
            if (!fields) return true;

            var valid = true;
            /* Remove any existing step-level error banner */
            var banner = document.getElementById('step-error-banner');
            if (banner) banner.remove();

            fields.forEach(function (name) {
                var el = document.querySelector('#step-' + stepNum + ' [name="' + name + '"]');
                if (!el) return;
                var empty = el.value.trim() === '' || (el.tagName === 'SELECT' && el.value === '');
                if (empty) {
                    el.classList.add('is-error');
                    valid = false;
                } else {
                    el.classList.remove('is-error');
                }
            });

            /* Gmail format check: if user included @, it must end with @gmail.com */
            if (stepNum === 1) {
                var gmailEl = document.querySelector('#step-1 [name="gmail_account"]');
                if (gmailEl && gmailEl.value.trim() && gmailEl.value.trim().indexOf('@') !== -1
                        && !gmailEl.value.trim().toLowerCase().endsWith('@gmail.com')) {
                    gmailEl.classList.add('is-error');
                    valid = false;
                }
            }

            if (!valid) {
                /* Show inline error banner at top of the current step */
                var stepEl = document.getElementById('step-' + stepNum);
                var div = document.createElement('div');
                div.id = 'step-error-banner';
                div.className = 'mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm flex items-center gap-2';
                div.innerHTML = '<svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>'
                    + '<span>Please fill in all required fields before proceeding.</span>';
                stepEl.insertBefore(div, stepEl.firstChild);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            return valid;
        }

        function goToStep(n) {
            n = parseInt(n, 10);
            if (isNaN(n) || n === current) return;

            /* Validate when moving FORWARD only */
            if (n > current && !validateStep(current)) return;
            if (n > current && current === 1 && hasDuplicateBlock()) {
                var dupBanner = document.getElementById('step-error-banner');
                if (!dupBanner) {
                    dupBanner = document.createElement('div');
                    dupBanner.id = 'step-error-banner';
                    dupBanner.className = 'mb-3 p-3 bg-red-50 border border-red-300 rounded-lg text-red-700 text-sm font-semibold';
                    dupBanner.textContent = 'Please resolve the duplicate warnings above before proceeding.';
                    var step1El = document.getElementById('step-1');
                    if (step1El) step1El.prepend(dupBanner);
                }
                return;
            }

            /* Remove error banner when navigating */
            var banner = document.getElementById('step-error-banner');
            if (banner) banner.remove();

            var from = document.getElementById('step-' + current);
            var to   = document.getElementById('step-' + n);
            if (!from || !to) return;

            // Hide all steps first to be safe
            document.querySelectorAll('.form-step').forEach(function(s) {
                s.classList.remove('active');
            });
            to.classList.add('active');
            current = n;

            /* update header bar */
            var title = document.getElementById('step-title');
            var ind   = document.getElementById('step-indicator');
            if (title) title.textContent = TITLES[n] || 'Admission Form';
            if (ind)   ind.textContent   = n;

            /* update progress circles */
            for (var i = 1; i <= 3; i++) {
                var circle = document.getElementById('circle-' + i);
                var label  = document.getElementById('label-'  + i);
                if (!circle || !label) continue;
                if (i < n) {
                    circle.className = 'step-circle done';
                    circle.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>';
                    label.classList.remove('current', 'upcoming');
                    label.classList.add('step-label', 'done');
                } else if (i === n) {
                    circle.className = 'step-circle current';
                    circle.textContent = i;
                    label.classList.remove('done', 'upcoming');
                    label.classList.add('step-label', 'current');
                } else {
                    circle.className = 'step-circle upcoming';
                    circle.textContent = i;
                    label.classList.remove('done', 'current');
                    label.classList.add('step-label', 'upcoming');
                }
                if (i < 3) {
                    var line = document.getElementById('line-' + i);
                    if (line) line.className = 'step-line ' + (i < n ? 'done' : 'upcoming');
                }
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function initSteps() {
            /* Wire data-goto buttons */
            document.querySelectorAll('button[data-goto]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    goToStep(parseInt(this.getAttribute('data-goto'), 10));
                });
            });

            /* Make step circles + labels clickable */
            for (var i = 1; i <= 3; i++) {
                (function (n) {
                    ['circle-' + n, 'label-' + n].forEach(function (id) {
                        var el = document.getElementById(id);
                        if (el) {
                            el.style.cursor = 'pointer';
                            el.addEventListener('click', function () { goToStep(n); });
                        }
                    });
                })(i);
            }

            /* Jump to error step on server-side validation failure */
            @if($errors->any())
            var step1 = ['lastname','firstname','middlename','age','sex','civil_status',
                         'date_of_birth','birth_place','temporary_address','permanent_address',
                         'contact_number','gmail_account'];
            var step2 = ['guardian_name','guardian_relationship','guardian_phone'];
            var errorKeys = @json(array_keys($errors->toArray()));
            if (errorKeys.some(function(k){ return step1.indexOf(k) !== -1; })) {
                goToStep(1);
            } else if (errorKeys.some(function(k){ return step2.indexOf(k) !== -1; })) {
                goToStep(2);
            } else {
                goToStep(3);
            }
            @endif
        }

        /* ── File upload feedback ── */
        function handleFileChange(input, zoneId, labelId) {
            var zone  = document.getElementById(zoneId);
            var label = document.getElementById(labelId);
            if (input.files.length > 0) {
                var name = input.files[0].name;
                label.textContent = name.length > 28 ? name.slice(0, 26) + '…' : name;
                zone.classList.add('has-file');
                zone.classList.remove('is-error');
            }
        }

        /* ── Duplicate checking ── */
        var duplicateFlags = { gmail_account: false, name: false };

        function showDuplicateWarning(key, msg) {
            duplicateFlags[key] = true;
            var id = 'dup-warn-' + key;
            if (document.getElementById(id)) return;

            var el = document.createElement('div');
            el.id = id;
            el.style.cssText = 'display:flex;align-items:flex-start;gap:10px;background:#fff1f2;border:1.5px solid #f87171;border-radius:8px;padding:10px 14px;margin-top:10px;width:100%;box-sizing:border-box;';

            var icon = document.createElement('div');
            icon.style.cssText = 'flex-shrink:0;width:18px;height:18px;background:#dc2626;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-top:1px;';
            icon.innerHTML = '<svg width="10" height="10" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';

            var text = document.createElement('span');
            text.textContent = msg;
            text.style.cssText = 'font-size:13px;font-weight:600;color:#b91c1c;line-height:1.5;';

            el.appendChild(icon);
            el.appendChild(text);

            var anchor;
            if (key === 'gmail_account') {
                /* parent div that holds label + flex-row input */
                anchor = document.querySelector('[name="gmail_account"]').closest('.flex').parentElement;
                anchor.appendChild(el);
            } else {
                /* insert after the grid row that holds the name inputs */
                var grid = document.querySelector('[name="lastname"]').closest('.grid');
                if (!grid) grid = document.querySelector('[name="lastname"]').parentElement.parentElement;
                grid.insertAdjacentElement('afterend', el);
            }
        }

        function clearDuplicateWarning(key) {
            duplicateFlags[key] = false;
            var el = document.getElementById('dup-warn-' + key);
            if (el) el.remove();
        }

        function hasDuplicateBlock() {
            return duplicateFlags.gmail_account || duplicateFlags.name;
        }

        function runDuplicateCheck() {
            var email    = document.querySelector('[name="gmail_account"]').value.trim();
            var first    = document.querySelector('[name="firstname"]').value.trim();
            var last     = document.querySelector('[name="lastname"]').value.trim();
            if (!email && !first && !last) return;

            var body = new URLSearchParams();
            body.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            // Server normalizes: appends @gmail.com if no @ is present
            var emailToCheck = email;
            if (email)         body.append('gmail_account', emailToCheck);
            if (first && last) { body.append('firstname', first); body.append('lastname', last); }

            fetch('{{ route("student.check-duplicate") }}', { method: 'POST', body: body })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    ['gmail_account', 'name'].forEach(function (k) {
                        if (data.conflicts && data.conflicts[k]) {
                            showDuplicateWarning(k, data.conflicts[k]);
                            if (k === 'gmail_account') document.querySelector('[name="gmail_account"]').classList.add('is-error');
                            if (k === 'name') { document.querySelector('[name="firstname"]').classList.add('is-error'); document.querySelector('[name="lastname"]').classList.add('is-error'); }
                        } else {
                            clearDuplicateWarning(k);
                            if (k === 'gmail_account') document.querySelector('[name="gmail_account"]').classList.remove('is-error');
                            if (k === 'name') { document.querySelector('[name="firstname"]').classList.remove('is-error'); document.querySelector('[name="lastname"]').classList.remove('is-error'); }
                        }
                    });
                })
                .catch(function () {});
        }

        /* ── Boot everything when DOM is ready ── */
        document.addEventListener('DOMContentLoaded', function () {
            initCascade();
            initSteps();

            /* Clear error highlighting on input */
            document.querySelectorAll('.field-input').forEach(function (f) {
                f.addEventListener('input',  function () { f.classList.remove('is-error'); });
                f.addEventListener('change', function () { f.classList.remove('is-error'); });
            });

            /* Duplicate checks on blur */
            ['gmail_account', 'firstname', 'lastname'].forEach(function (n) {
                var el = document.querySelector('[name="' + n + '"]');
                if (el) el.addEventListener('blur', runDuplicateCheck);
            });

            /* Gmail: auto-strip @gmail.com if user types the full address */
            var gmailInput = document.querySelector('[name="gmail_account"]');
            if (gmailInput) {
                gmailInput.addEventListener('input', function () {
                    if (this.value.toLowerCase().endsWith('@gmail.com')) {
                        var pos = this.selectionStart - 10;
                        this.value = this.value.slice(0, -10);
                        if (pos >= 0) this.setSelectionRange(pos, pos);
                    }
                    this.classList.remove('is-error');
                });
            }

            /* Clear terms error styling when user checks the box */
            var termsEl = document.getElementById('terms');
            if (termsEl) {
                termsEl.addEventListener('change', function () {
                    var wrapper = this.closest('div');
                    if (wrapper) { wrapper.style.borderColor = ''; wrapper.style.background = ''; }
                    var err = document.getElementById('terms-error');
                    if (err) err.remove();
                });
            }

        });

        /* Expose handleFileChange globally (used via onchange attribute) */
        window.handleFileChange = handleFileChange;

        /* Validate step-3 fields, file uploads, and terms before submitting */
        function validateBeforeSubmit() {
            var valid = validateStep(3);

            /* Required file uploads */
            [
                { id: 'input-photo', zone: 'zone-photo' },
                { id: 'input-bc',    zone: 'zone-bc'    },
                { id: 'input-rc',    zone: 'zone-rc'    }
            ].forEach(function (fc) {
                var inp  = document.getElementById(fc.id);
                var zone = document.getElementById(fc.zone);
                if (inp && zone && inp.files.length === 0) {
                    zone.classList.add('is-error');
                    valid = false;
                }
            });

            /* Terms must be accepted */
            var terms = document.getElementById('terms');
            if (terms && !terms.checked) {
                var wrapper = terms.closest('div');
                if (wrapper) { wrapper.style.borderColor = '#ef4444'; wrapper.style.background = '#fff5f5'; }
                if (!document.getElementById('terms-error')) {
                    var termsErr = document.createElement('p');
                    termsErr.id = 'terms-error';
                    termsErr.className = 'text-red-600 text-sm mt-2 font-semibold';
                    termsErr.textContent = 'You must agree to the terms and conditions before submitting.';
                    terms.closest('div').insertAdjacentElement('afterend', termsErr);
                }
                valid = false;
            }

            return valid;
        }

        /* Validate before submit, then lock button to prevent double-submission */
        document.querySelector('form').addEventListener('submit', function (e) {
            if (!validateBeforeSubmit()) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }
            var btn = this.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Submitting…';
            }
        });

    })();
    </script>
</body>
</html>
