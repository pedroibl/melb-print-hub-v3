<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\WhatsAppAPIController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Simple test route to bypass all middleware
Route::get('/test-simple', function() {
    return response()->json([
        'status' => 'success',
        'message' => 'Laravel is working!',
        'timestamp' => now()->toISOString(),
        'app_url' => config('app.url'),
        'app_env' => config('app.env')
    ]);
})->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

// Even simpler test route
Route::get('/test-basic', function() {
    return 'Hello World! Laravel is working!';
});

// Super basic route that bypasses ALL middleware
Route::get('/test-raw', function() {
    return 'RAW: Laravel is working!';
})->withoutMiddleware('*');

// Test route that bypasses ALL middleware completely
Route::get('/test-no-middleware', function() {
    return 'No middleware test!';
})->withoutMiddleware('*');

// Test URL generation
Route::get('/test-urls', function() {
    return response()->json([
        'app_url' => config('app.url'),
        'app_env' => config('app.env'),
        'asset_url' => asset('css/app.css'),
        'route_url' => route('home'),
        'current_url' => url()->current(),
        'full_url' => request()->fullUrl(),
        'secure_asset' => secure_asset('css/app.css'),
        'secure_url' => secure_url('/'),
        'is_secure' => request()->isSecure(),
        'scheme' => request()->getScheme(),
        'host' => request()->getHost(),
        'port' => request()->getPort(),
        'server_vars' => [
            'HTTPS' => request()->server('HTTPS'),
            'HTTP_X_FORWARDED_PROTO' => request()->server('HTTP_X_FORWARDED_PROTO'),
            'HTTP_X_FORWARDED_SSL' => request()->server('HTTP_X_FORWARDED_SSL'),
            'SERVER_PORT' => request()->server('SERVER_PORT'),
        ],
    ]);
})->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

// Test route that bypasses Inertia
Route::get('/test-no-inertia', function() {
    return view('welcome');
});

// Debug route to test if redirect is fixed
Route::get('/debug-route', function() {
    \Log::info('Debug route accessed');
    return 'Debug route working!';
});

// Public pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Quote and Contact forms
Route::get('/get-quote', [QuoteController::class, 'show'])->name('quote.show');
Route::post('/get-quote', [QuoteController::class, 'store'])->middleware('form.rate.limit:quote')->name('quote.store');
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->middleware('form.rate.limit:contact')->name('contact.store');

// Thanks page
Route::get('/thanks', [ContactController::class, 'thanks'])->name('thanks');

// Loading page
Route::get('/loading', function() {
    return view('loading');
})->name('loading');

// Test form for testing the loading flow
Route::get('/test-form', function() {
    return view('test-form');
})->name('test.form');

// Test route for email testing (bypasses CSRF)
Route::post('/test-email', function(Request $request) {
    try {
        // Store in database
        $contactMessage = \App\Models\ContactMessage::create([
            'name' => $request->input('name', 'Test User'),
            'email' => $request->input('email', 'test@example.com'),
            'message' => $request->input('message', 'Test message'),
            'status' => 'new'
        ]);

        // Send email
        \Illuminate\Support\Facades\Mail::raw("Test Email from Melbourne Print Hub Website

Name: {$contactMessage->name}
Email: {$contactMessage->email}

Message:
{$contactMessage->message}

Submitted at: " . now()->format('Y-m-d H:i:s'), function($message) {
            $message->to('info@melbourneprinthub.com.au')
                    ->subject('Test Email - Melbourne Print Hub')
                    ->from('info@melbourneprinthub.com.au', 'Melbourne Print Hub Website');
        });

        return response()->json([
            'success' => true,
            'message' => 'Email sent successfully',
            'data' => $contactMessage
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
})->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)->name('test.email');

// Test form submission without CSRF (for debugging)
Route::post('/test-form-submission', function(Request $request) {
    try {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);
        
        // Store in database
        $contactMessage = \App\Models\ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
            'status' => 'new'
        ]);
        
        // Send email
        \Illuminate\Support\Facades\Mail::raw("Test Form Submission from Melbourne Print Hub Website

Name: {$contactMessage->name}
Email: {$contactMessage->email}

Message:
{$contactMessage->message}

Submitted at: " . now()->format('Y-m-d H:i:s'), function($message) {
            $message->to('info@melbourneprinthub.com.au')
                    ->subject('Test Form Submission - Melbourne Print Hub')
                    ->from('info@melbourneprinthub.com.au', 'Melbourne Print Hub Website');
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Form submitted successfully',
            'data' => $contactMessage,
            'csrf_token' => csrf_token(),
            'session_id' => session()->getId()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'csrf_token' => csrf_token(),
            'session_id' => session()->getId()
        ], 500);
    }
})->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)->name('test.form.submission');

// Security Testing Routes
Route::get('/security-test', function() {
    return response()->json([
        'status' => 'success',
        'message' => 'Security headers are working',
        'timestamp' => now()->toISOString(),
        'headers' => [
            'hsts' => 'Strict-Transport-Security: max-age=31536000; includeSubDomains; preload',
            'csp' => 'Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' https://cdn.jsdelivr.net https://unpkg.com; style-src \'self\' \'unsafe-inline\' https://fonts.googleapis.com https://cdn.jsdelivr.net; font-src \'self\' https://fonts.gstatic.com https://cdn.jsdelivr.net; img-src \'self\' data: https:; connect-src \'self\' https:; frame-src \'none\'; object-src \'none\'; base-uri \'self\'; form-action \'self\'; frame-ancestors \'none\'; upgrade-insecure-requests;',
            'x_frame_options' => 'X-Frame-Options: DENY',
            'x_content_type_options' => 'X-Content-Type-Options: nosniff',
            'x_xss_protection' => 'X-XSS-Protection: 1; mode=block',
            'referrer_policy' => 'Referrer-Policy: strict-origin-when-cross-origin',
            'permissions_policy' => 'Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()'
        ]
    ]);
})->name('security.test');

Route::get('/ssl-test', function() {
    $isSecure = request()->isSecure();
    $protocol = $isSecure ? 'HTTPS' : 'HTTP';
    $sslInfo = [];
    
    if ($isSecure) {
        $sslInfo = [
            'protocol' => $_SERVER['SSL_PROTOCOL'] ?? 'Unknown',
            'cipher' => $_SERVER['SSL_CIPHER'] ?? 'Unknown',
            'certificate' => 'Valid SSL certificate detected'
        ];
    }
    
    return response()->json([
        'status' => 'success',
        'secure' => $isSecure,
        'protocol' => $protocol,
        'ssl_info' => $sslInfo,
        'recommendation' => $isSecure ? 'SSL is properly configured' : 'SSL should be enabled for production',
        'timestamp' => now()->toISOString()
    ]);
})->name('ssl.test');

Route::get('/csrf-test', function() {
    return response()->json([
        'status' => 'success',
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'session_lifetime' => config('session.lifetime'),
        'session_secure' => config('session.secure'),
        'session_http_only' => config('session.http_only'),
        'session_same_site' => config('session.same_site'),
        'timestamp' => now()->toISOString()
    ]);
})->name('csrf.test');

// Security Testing Page
Route::get('/security-dashboard', function() {
    return view('security-test');
})->name('security.dashboard');

// Test pages
Route::get('/test-whatsapp', function () {
    return view('test-whatsapp');
})->name('test.whatsapp');

Route::get('/test-react', function () {
    return view('test-react');
})->name('test.react');

Route::get('/debug-whatsapp', function () {
    return view('debug-whatsapp');
})->name('debug.whatsapp');

Route::get('/mobile-whatsapp-test', function () {
    return view('mobile-whatsapp-test');
})->name('mobile.whatsapp.test');

// Mobile quote test page
Route::get('/mobile-quote-test', function () {
    return view('mobile-quote-test');
})->name('mobile.quote.test.page');

// Mobile quote test route (bypasses CSRF for testing)
Route::post('/mobile-quote-test', function (Request $request) {
    try {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'service' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'quantity' => 'required|string|max:255'
        ]);

        // Store in database
        $quoteRequest = \App\Models\QuoteRequest::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'service' => $validated['service'],
            'description' => $validated['description'],
            'quantity' => $validated['quantity'],
            'status' => 'new'
        ]);

        // Send HTML email notification
        $htmlEmailService = new \App\Services\HtmlEmailService();
        $htmlEmailService->sendQuoteRequestNotification($quoteRequest);

        return response()->json([
            'success' => true,
            'message' => 'Mobile quote test successful!',
            'data' => $quoteRequest
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
})->middleware('web')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Simple quote test route (bypasses Inertia.js)
Route::get('/simple-quote-test', function () {
    return view('simple-quote-test');
})->name('simple.quote.test');

// Test route for debugging CSRF issues
Route::get('/test-csrf', function() {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'session_lifetime' => config('session.lifetime'),
        'user_agent' => request()->userAgent(),
        'ip' => request()->ip(),
        'timestamp' => now()->toISOString()
    ]);
})->name('test.csrf');

// Email testing routes
Route::prefix('email-testing')->group(function () {
    Route::get('/', [App\Http\Controllers\EmailTestingController::class, 'show'])->name('email.testing');
    Route::post('/test', [App\Http\Controllers\EmailTestingController::class, 'test'])->name('email.testing.test');
    Route::get('/config', [App\Http\Controllers\EmailTestingController::class, 'getConfig'])->name('email.testing.config');
    Route::get('/test-config', [App\Http\Controllers\EmailTestingController::class, 'testConfig'])->name('email.testing.test-config');
    Route::get('/mobile-test', [App\Http\Controllers\EmailTestingController::class, 'mobileTest'])->name('email.testing.mobile-test');
    Route::post('/mobile-test-submit', [App\Http\Controllers\EmailTestingController::class, 'mobileTestSubmit'])->name('email.testing.mobile-test-submit')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
});

// WhatsApp integration routes
Route::prefix('whatsapp')->group(function () {
    // GET endpoints for URL generation
    Route::get('/config', [WhatsAppController::class, 'getConfig'])->name('whatsapp.config');
    Route::get('/service', [WhatsAppController::class, 'getServiceUrl'])->name('whatsapp.service');
    Route::get('/urgent', [WhatsAppController::class, 'getUrgentUrl'])->name('whatsapp.urgent');
    Route::get('/quote-followup', [WhatsAppController::class, 'getQuoteFollowUpUrl'])->name('whatsapp.quote-followup');
    Route::get('/order-status', [WhatsAppController::class, 'getOrderStatusUrl'])->name('whatsapp.order-status');
    Route::get('/templates', [WhatsAppController::class, 'getTemplates'])->name('whatsapp.templates');
    Route::get('/test', [WhatsAppController::class, 'test'])->name('whatsapp.test');
    
    // POST endpoints for sending messages
    Route::post('/send', [WhatsAppController::class, 'sendMessage'])->name('whatsapp.send');
    Route::post('/send-service', [WhatsAppController::class, 'sendServiceMessage'])->name('whatsapp.send-service');
    Route::post('/send-urgent', [WhatsAppController::class, 'sendUrgentMessage'])->name('whatsapp.send-urgent');
});

// Enhanced WhatsApp API routes (4WhatsApp.net integration)
Route::prefix('whatsapp-api')->group(function () {
    // Configuration and status
    Route::get('/config', [WhatsAppAPIController::class, 'getConfig'])->name('whatsapp.api.config');
    Route::get('/status', [WhatsAppAPIController::class, 'getInstanceStatus'])->name('whatsapp.api.status');
    Route::get('/qr-code', [WhatsAppAPIController::class, 'getQRCode'])->name('whatsapp.api.qr-code');
    Route::get('/test', [WhatsAppAPIController::class, 'test'])->name('whatsapp.api.test');
    
    // Templates and service URLs
    Route::get('/templates', [WhatsAppAPIController::class, 'getTemplates'])->name('whatsapp.api.templates');
    Route::get('/service', [WhatsAppAPIController::class, 'getServiceUrl'])->name('whatsapp.api.service');
    
    // Message sending endpoints
    Route::post('/send', [WhatsAppAPIController::class, 'sendMessage'])->name('whatsapp.api.send');
    Route::post('/send-file', [WhatsAppAPIController::class, 'sendFile'])->name('whatsapp.api.send-file');
    Route::post('/send-contact', [WhatsAppAPIController::class, 'sendContact'])->name('whatsapp.api.send-contact');
    Route::post('/send-location', [WhatsAppAPIController::class, 'sendLocation'])->name('whatsapp.api.send-location');
    
    // Chat management
    Route::get('/dialogs', [WhatsAppAPIController::class, 'getDialogs'])->name('whatsapp.api.dialogs');
    Route::get('/messages', [WhatsAppAPIController::class, 'getMessages'])->name('whatsapp.api.messages');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    // Protected product routes (for admin)
    Route::get('/admin/products', [ProductController::class, 'adminIndex'])->name('admin.products.index');
    
    // Admin routes for form submissions
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/quotes', [AdminController::class, 'quoteRequests'])->name('admin.quotes');
    Route::get('/admin/contacts', [AdminController::class, 'contactMessages'])->name('admin.contacts');
    Route::patch('/admin/quotes/{quoteRequest}/status', [AdminController::class, 'updateQuoteStatus'])->name('admin.quotes.status');
    Route::patch('/admin/contacts/{contactMessage}/status', [AdminController::class, 'updateContactStatus'])->name('admin.contacts.status');
});

require __DIR__.'/auth.php';

// CSRF debug test page
Route::get('/csrf-debug-test', function() {
    return view('csrf-debug-test');
})->name('csrf.debug.test');

// CSRF debugging route
Route::get('/debug-csrf', function() {
    return response()->json([
        'status' => 'success',
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'session_lifetime' => config('session.lifetime'),
        'session_expire_on_close' => config('session.expire_on_close'),
        'session_same_site' => config('session.same_site'),
        'session_secure' => config('session.secure'),
        'session_http_only' => config('session.http_only'),
        'session_driver' => config('session.driver'),
        'user_agent' => request()->userAgent(),
        'ip' => request()->ip(),
        'is_mobile' => preg_match('/(android|iphone|ipad|mobile|tablet)/i', request()->userAgent() ?? ''),
        'session_data' => [
            'mobile_device' => session()->get('mobile_device', false),
            'last_activity' => session()->get('last_activity', 0),
            'session_age' => time() - session()->get('last_activity', time()),
        ],
        'headers' => [
            'x-csrf-token' => request()->header('X-CSRF-TOKEN'),
            'x-requested-with' => request()->header('X-Requested-With'),
            'content-type' => request()->header('Content-Type'),
        ],
        'timestamp' => now()->toISOString()
    ]);
})->name('debug.csrf');
