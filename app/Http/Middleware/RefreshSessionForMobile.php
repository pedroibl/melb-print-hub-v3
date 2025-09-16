<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RefreshSessionForMobile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a mobile device
        $userAgent = $request->userAgent();
        $isMobile = $this->isMobileDevice($userAgent);
        
        if ($isMobile) {
            Log::info('Mobile device detected', [
                'user_agent' => $userAgent,
                'ip' => $request->ip(),
                'session_id' => session()->getId()
            ]);
            
            // Extend session lifetime for mobile devices
            $session = $request->session();
            $session->put('mobile_device', true);
            $session->put('last_activity', time());
            
            // Regenerate session ID if it's been more than 30 minutes
            $lastActivity = $session->get('last_activity', 0);
            if (time() - $lastActivity > 1800) { // 30 minutes
                $session->regenerate();
                Log::info('Session regenerated for mobile device', [
                    'ip' => $request->ip(),
                    'new_session_id' => session()->getId()
                ]);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Check if the user agent indicates a mobile device
     */
    private function isMobileDevice(?string $userAgent): bool
    {
        if (!$userAgent) {
            return false;
        }
        
        $mobileKeywords = [
            'android', 'iphone', 'ipad', 'ipod', 'blackberry', 
            'windows phone', 'mobile', 'tablet', 'opera mini',
            'mobile safari', 'mobile chrome'
        ];
        
        $userAgentLower = strtolower($userAgent);
        
        foreach ($mobileKeywords as $keyword) {
            if (str_contains($userAgentLower, $keyword)) {
                return true;
            }
        }
        
        return false;
    }
}
