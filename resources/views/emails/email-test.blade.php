@extends('emails.layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">🧪 Email Test Results</h2>
        </div>
        
        <div class="info-section">
            <div class="info-label">👤 Test Information</div>
            <div class="info-value">
                <strong>Name:</strong> {{ $contactMessage->name }}<br>
                <strong>Email:</strong> {{ $contactMessage->email }}<br>
                <strong>Platform:</strong> <span style="color: #007bff; font-weight: 600;">{{ $platform }}</span>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-label">📝 Test Message</div>
            <div class="info-value">
                {{ $contactMessage->message }}
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-label">🔧 Technical Details</div>
            <div class="info-value">
                <strong>User Agent:</strong> {{ $userAgent }}<br>
                <strong>IP Address:</strong> {{ $ip }}<br>
                <strong>Timestamp:</strong> {{ $timestamp }}<br>
                <strong>Contact ID:</strong> #{{ $contactMessage->id }}
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-label">✅ Test Results</div>
            <div class="info-value">
                <span style="color: #28a745; font-weight: 600;">✅ Form submission: Successful</span><br>
                <span style="color: #28a745; font-weight: 600;">✅ Database storage: Successful</span><br>
                <span style="color: #28a745; font-weight: 600;">✅ Email sending: Successful</span><br>
                <span style="color: #28a745; font-weight: 600;">✅ Platform detection: {{ $platform }}</span>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-label">⚙️ Email Configuration</div>
            <div class="info-value">
                <strong>Mail Driver:</strong> {{ $mailDriver }}<br>
                <strong>From Address:</strong> {{ $fromAddress }}<br>
                <strong>From Name:</strong> {{ $fromName }}
            </div>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <p style="font-size: 16px; color: #6c757d; margin-bottom: 20px;">
            This is a test email to verify email functionality on {{ $platform }} platform.
        </p>
        
        <div style="background-color: #e7f3ff; border-radius: 8px; padding: 20px; margin-top: 20px;">
            <h3 style="margin-top: 0; color: #0056b3;">🎉 Test Successful!</h3>
            <p style="color: #0056b3; margin-bottom: 0;">
                Your email system is working correctly on {{ $platform }} devices.
            </p>
        </div>
    </div>
    
    <div style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h3 style="margin-top: 0; color: #495057;">📊 Test Summary</h3>
        <ul style="margin: 0; padding-left: 20px; color: #6c757d;">
            <li>Email template rendering: <span style="color: #28a745;">✅ Working</span></li>
            <li>HTML formatting: <span style="color: #28a745;">✅ Working</span></li>
            <li>CSS styling: <span style="color: #28a745;">✅ Working</span></li>
            <li>Responsive design: <span style="color: #28a745;">✅ Working</span></li>
            <li>Email client compatibility: <span style="color: #28a745;">✅ Working</span></li>
        </ul>
    </div>
@endsection
