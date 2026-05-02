<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Application - ParSU Registration</title>
    <link rel="stylesheet" href="{{ asset('css/university_portal.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f1f5f9; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .status-container { background: white; width: 100%; max-width: 550px; padding: 40px; border-radius: 20px; shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .status-header { text-align: center; margin-bottom: 30px; }
        .tracking-box { background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 25px; text-align: center; }
        .status-badge { display: inline-block; padding: 8px 16px; border-radius: 50px; font-weight: 600; font-size: 0.9em; margin-top: 10px; }
        .status-Pending { background: #fef3c7; color: #92400e; }
        .status-Approved { background: #dcfce7; color: #166534; }
        .status-Rejected { background: #fee2e2; color: #991b1b; }
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .info-label { color: #64748b; font-size: 0.9em; }
        .info-value { color: #1e293b; font-weight: 600; }
        .back-link { display: block; text-align: center; margin-top: 30px; color: var(--primary-color); text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="status-container">
        <div class="status-header">
            <img src="{{ asset('images/nobgParsulogo.png') }}" alt="Logo" style="height: 60px; margin-bottom: 15px;">
            <h2 style="margin:0;">Application Status</h2>
            <p style="color:#64748b; font-size: 0.9em;">Tracking Information for {{ $application->name }}</p>
        </div>

        <div class="tracking-box">
            <div style="font-size: 0.8em; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Tracking Number</div>
            <div style="font-size: 1.5em; font-family: monospace; color: var(--secondary-color); margin: 5px 0;">{{ $application->tracking_number }}</div>
            <span class="status-badge status-{{ $application->status }}">{{ strtoupper($application->status) }}</span>
        </div>

        <div class="info-list">
            <div class="info-row">
                <span class="info-label">Application Type</span>
                <span class="info-value">{{ ucfirst($application->type) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Submission Date</span>
                <span class="info-value">{{ $application->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Contact Email</span>
                <span class="info-value">{{ $application->email }}</span>
            </div>
            @if($application->remarks)
            <div class="info-row" style="flex-direction: column; border-bottom: none;">
                <span class="info-label" style="margin-bottom: 5px;">Admin Remarks</span>
                <div style="background: #f1f5f9; padding: 15px; border-radius: 8px; font-size: 0.9em; color: #475569;">
                    {{ $application->remarks }}
                </div>
            </div>
            @endif

            @if($application->status === 'Approved')
            <div style="margin-top: 25px; padding: 20px; background: #eff6ff; border-radius: 12px; border: 1px solid #bfdbfe; color: #1e40af; font-size: 0.9em;">
                <strong>Congratulations!</strong> Your application has been approved. You can now log in using:<br><br>
                <strong>Email:</strong> <code>{{ $application->email }}</code><br>
                <strong>Password:</strong> <code>{{ $application->temp_password }}</code>
            </div>
            @endif
        </div>

        <a href="{{ url('/') }}{{ request('role') ? '?role=' . request('role') : '' }}" class="back-link">← Return to Portal</a>
    </div>
</body>
</html>
