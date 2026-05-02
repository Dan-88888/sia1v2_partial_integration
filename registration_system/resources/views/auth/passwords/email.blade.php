<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/university_portal.css') }}">
</head>
<body style="display:flex; flex-direction:column; min-height:100vh;">
    <div class="login-top-bar"></div>
    <div class="login-content">
        <div class="login-box">
            <div class="login-logo">
                <img src="{{ asset('images/nobgParsulogo.png') }}" alt="Logo">
            </div>
            <h2 class="login-university-name">Reset Password</h2>
            <p class="login-location">Enter your email to receive a password reset link.</p>

            @if (session('status'))
                <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:8px; margin-bottom:20px; font-size:0.85rem; border:1px solid #bbf7d0;">
                    {{ session('status') }}
                </div>
            @endif

            <div class="login-form">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="login-field">
                        <label for="email">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span style="color:#ef4444; font-size:0.75rem; display:block; margin-top:4px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="login-btn">
                        Send Password Reset Link
                    </button>
                </form>
            </div>

            <a href="{{ url('/') }}?role={{ request('role', 'student') }}" style="display:block; margin-top:20px; color:var(--text-light); font-size:0.85rem; text-decoration:none; font-weight:600;">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>
</body>
</html>
