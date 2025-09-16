<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use App\Models\ContactMessage;
use App\Jobs\SendContactEmailJob;
use Illuminate\Support\Facades\Session;
use App\Services\HtmlEmailService;
use App\Services\HumanVerificationService;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    protected $htmlEmailService;
    protected $humanVerificationService;

    public function __construct(HtmlEmailService $htmlEmailService, HumanVerificationService $humanVerificationService)
    {
        $this->htmlEmailService = $htmlEmailService;
        $this->humanVerificationService = $humanVerificationService;
    }

    /**
     * Display the contact page
     */
    public function show()
    {
        // Ensure session is started and CSRF token is available
        if (!Session::isStarted()) {
            Session::start();
        }
        
        // Log session info for debugging
        $userAgent = request()->userAgent() ?? '';
        $isMobile = preg_match('/(android|iphone|ipad|mobile|tablet)/i', $userAgent);
        
        Log::info('Contact page accessed', [
            'user_agent' => $userAgent,
            'is_mobile' => $isMobile,
            'session_id' => Session::getId(),
            'csrf_token' => csrf_token(),
            'session_lifetime' => config('session.lifetime')
        ]);

        // Determine effective CAPTCHA settings (disable if keys missing)
        $configuredType = config('captcha.verification_type');
        $recaptchaSiteKey = config('captcha.sitekey');
        $hcaptchaSiteKey = config('captcha.hcaptcha_sitekey');
        $effectiveType = $configuredType;
        if ($configuredType === 'recaptcha' && empty($recaptchaSiteKey)) {
            $effectiveType = 'none';
        }
        if ($configuredType === 'hcaptcha' && empty($hcaptchaSiteKey)) {
            $effectiveType = 'none';
        }

        return Inertia::render('Contact', [
            'phone' => '0449 598 440',
            'email' => 'info@melbourneprinthub.com.au',
            'hours' => 'Monday to Friday, 08:00 AM to 06:00 PM',
            'csrf_token' => csrf_token(), // Explicitly pass CSRF token
            'captcha' => [
                'type' => $effectiveType,
                'recaptcha_sitekey' => $recaptchaSiteKey,
                'hcaptcha_sitekey' => $hcaptchaSiteKey,
            ]
        ]);
    }

    /**
     * Process the contact message
     */
    public function store(Request $request)
    {
        // Log the request for debugging
        Log::info('Contact request received', [
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'has_csrf_token' => $request->hasHeader('X-CSRF-TOKEN'),
            'csrf_token_header' => $request->header('X-CSRF-TOKEN'),
            'session_id' => $request->session()->getId(),
            'session_token' => $request->session()->token(),
            'timestamp' => now()->toISOString(),
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'data' => $request->all()
        ]);

        // Determine effective CAPTCHA settings (disable if keys missing)
        $configuredType = config('captcha.verification_type');
        $recaptchaSiteKey = config('captcha.sitekey');
        $hcaptchaSiteKey = config('captcha.hcaptcha_sitekey');
        $effectiveType = $configuredType;
        if ($configuredType === 'recaptcha' && empty($recaptchaSiteKey)) {
            $effectiveType = 'none';
        }
        if ($configuredType === 'hcaptcha' && empty($hcaptchaSiteKey)) {
            $effectiveType = 'none';
        }

        // Human verification check
        $verificationResult = $this->humanVerificationService->verifySubmission(
            $request, 
            $effectiveType
        );
        
        if (!$verificationResult['success']) {
            Log::warning('Contact form verification failed', [
                'errors' => $verificationResult['errors'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            return response()->json([
                'error' => 'Verification failed. Please try again.',
                'verification_errors' => $verificationResult['errors']
            ], 422);
        }

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000'
        ]);

        Log::info('Contact validation passed', ['validated' => $validated]);

        try {
            // Store in database
            $contactMessage = ContactMessage::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'message' => $validated['message'],
                'status' => 'new'
            ]);

            Log::info('Contact message created', ['contact_message_id' => $contactMessage->id]);

            // Dispatch job to send emails in background (FASTER!)
            SendContactEmailJob::dispatch($contactMessage);

            // Log successful submission
            Log::info('Contact message processed successfully', [
                'contact_message_id' => $contactMessage->id,
                'email' => $validated['email']
            ]);

            // Return Inertia response with success message
            Log::info('Returning Inertia response with success message');
            return Inertia::render('Contact', [
                'phone' => '0449 598 440',
                'email' => 'info@melbourneprinthub.com.au',
                'hours' => 'Monday to Friday, 08:00 AM to 06:00 PM',
                'success' => 'Thank you! Your message has been sent successfully. We\'ll get back to you within 24 hours.',
                'contactMessage' => $contactMessage,
                'captcha' => [
                    'type' => $effectiveType,
                    'recaptcha_sitekey' => $recaptchaSiteKey,
                    'hcaptcha_sitekey' => $hcaptchaSiteKey,
                ]
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Contact message failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);

            // Return Inertia response with error message
            return Inertia::render('Contact', [
                'phone' => '0449 598 440',
                'email' => 'info@melbourneprinthub.com.au',
                'hours' => 'Monday to Friday, 08:00 AM to 06:00 PM',
                'error' => 'Sorry, there was an error sending your message. Please try again or call us directly at 0449 598 440.',
                'errors' => ['general' => 'Sorry, there was an error sending your message. Please try again or call us directly at 0449 598 440.'],
                'captcha' => [
                    'type' => $effectiveType,
                    'recaptcha_sitekey' => $recaptchaSiteKey,
                    'hcaptcha_sitekey' => $hcaptchaSiteKey,
                ]
            ]);
        }
    }

    /**
     * Show the thanks page
     */
    public function thanks()
    {
        return view('thanks');
    }
}
