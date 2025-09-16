<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // CORS Headers for JavaScript and CSS assets
        if (preg_match('/\.(js|css)$/', $request->path())) {
            $origin = $request->header('Origin');
            // Allow both www and non-www subdomains
            if (preg_match('/^https:\/\/(www\.)?melbourneprinthub\.com\.au$/', $origin)) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
            } else {
                $response->headers->set('Access-Control-Allow-Origin', '*');
            }
            $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
            $response->headers->set('Access-Control-Max-Age', '86400');
        }

        // Security headers
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Content Security Policy
        $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://melbourneprinthub.com.au https://www.melbourneprinthub.com.au https://www.google.com https://www.gstatic.com https://www.google.com/recaptcha/; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net https://melbourneprinthub.com.au https://www.melbourneprinthub.com.au; style-src-elem 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net https://melbourneprinthub.com.au https://www.melbourneprinthub.com.au; font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net https://cdn.jsdelivr.net; img-src 'self' data: https:; connect-src 'self' https:; frame-src 'self' https://www.google.com https://www.gstatic.com; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'; upgrade-insecure-requests;";

        // Relax CSP in local/dev to allow Vite HMR
        if (app()->environment('local')) {
            $csp = "default-src 'self'; "
                . "script-src 'self' 'unsafe-inline' 'unsafe-eval' http: https:; "
                . "style-src 'self' 'unsafe-inline' http: https:; "
                . "style-src-elem 'self' 'unsafe-inline' http: https:; "
                . "font-src 'self' data: http: https:; "
                . "img-src 'self' data: http: https:; "
                . "connect-src 'self' http: https: ws: wss:; "
                . "frame-src 'self' https:; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'; upgrade-insecure-requests;";
        }
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
