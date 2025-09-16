<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\QuoteRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Jobs\SendQuoteEmailJob;
use App\Services\HtmlEmailService;
use App\Services\HumanVerificationService;

class QuoteController extends Controller
{
    protected $htmlEmailService;
    protected $humanVerificationService;

    public function __construct(HtmlEmailService $htmlEmailService, HumanVerificationService $humanVerificationService)
    {
        $this->htmlEmailService = $htmlEmailService;
        $this->humanVerificationService = $humanVerificationService;
    }

    /**
     * Display the quote request form
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
        
        Log::info('Quote page accessed', [
            'user_agent' => $userAgent,
            'is_mobile' => $isMobile,
            'session_id' => Session::getId(),
            'csrf_token' => csrf_token(),
            'session_lifetime' => config('session.lifetime')
        ]);

        $services = Product::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

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

        return Inertia::render('Quote', [
            'services' => $services,
            'phone' => '0449 598 440',
            'email' => 'info@melbourneprinthub.com.au',
            'csrf_token' => csrf_token(), // Explicitly pass CSRF token
            'captcha' => [
                'type' => $effectiveType,
                'recaptcha_sitekey' => $recaptchaSiteKey,
                'hcaptcha_sitekey' => $hcaptchaSiteKey,
            ]
        ]);
    }

    /**
     * Process the quote request
     */
    public function store(Request $request)
    {
        // Log the request for debugging
        Log::info('Quote request received', [
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
            Log::warning('Quote form verification failed', [
                'errors' => $verificationResult['errors'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            return response()->json([
                'error' => 'Verification failed. Please try again.',
                'verification_errors' => $verificationResult['errors']
            ], 422);
        }

        // Validate the request with all new fields
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'service' => 'required|string|max:255',
            'serviceCategory' => 'nullable|string|max:255',
            'description' => 'required|string|max:1000',
            'quantity' => 'required|string|max:255',
            'size' => 'nullable|string|max:255',
            'artwork' => 'nullable|file|mimes:pdf,ai,eps,psd,jpg,jpeg,png,tiff|max:51200',
            'addressStreet' => 'required|string|max:255',
            'addressSuburb' => 'required|string|max:255',
            'addressState' => 'required|string|in:ACT,NSW,NT,QLD,SA,TAS,VIC,WA',
            'addressPostcode' => ['nullable','regex:/^\\d{4}$/'],
            'specialRequirements' => 'nullable|string|max:1000'
        ]);

        Log::info('Quote validation passed', ['validated' => $validated]);

        try {
            // Handle file upload if provided
            $artworkFile = null;
            if ($request->hasFile('artwork')) {
                $artworkFile = $request->file('artwork')->store('artwork', 'public');
            }

            $addressStreet = trim($validated['addressStreet']);
            $addressSuburb = preg_replace('/\s+/', ' ', strtoupper($validated['addressSuburb']));
            $addressState = strtoupper($validated['addressState']);
            $addressPostcode = $validated['addressPostcode'] ?? null;
            $addressPostcode = $addressPostcode !== null && $addressPostcode !== '' ? $addressPostcode : null;

            $addressLineTwo = array_filter([$addressSuburb, $addressState, $addressPostcode]);
            $formattedAddress = trim($addressStreet . "\n" . implode(' ', $addressLineTwo));

            // Store in database with all new fields
            $quoteRequest = QuoteRequest::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'service' => $validated['service'],
                'service_category' => $validated['serviceCategory'] ?? null,
                'description' => $validated['description'],
                'quantity' => $validated['quantity'],
                'size' => $validated['size'] ?? null,
                'artwork_file' => $artworkFile,
                'address_street' => $addressStreet,
                'address_suburb' => $addressSuburb,
                'address_state' => $addressState,
                'address_postcode' => $addressPostcode,
                'delivery_address' => $formattedAddress,
                'special_requirements' => $validated['specialRequirements'] ?? null,
                'status' => 'new'
            ]);

            Log::info('Quote request created', ['quote_request_id' => $quoteRequest->id]);

            // Dispatch job to send emails in background (FASTER!)
            SendQuoteEmailJob::dispatch($quoteRequest);

            // Log successful submission
            Log::info('Quote request processed successfully', [
                'quote_request_id' => $quoteRequest->id,
                'email' => $validated['email'],
                'service' => $validated['service']
            ]);

            // Return Inertia response with success message
            Log::info('Returning Inertia response with success message');
            return Inertia::render('Quote', [
                'services' => Product::where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get()
                    ->groupBy('category'),
                'phone' => '0449 598 440',
                'email' => 'pedroibl@yahoo.com',
                'success' => 'Thank you! Your quote request has been sent successfully. We\'ll get back to you within 24 hours.',
                'quoteRequest' => $quoteRequest,
                'captcha' => [
                    'type' => $effectiveType,
                    'recaptcha_sitekey' => $recaptchaSiteKey,
                    'hcaptcha_sitekey' => $hcaptchaSiteKey,
                ]
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Quote request failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['artwork']),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);

            // Return Inertia response with error message
            return Inertia::render('Quote', [
                'services' => Product::where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get()
                    ->groupBy('category'),
                'phone' => '0449 598 440',
                'email' => 'pedroibl@yahoo.com',
                'error' => 'Sorry, there was an error sending your request. Please try again or call us directly at 0449 598 440.',
                'errors' => ['general' => 'Sorry, there was an error sending your request. Please try again or call us directly at 0449 598 440.'],
                'captcha' => [
                    'type' => $effectiveType,
                    'recaptcha_sitekey' => $recaptchaSiteKey,
                    'hcaptcha_sitekey' => $hcaptchaSiteKey,
                ]
            ]);
        }
    }
}
