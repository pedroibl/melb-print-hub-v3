@extends('emails.layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">✅ Quote Request Received</h2>
        </div>
        
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="background-color: #d4edda; border-radius: 50%; width: 80px; height: 80px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <span style="font-size: 40px;">✅</span>
            </div>
            <h2 style="color: #155724; margin: 0;">Thank You!</h2>
            <p style="color: #6c757d; font-size: 18px; margin: 10px 0 0 0;">
                We've received your quote request and will get back to you within 24 hours.
            </p>
        </div>
        
        <div class="info-section">
            <div class="info-label">📋 Request Summary</div>
            <div class="info-value">
                <strong>Service:</strong> {{ $quoteRequest->service }}<br>
                <strong>Quantity:</strong> {{ $quoteRequest->quantity }}<br>
                @if($quoteRequest->size)
                    <strong>Size:</strong> {{ $quoteRequest->size }}<br>
                @endif
                <strong>Request ID:</strong> #{{ $quoteRequest->id }}
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-label">📝 Project Description</div>
            <div class="info-value">
                {{ $quoteRequest->description }}
            </div>
        </div>
        
        @if($quoteRequest->special_requirements)
        <div class="info-section">
            <div class="info-label">✨ Special Requirements</div>
            <div class="info-value">
                {{ $quoteRequest->special_requirements }}
            </div>
        </div>
        @endif
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <p style="font-size: 16px; color: #6c757d; margin-bottom: 20px;">
            Our team will review your requirements and prepare a detailed quote for you.
        </p>
        
        <a href="tel:0449598440" class="btn">
            📞 Call Us Now
        </a>
        
        <a href="https://wa.me/+61449598440?text=Hi! I have a question about my quote request #{{ $quoteRequest->id }}" class="btn btn-secondary">
            💬 WhatsApp Us
        </a>
    </div>
    
    <div style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h3 style="margin-top: 0; color: #495057;">⏰ What Happens Next?</h3>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <div style="display: flex; align-items: center;">
                <div style="background-color: #667eea; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 15px;">1</div>
                <div>
                    <strong>Review & Analysis</strong><br>
                    <span style="color: #6c757d; font-size: 14px;">We'll review your requirements and specifications</span>
                </div>
            </div>
            <div style="display: flex; align-items: center;">
                <div style="background-color: #667eea; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 15px;">2</div>
                <div>
                    <strong>Quote Preparation</strong><br>
                    <span style="color: #6c757d; font-size: 14px;">We'll calculate pricing and prepare your detailed quote</span>
                </div>
            </div>
            <div style="display: flex; align-items: center;">
                <div style="background-color: #667eea; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 15px;">3</div>
                <div>
                    <strong>Contact You</strong><br>
                    <span style="color: #6c757d; font-size: 14px;">We'll reach out within 24 hours with your quote</span>
                </div>
            </div>
        </div>
    </div>
    
    <div style="background-color: #e7f3ff; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h3 style="margin-top: 0; color: #0056b3;">💡 Need Immediate Assistance?</h3>
        <p style="color: #0056b3; margin-bottom: 15px;">
            If you need urgent help or have questions, don't hesitate to contact us:
        </p>
        <div style="text-align: center;">
            <p style="margin: 5px 0;"><strong>📞 Phone:</strong> 0449 598 440</p>
            <p style="margin: 5px 0;"><strong>📧 Email:</strong> info@melbourneprinthub.com.au</p>
            <p style="margin: 5px 0;"><strong>🕒 Hours:</strong> Monday to Friday, 08:00 AM to 06:00 PM</p>
        </div>
    </div>
@endsection
