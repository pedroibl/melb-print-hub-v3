<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class HumanVerificationService
{
    /**
     * Verify reCAPTCHA response
     */
    public function verifyRecaptcha(Request $request): bool
    {
        $recaptchaResponse = $request->input('g-recaptcha-response');
        
        if (!$recaptchaResponse) {
            Log::warning('reCAPTCHA response missing', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return false;
        }
        
        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('captcha.secret'),
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip()
            ]);
            
            $result = $response->json();
            
            Log::info('reCAPTCHA verification result', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? null,
                'action' => $result['action'] ?? null,
                'ip' => $request->ip()
            ]);
            
            // For v3, check score (0.0 is bot, 1.0 is human)
            if (isset($result['score'])) {
                return $result['success'] && $result['score'] >= 0.5;
            }
            
            // For v2, just check success
            return $result['success'] ?? false;
            
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification failed', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            return false;
        }
    }
    
    /**
     * Verify hCaptcha response
     */
    public function verifyHcaptcha(Request $request): bool
    {
        $hcaptchaResponse = $request->input('h-captcha-response');
        
        if (!$hcaptchaResponse) {
            Log::warning('hCaptcha response missing', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return false;
        }
        
        try {
            $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
                'secret' => config('captcha.hcaptcha_secret'),
                'response' => $hcaptchaResponse,
                'remoteip' => $request->ip()
            ]);
            
            $result = $response->json();
            
            Log::info('hCaptcha verification result', [
                'success' => $result['success'] ?? false,
                'ip' => $request->ip()
            ]);
            
            return $result['success'] ?? false;
            
        } catch (\Exception $e) {
            Log::error('hCaptcha verification failed', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);
            return false;
        }
    }
    
    /**
     * Check honeypot field
     */
    public function checkHoneypot(Request $request): bool
    {
        $honeypotFields = ['website', 'phone_number', 'company', 'url'];
        
        foreach ($honeypotFields as $field) {
            if ($request->filled($field)) {
                Log::warning('Honeypot field filled', [
                    'field' => $field,
                    'value' => $request->input($field),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Check submission timing (prevent instant submissions)
     */
    public function checkSubmissionTiming(Request $request): bool
    {
        $formStartTime = $request->input('form_start_time');
        
        if (!$formStartTime) {
            Log::warning('Form start time missing', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return false;
        }
        
        $startTime = (int) $formStartTime;
        $currentTime = time();
        $timeDiff = $currentTime - $startTime;
        
        // Minimum 3 seconds, maximum 30 minutes
        if ($timeDiff < 3 || $timeDiff > 1800) {
            Log::warning('Suspicious submission timing', [
                'time_diff' => $timeDiff,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return false;
        }
        
        return true;
    }
    
    /**
     * Check for suspicious patterns in content
     */
    public function checkContentPatterns(Request $request): bool
    {
        $suspiciousPatterns = [
            '/\b(viagra|cialis|casino|poker|loan|debt|weight loss|diet)\b/i',
            '/\b(click here|buy now|limited time|act now|urgent)\b/i',
            '/\b(free.*money|earn.*money|make.*money)\b/i',
            '/\b(bitcoin|crypto|forex|trading)\b/i',
            '/\b(seo|backlink|traffic|ranking)\b/i'
        ];
        
        $content = implode(' ', $request->all());
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                Log::warning('Suspicious content pattern detected', [
                    'pattern' => $pattern,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Comprehensive verification check
     */
    public function verifySubmission(Request $request, string $verificationType = 'recaptcha'): array
    {
        $results = [
            'success' => true,
            'errors' => [],
            'verification_type' => $verificationType
        ];
        
        // Short-circuit when CAPTCHA is disabled
        if ($verificationType === 'none') {
            return $results;
        }

        // Check honeypot
        if (!$this->checkHoneypot($request)) {
            $results['success'] = false;
            $results['errors'][] = 'Invalid form submission detected';
        }
        
        // Check submission timing
        if (!$this->checkSubmissionTiming($request)) {
            $results['success'] = false;
            $results['errors'][] = 'Suspicious submission timing detected';
        }
        
        // Check content patterns
        if (!$this->checkContentPatterns($request)) {
            $results['success'] = false;
            $results['errors'][] = 'Suspicious content detected';
        }
        
        // Verify CAPTCHA based on type
        if ($verificationType === 'recaptcha') {
            if (!$this->verifyRecaptcha($request)) {
                $results['success'] = false;
                $results['errors'][] = 'reCAPTCHA verification failed';
            }
        } elseif ($verificationType === 'hcaptcha') {
            if (!$this->verifyHcaptcha($request)) {
                $results['success'] = false;
                $results['errors'][] = 'hCaptcha verification failed';
            }
        }
        
        Log::info('Human verification completed', [
            'success' => $results['success'],
            'errors' => $results['errors'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return $results;
    }
}
