<?php

return [
    // Google reCAPTCHA settings
    'secret' => env('RECAPTCHA_SECRET_KEY'),
    'sitekey' => env('RECAPTCHA_SITE_KEY'),
    
    // hCaptcha settings
    'hcaptcha_secret' => env('HCAPTCHA_SECRET'),
    'hcaptcha_sitekey' => env('HCAPTCHA_SITEKEY'),
    
    // Verification settings
    // Allowed: 'none' (disabled), 'recaptcha', 'hcaptcha'
    'verification_type' => env('CAPTCHA_TYPE', 'none'),
    'min_score' => env('RECAPTCHA_MIN_SCORE', 0.5), // For reCAPTCHA v3
    
    'options' => [
        'timeout' => 30,
    ],
];
