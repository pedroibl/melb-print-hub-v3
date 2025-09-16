@extends('emails.layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">📋 New Quote Request</h2>
        </div>
        
        <div class="info-section">
            <div class="info-label">👤 Customer Information</div>
            <div class="info-value">
                <strong>Name:</strong> {{ $quoteRequest->name }}<br>
                <strong>Email:</strong> {{ $quoteRequest->email }}<br>
                <strong>Phone:</strong> {{ $quoteRequest->phone }}
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-label">🖨️ Project Details</div>
            <div class="info-value">
                <strong>Service:</strong> {{ $quoteRequest->service }}<br>
                @if($quoteRequest->service_category)
                    <strong>Category:</strong> {{ $quoteRequest->service_category }}<br>
                @endif
                <strong>Quantity:</strong> {{ $quoteRequest->quantity }}<br>
                @if($quoteRequest->size)
                    <strong>Size:</strong> {{ $quoteRequest->size }}<br>
                @endif
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-label">📝 Project Description</div>
            <div class="info-value">
                {{ $quoteRequest->description }}
            </div>
        </div>
        
        @if($quoteRequest->delivery_address)
        <div class="info-section">
            <div class="info-label">📍 Delivery Address</div>
            <div class="info-value">
                {{ $quoteRequest->delivery_address }}
            </div>
        </div>
        @endif
        
        @if($quoteRequest->special_requirements)
        <div class="info-section">
            <div class="info-label">✨ Special Requirements</div>
            <div class="info-value">
                {{ $quoteRequest->special_requirements }}
            </div>
        </div>
        @endif
        
        @if($quoteRequest->artwork_file)
        <div class="info-section">
            <div class="info-label">🎨 Artwork Files</div>
            <div class="info-value">
                <strong>File:</strong> {{ $quoteRequest->artwork_file }}<br>
                <em>Artwork file has been uploaded and is available for review.</em>
            </div>
        </div>
        @endif
        
        <div class="info-section">
            <div class="info-label">📅 Submission Details</div>
            <div class="info-value">
                <strong>Submitted:</strong> {{ $quoteRequest->created_at->format('F j, Y \a\t g:i A') }}<br>
                <strong>Request ID:</strong> #{{ $quoteRequest->id }}<br>
                <strong>Status:</strong> <span style="color: #28a745; font-weight: 600;">New</span>
            </div>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <p style="font-size: 16px; color: #6c757d; margin-bottom: 20px;">
            Please review this quote request and respond to the customer within 24 hours.
        </p>
        
        <a href="mailto:{{ $quoteRequest->email }}?subject=Re: Quote Request #{{ $quoteRequest->id }} - {{ $quoteRequest->service }}" class="btn">
            📧 Reply to Customer
        </a>
        
        <a href="tel:0449598440" class="btn btn-secondary">
            📞 Call Customer
        </a>
    </div>
    
    <div style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin-top: 30px;">
        <h3 style="margin-top: 0; color: #495057;">💡 Next Steps</h3>
        <ul style="margin: 0; padding-left: 20px; color: #6c757d;">
            <li>Review the project requirements and specifications</li>
            <li>Calculate pricing based on quantity, size, and materials</li>
            <li>Check artwork files if provided</li>
            <li>Prepare a detailed quote with timeline</li>
            <li>Contact the customer within 24 hours</li>
        </ul>
    </div>
@endsection
