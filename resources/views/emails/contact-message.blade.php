@extends('emails.layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">💬 New Contact Message</h2>
        </div>
        
        <div class="info-section">
            <div class="info-label">👤 Contact Information</div>
            <div class="info-value">
                <strong>Name:</strong> {{ $contactMessage->name }}<br>
                <strong>Email:</strong> {{ $contactMessage->email }}
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-label">📝 Message</div>
            <div class="info-value">
                {{ $contactMessage->message }}
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-label">📅 Submission Details</div>
            <div class="info-value">
                <strong>Submitted:</strong> {{ $contactMessage->created_at->format('F j, Y \a\t g:i A') }}<br>
                <strong>Message ID:</strong> #{{ $contactMessage->id }}<br>
                <strong>Status:</strong> <span style="color: #28a745; font-weight: 600;">New</span>
            </div>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <p style="font-size: 16px; color: #6c757d; margin-bottom: 20px;">
            Please respond to this contact message as soon as possible.
        </p>
        
        <a href="mailto:{{ $contactMessage->email }}?subject=Re: Contact Message #{{ $contactMessage->id }}" class="btn">
            📧 Reply to Customer
        </a>
        
        <a href="tel:0449598440" class="btn btn-secondary">
            📞 Call Customer
        </a>
    </div>
    
    <div style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h3 style="margin-top: 0; color: #495057;">💡 Quick Actions</h3>
        <ul style="margin: 0; padding-left: 20px; color: #6c757d;">
            <li>Review the customer's inquiry</li>
            <li>Prepare a helpful response</li>
            <li>Include relevant information about services</li>
            <li>Offer to schedule a consultation if needed</li>
            <li>Follow up within 24 hours</li>
        </ul>
    </div>
@endsection
