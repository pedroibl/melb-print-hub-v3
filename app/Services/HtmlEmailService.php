<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use App\Models\ContactMessage;
use App\Models\QuoteRequest;

class HtmlEmailService
{
    /**
     * Send HTML email for quote request notification
     */
    public function sendQuoteRequestNotification(QuoteRequest $quoteRequest): bool
    {
        try {
            Mail::send('emails.quote-request', [
                'quoteRequest' => $quoteRequest,
                'subject' => 'New Quote Request - Melbourne Print Hub',
                'headerSubtitle' => 'Professional Printing Solutions'
            ], function($message) use ($quoteRequest) {
                $message->to('info@melbourneprinthub.com.au')
                        ->subject('New Quote Request - Melbourne Print Hub')
                        ->from('info@melbourneprinthub.com.au', 'Melbourne Print Hub Website');
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send quote request HTML email', [
                'error' => $e->getMessage(),
                'quote_request_id' => $quoteRequest->id
            ]);
            return false;
        }
    }

    /**
     * Send HTML email for contact message notification
     */
    public function sendContactMessageNotification(ContactMessage $contactMessage): bool
    {
        try {
            Mail::send('emails.contact-message', [
                'contactMessage' => $contactMessage,
                'subject' => 'New Contact Message - Melbourne Print Hub',
                'headerSubtitle' => 'Professional Printing Solutions'
            ], function($message) use ($contactMessage) {
                $message->to('info@melbourneprinthub.com.au')
                        ->subject('New Contact Message - Melbourne Print Hub')
                        ->from('info@melbourneprinthub.com.au', 'Melbourne Print Hub Website');
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send contact message HTML email', [
                'error' => $e->getMessage(),
                'contact_message_id' => $contactMessage->id
            ]);
            return false;
        }
    }

    /**
     * Send HTML email for email testing
     */
    public function sendEmailTestNotification(ContactMessage $contactMessage, array $testData): bool
    {
        try {
            Mail::send('emails.email-test', [
                'contactMessage' => $contactMessage,
                'platform' => $testData['platform'] ?? 'unknown',
                'userAgent' => $testData['user_agent'] ?? 'unknown',
                'ip' => $testData['ip'] ?? 'unknown',
                'timestamp' => $testData['timestamp'] ?? now()->format('Y-m-d H:i:s'),
                'mailDriver' => config('mail.default'),
                'fromAddress' => config('mail.from.address'),
                'fromName' => config('mail.from.name'),
                'subject' => 'Email Test - Melbourne Print Hub',
                'headerSubtitle' => 'Email System Testing'
            ], function($message) use ($testData) {
                $message->to('info@melbourneprinthub.com.au')
                        ->subject('Email Test - Melbourne Print Hub (' . ($testData['platform'] ?? 'unknown') . ')')
                        ->from('info@melbourneprinthub.com.au', 'Melbourne Print Hub Website');
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send email test HTML email', [
                'error' => $e->getMessage(),
                'contact_message_id' => $contactMessage->id
            ]);
            return false;
        }
    }

    /**
     * Send HTML confirmation email to customer for quote request
     */
    public function sendCustomerQuoteConfirmation(QuoteRequest $quoteRequest): bool
    {
        try {
            Mail::send('emails.customer-quote-confirmation', [
                'quoteRequest' => $quoteRequest,
                'subject' => 'Quote Request Received - Melbourne Print Hub',
                'headerSubtitle' => 'Professional Printing Solutions'
            ], function($message) use ($quoteRequest) {
                $message->to($quoteRequest->email)
                        ->subject('Quote Request Received - Melbourne Print Hub')
                        ->from('info@melbourneprinthub.com.au', 'Melbourne Print Hub');
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send customer quote confirmation HTML email', [
                'error' => $e->getMessage(),
                'quote_request_id' => $quoteRequest->id,
                'customer_email' => $quoteRequest->email
            ]);
            return false;
        }
    }

    /**
     * Send HTML confirmation email to customer for contact message
     */
    public function sendCustomerContactConfirmation(ContactMessage $contactMessage): bool
    {
        try {
            Mail::send('emails.customer-contact-confirmation', [
                'contactMessage' => $contactMessage,
                'subject' => 'Message Received - Melbourne Print Hub',
                'headerSubtitle' => 'Professional Printing Solutions'
            ], function($message) use ($contactMessage) {
                $message->to($contactMessage->email)
                        ->subject('Message Received - Melbourne Print Hub')
                        ->from('info@melbourneprinthub.com.au', 'Melbourne Print Hub');
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send customer contact confirmation HTML email', [
                'error' => $e->getMessage(),
                'contact_message_id' => $contactMessage->id,
                'customer_email' => $contactMessage->email
            ]);
            return false;
        }
    }

    /**
     * Generate HTML email content for testing purposes
     */
    public function generateTestEmailContent(ContactMessage $contactMessage, array $testData): string
    {
        return View::make('emails.email-test', [
            'contactMessage' => $contactMessage,
            'platform' => $testData['platform'] ?? 'unknown',
            'userAgent' => $testData['user_agent'] ?? 'unknown',
            'ip' => $testData['ip'] ?? 'unknown',
            'timestamp' => $testData['timestamp'] ?? now()->format('Y-m-d H:i:s'),
            'mailDriver' => config('mail.default'),
            'fromAddress' => config('mail.from.address'),
            'fromName' => config('mail.from.name'),
            'subject' => 'Email Test - Melbourne Print Hub',
            'headerSubtitle' => 'Email System Testing'
        ])->render();
    }
}
