<?php

namespace App\Services;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Messages\MessageType;
use Vonage\Messages\Channel\WhatsApp\WhatsAppText;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $phoneNumber;
    private ?string $businessId;
    private ?string $apiKey;
    private ?Client $vonageClient;

    public function __construct()
    {
        $this->phoneNumber = config('whatsapp.phone_number', '+61449598440');
        $this->businessId = config('whatsapp.business_id', '');
        $this->apiKey = config('whatsapp.api_key');
        $this->vonageClient = null; // Initialize to null
        
        // Initialize Vonage client if credentials are available
        if ($this->isConfigured()) {
            $this->vonageClient = new Client(
                new Basic($this->apiKey, config('whatsapp.api_secret'))
            );
        }
    }

    /**
     * Get the WhatsApp phone number
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * Get the WhatsApp business ID
     */
    public function getBusinessId(): string
    {
        return $this->businessId;
    }

    /**
     * Check if WhatsApp API is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get message templates for different services
     */
    public function getMessageTemplates(): array
    {
        return [
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
        ];
    }

    /**
     * Get a specific message template
     */
    public function getMessageTemplate(string $key): string
    {
        $templates = $this->getMessageTemplates();
        return $templates[$key] ?? $templates['general'];
    }

    /**
     * Generate WhatsApp URL with pre-filled message
     */
    public function generateWhatsAppUrl(string $message = '', string $template = 'general'): string
    {
        $phone = $this->phoneNumber;
        $text = $message ?: $this->getMessageTemplate($template);
        
        // Remove any non-numeric characters except + from phone number
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Encode the message for URL
        $encodedMessage = urlencode($text);
        
        return "https://wa.me/{$cleanPhone}?text={$encodedMessage}";
    }

    /**
     * Generate WhatsApp URL for specific service
     */
    public function getServiceWhatsAppUrl(string $service): string
    {
        return $this->generateWhatsAppUrl('', $service);
    }

    /**
     * Generate WhatsApp URL for urgent requests
     */
    public function getUrgentWhatsAppUrl(string $service = '', string $deadline = ''): string
    {
        $message = "URGENT: I need {$service} printing by {$deadline}. Available?";
        return $this->generateWhatsAppUrl($message);
    }

    /**
     * Generate WhatsApp URL for quote follow-up
     */
    public function getQuoteFollowUpUrl(string $quoteId = ''): string
    {
        $message = $quoteId 
            ? "Hi! I submitted quote request #{$quoteId}. Can we discuss it?"
            : "Hi! I submitted a quote request. Can we discuss it?";
        return $this->generateWhatsAppUrl($message);
    }

    /**
     * Generate WhatsApp URL for order status
     */
    public function getOrderStatusUrl(string $orderNumber = ''): string
    {
        $message = $orderNumber 
            ? "Hi! Can I get an update on my order #{$orderNumber}?"
            : "Hi! Can I get an update on my order?";
        return $this->generateWhatsAppUrl($message);
    }

    /**
     * Send WhatsApp message using Vonage API
     */
    public function sendMessage(string $to, string $message, string $template = 'general'): array
    {
        if (!$this->isConfigured() || !$this->vonageClient) {
            return [
                'success' => false,
                'error' => 'WhatsApp API not configured'
            ];
        }

        try {
            // Use template if no custom message provided
            $text = $message ?: $this->getMessageTemplate($template);
            
            // Clean phone number (remove + and format for Vonage)
            $cleanTo = $this->cleanPhoneNumber($to);
            $cleanFrom = $this->cleanPhoneNumber($this->phoneNumber);

            $response = $this->vonageClient->messages()->send(
                new WhatsAppText(
                    to: $cleanTo,
                    from: $cleanFrom,
                    text: $text
                )
            );

            Log::info('WhatsApp message sent successfully', [
                'to' => $cleanTo,
                'from' => $cleanFrom,
                'message' => $text,
                'response' => $response->toArray()
            ]);

            return [
                'success' => true,
                'message_id' => $response->getMessageUuid(),
                'response' => $response->toArray()
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message', [
                'to' => $to,
                'message' => $message,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send service-specific message
     */
    public function sendServiceMessage(string $to, string $service, string $customMessage = ''): array
    {
        $message = $customMessage ?: $this->getMessageTemplate($service);
        return $this->sendMessage($to, $message, $service);
    }

    /**
     * Send urgent request message
     */
    public function sendUrgentMessage(string $to, string $service = '', string $deadline = ''): array
    {
        $message = "URGENT: I need {$service} printing by {$deadline}. Available?";
        return $this->sendMessage($to, $message);
    }

    /**
     * Send quote follow-up message
     */
    public function sendQuoteFollowUp(string $to, string $quoteId = ''): array
    {
        $message = $quoteId 
            ? "Hi! I submitted quote request #{$quoteId}. Can we discuss it?"
            : "Hi! I submitted a quote request. Can we discuss it?";
        return $this->sendMessage($to, $message);
    }

    /**
     * Send order status message
     */
    public function sendOrderStatusMessage(string $to, string $orderNumber = ''): array
    {
        $message = $orderNumber 
            ? "Hi! Can I get an update on my order #{$orderNumber}?"
            : "Hi! Can I get an update on my order?";
        return $this->sendMessage($to, $message);
    }

    /**
     * Clean phone number for Vonage API (remove + and ensure E.164 format)
     */
    private function cleanPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters except +
        $clean = preg_replace('/[^0-9+]/', '', $phone);
        
        // Remove + if present
        $clean = ltrim($clean, '+');
        
        // Ensure it starts with country code
        if (!str_starts_with($clean, '61')) {
            $clean = '61' . $clean;
        }
        
        return $clean;
    }

    /**
     * Check if Vonage client is available
     */
    public function hasVonageClient(): bool
    {
        return $this->vonageClient !== null;
    }
}
