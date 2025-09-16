<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FormRateLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $formType = 'general'): Response
    {
        $key = $this->resolveRequestSignature($request, $formType);
        
        // Different limits for different form types
        $limits = [
            'contact' => ['max_attempts' => 5, 'decay_minutes' => 60], // 5 per hour
            'quote' => ['max_attempts' => 3, 'decay_minutes' => 60],   // 3 per hour
            'general' => ['max_attempts' => 10, 'decay_minutes' => 60] // 10 per hour
        ];
        
        $limit = $limits[$formType] ?? $limits['general'];
        
        if (RateLimiter::tooManyAttempts($key, $limit['max_attempts'])) {
            $seconds = RateLimiter::availableIn($key);
            
            Log::warning('Form rate limit exceeded', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'form_type' => $formType,
                'seconds_remaining' => $seconds
            ]);
            
            return response()->json([
                'error' => 'Too many form submissions. Please try again in ' . ceil($seconds / 60) . ' minutes.',
                'retry_after' => $seconds
            ], 429);
        }
        
        RateLimiter::hit($key, $limit['decay_minutes'] * 60);
        
        return $next($request);
    }
    
    /**
     * Resolve request signature for rate limiting.
     */
    protected function resolveRequestSignature(Request $request, string $formType): string
    {
        $identifier = sha1(implode('|', [
            $request->ip(),
            $request->userAgent(),
            $formType
        ]));
        
        return 'form_submission:' . $formType . ':' . $identifier;
    }
}
