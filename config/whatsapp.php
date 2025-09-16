<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for WhatsApp integration
    | including API keys, phone numbers, and business settings.
    |
    */

    // WhatsApp Business API Key (from Vonage/Nexmo)
    'api_key' => env('WHATSAPP_API_KEY'),
    
    // WhatsApp Business API Secret (from Vonage/Nexmo)
    'api_secret' => env('WHATSAPP_API_SECRET'),
    
    // 4WhatsApp.net API Configuration
    'api_url' => env('WHATSAPP_API_URL', 'https://api.4whats.net'),
    'instance_id' => env('WHATSAPP_INSTANCE_ID'),
    'api_token' => env('WHATSAPP_API_TOKEN'),

    // WhatsApp Business Phone Number (with country code)
    'phone_number' => env('WHATSAPP_PHONE_NUMBER', '+61449598440'),

    // WhatsApp Business ID
    'business_id' => env('WHATSAPP_BUSINESS_ID'),

    // Default message template
    'default_template' => env('WHATSAPP_DEFAULT_TEMPLATE', 'general'),

    // Enable/disable WhatsApp integration
    'enabled' => env('WHATSAPP_ENABLED', true),

    // WhatsApp button settings
    'button' => [
        'color' => '#25D366', // WhatsApp brand green
        'text' => 'Chat on WhatsApp',
        'mobile_text' => 'WhatsApp Us',
        'icon' => 'whatsapp', // Icon identifier
    ],

    // Message templates for different services
    'templates' => [
        'business_cards' => "Hi! I need a quote for business cards. Can you help?",
        'banners' => "Hi! I need banners for an event. What are my options?",
        'flyers' => "Hi! I need flyers printed. Can you help with design and printing?",
        'letterheads' => "Hi! I need professional letterheads. What are your options?",
        'posters' => "Hi! I need posters printed. What sizes and materials do you offer?",
        'corflute_signs' => "Hi! I need corflute signs for outdoor advertising. Can you help?",
        'window_graphics' => "Hi! I need window graphics for my storefront. What are my options?",
        'general' => "Hi! I have a printing question. Can you help?",
        'urgent' => "URGENT: I need printing services quickly. Available?",
        'quote' => "Hi! I submitted a quote request. Can we discuss it?",
        'design_consultation' => "Hi! I need help with design for my printing project. Any tips?",
        'order_status' => "Hi! Can I get an update on my order?",
    ],
];
