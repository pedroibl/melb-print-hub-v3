<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppAPIService
{
    private string $apiUrl;
    private ?string $instanceId;
    private ?string $token;
    private string $phoneNumber;

    public function __construct()
    {
        $this->apiUrl = config('whatsapp.api_url', 'https://api.4whats.net');
        $this->instanceId = config('whatsapp.instance_id', '');
        $this->token = config('whatsapp.api_token', '');
        $this->phoneNumber = config('whatsapp.phone_number', '+61449598440');
    }

    /**
     * Check if WhatsApp API is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->instanceId) && !empty($this->token);
    }

    /**
     * Get WhatsApp instance status
     * @see https://docs.4whats.net/get/Instance/status
     */
    public function getInstanceStatus(): array
    {
        if (!$this->isConfigured()) {
            return ['error' => 'WhatsApp API not configured'];
        }

        try {
            $response = Http::get("{$this->apiUrl}/status", [
                'instanceid' => $this->instanceId,
                'token' => $this->token
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('WhatsApp instance status retrieved', $data);
                return $data;
            } else {
                Log::error('WhatsApp instance status failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return ['error' => 'Failed to get instance status'];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp API status check failed: ' . $e->getMessage());
            return ['error' => 'Failed to check instance status: ' . $e->getMessage()];
        }
    }

    /**
     * Get QR code for authentication
     * @see https://docs.4whats.net/get/Instance/qr_code
     */
    public function getQRCode(): array
    {
        if (!$this->isConfigured()) {
            return ['error' => 'WhatsApp API not configured'];
        }

        try {
            $response = Http::get("{$this->apiUrl}/qr_code", [
                'instanceid' => $this->instanceId,
                'token' => $this->token
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('WhatsApp QR code retrieved');
                return $data;
            } else {
                Log::error('WhatsApp QR code failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return ['error' => 'Failed to get QR code'];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp API QR code failed: ' . $e->getMessage());
            return ['error' => 'Failed to get QR code: ' . $e->getMessage()];
        }
    }

    /**
     * Send text message via WhatsApp
     * @see https://docs.4whats.net/get/Message/sendMessage
     */
    public function sendMessage(string $to, string $message): array
    {
        if (!$this->isConfigured()) {
            return ['error' => 'WhatsApp API not configured'];
        }

        try {
            $response = Http::get("{$this->apiUrl}/sendMessage", [
                'instanceid' => $this->instanceId,
                'token' => $this->token,
                'to' => $to,
                'text' => $message
            ]);

            $result = $response->json();
            
            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'to' => $to,
                    'message_id' => $result['id'] ?? null,
                    'result' => $result
                ]);
                return $result;
            } else {
                Log::error('WhatsApp message failed', [
                    'to' => $to,
                    'status' => $response->status(),
                    'response' => $result
                ]);
                return ['error' => 'Failed to send message', 'details' => $result];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp API send message failed: ' . $e->getMessage());
            return ['error' => 'Failed to send message: ' . $e->getMessage()];
        }
    }

    /**
     * Send file via WhatsApp
     * @see https://docs.4whats.net/get/Message/sendFile
     */
    public function sendFile(string $to, string $fileUrl, string $caption = ''): array
    {
        if (!$this->isConfigured()) {
            return ['error' => 'WhatsApp API not configured'];
        }

        try {
            $params = [
                'instanceid' => $this->instanceId,
                'token' => $this->token,
                'to' => $to,
                'file' => $fileUrl
            ];

            if (!empty($caption)) {
                $params['caption'] = $caption;
            }

            $response = Http::get("{$this->apiUrl}/sendFile", $params);
            $result = $response->json();
            
            if ($response->successful()) {
                Log::info('WhatsApp file sent successfully', [
                    'to' => $to,
                    'file' => $fileUrl,
                    'message_id' => $result['id'] ?? null
                ]);
                return $result;
            } else {
                Log::error('WhatsApp file failed', [
                    'to' => $to,
                    'file' => $fileUrl,
                    'status' => $response->status(),
                    'response' => $result
                ]);
                return ['error' => 'Failed to send file', 'details' => $result];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp API send file failed: ' . $e->getMessage());
            return ['error' => 'Failed to send file: ' . $e->getMessage()];
        }
    }

    /**
     * Send contact via WhatsApp
     * @see https://docs.4whats.net/get/Message/sendContact
     */
    public function sendContact(string $to, string $name, string $phone, string $description = ''): array
    {
        if (!$this->isConfigured()) {
            return ['error' => 'WhatsApp API not configured'];
        }

        try {
            $params = [
                'instanceid' => $this->instanceId,
                'token' => $this->token,
                'to' => $to,
                'name' => $name,
                'phone' => $phone
            ];

            if (!empty($description)) {
                $params['description'] = $description;
            }

            $response = Http::get("{$this->apiUrl}/sendContact", $params);
            $result = $response->json();
            
            if ($response->successful()) {
                Log::info('WhatsApp contact sent successfully', [
                    'to' => $to,
                    'contact_name' => $name,
                    'message_id' => $result['id'] ?? null
                ]);
                return $result;
            } else {
                Log::error('WhatsApp contact failed', [
                    'to' => $to,
                    'contact_name' => $name,
                    'status' => $response->status(),
                    'response' => $result
                ]);
                return ['error' => 'Failed to send contact', 'details' => $result];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp API send contact failed: ' . $e->getMessage());
            return ['error' => 'Failed to send contact: ' . $e->getMessage()];
        }
    }

    /**
     * Send location via WhatsApp
     * @see https://docs.4whats.net/get/Message/sendLocation
     */
    public function sendLocation(string $to, float $latitude, float $longitude, string $name = '', string $address = ''): array
    {
        if (!$this->isConfigured()) {
            return ['error' => 'WhatsApp API not configured'];
        }

        try {
            $params = [
                'instanceid' => $this->instanceId,
                'token' => $this->token,
                'to' => $to,
                'lat' => $latitude,
                'lng' => $longitude
            ];

            if (!empty($name)) {
                $params['name'] = $name;
            }

            if (!empty($address)) {
                $params['address'] = $address;
            }

            $response = Http::get("{$this->apiUrl}/sendLocation", $params);
            $result = $response->json();
            
            if ($response->successful()) {
                Log::info('WhatsApp location sent successfully', [
                    'to' => $to,
                    'lat' => $latitude,
                    'lng' => $longitude,
                    'message_id' => $result['id'] ?? null
                ]);
                return $result;
            } else {
                Log::error('WhatsApp location failed', [
                    'to' => $to,
                    'lat' => $latitude,
                    'lng' => $longitude,
                    'status' => $response->status(),
                    'response' => $result
                ]);
                return ['error' => 'Failed to send location', 'details' => $result];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp API send location failed: ' . $e->getMessage());
            return ['error' => 'Failed to send location: ' . $e->getMessage()];
        }
    }

    /**
     * Get chat dialogs
     * @see https://docs.4whats.net/get/Chat/dialogs
     */
    public function getDialogs(): array
    {
        if (!$this->isConfigured()) {
            return ['error' => 'WhatsApp API not configured'];
        }

        try {
            $response = Http::get("{$this->apiUrl}/dialogs", [
                'instanceid' => $this->instanceId,
                'token' => $this->token
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('WhatsApp dialogs retrieved', ['count' => count($data)]);
                return $data;
            } else {
                Log::error('WhatsApp dialogs failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return ['error' => 'Failed to get dialogs'];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp API get dialogs failed: ' . $e->getMessage());
            return ['error' => 'Failed to get dialogs: ' . $e->getMessage()];
        }
    }

    /**
     * Get messages from a specific chat or all messages
     * @see https://docs.4whats.net/get/Message/messages
     */
    public function getMessages(string $chatId = '', int $limit = 100, int $lastMessageNumber = 0): array
    {
        if (!$this->isConfigured()) {
            return ['error' => 'WhatsApp API not configured'];
        }

        try {
            $params = [
                'instanceid' => $this->instanceId,
                'token' => $this->token,
                'limit' => $limit
            ];

            // Add optional parameters
            if (!empty($chatId)) {
                $params['chatId'] = $chatId;
            }

            if ($lastMessageNumber > 0) {
                $params['lastMessageNumber'] = $lastMessageNumber;
            }

            $response = Http::get("{$this->apiUrl}/messages", $params);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('WhatsApp messages retrieved', [
                    'chatId' => $chatId ?: 'all',
                    'count' => count($data['messages'] ?? []),
                    'lastMessageNumber' => $data['lastMessageNumber'] ?? null
                ]);
                return $data;
            } else {
                Log::error('WhatsApp messages failed', [
                    'chatId' => $chatId ?: 'all',
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return ['error' => 'Failed to get messages'];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp API get messages failed: ' . $e->getMessage());
            return ['error' => 'Failed to get messages: ' . $e->getMessage()];
        }
    }

    /**
     * Set webhook URL for receiving notifications
     * @see https://docs.4whats.net/get/Webhook/webhook
     */
    public function setWebhook(string $webhookUrl): array
    {
        if (!$this->isConfigured()) {
            return ['error' => 'WhatsApp API not configured'];
        }

        try {
            $response = Http::get("{$this->apiUrl}/webhook", [
                'instanceid' => $this->instanceId,
                'token' => $this->token,
                'url' => $webhookUrl
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('WhatsApp webhook set successfully', [
                    'webhook_url' => $webhookUrl,
                    'response' => $data
                ]);
                return $data;
            } else {
                Log::error('WhatsApp webhook set failed', [
                    'webhook_url' => $webhookUrl,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return ['error' => 'Failed to set webhook'];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp API set webhook failed: ' . $e->getMessage());
            return ['error' => 'Failed to set webhook: ' . $e->getMessage()];
        }
    }

    /**
     * Get current webhook URL
     */
    public function getWebhook(): array
    {
        if (!$this->isConfigured()) {
            return ['error' => 'WhatsApp API not configured'];
        }

        try {
            $response = Http::get("{$this->apiUrl}/webhook", [
                'instanceid' => $this->instanceId,
                'token' => $this->token
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('WhatsApp webhook retrieved', $data);
                return $data;
            } else {
                Log::error('WhatsApp webhook get failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return ['error' => 'Failed to get webhook'];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp API get webhook failed: ' . $e->getMessage());
            return ['error' => 'Failed to get webhook: ' . $e->getMessage()];
        }
    }

    /**
     * Generate WhatsApp URL for fallback (when API is not configured)
     */
    public function generateWhatsAppUrl(string $message = ''): string
    {
        $defaultMessage = "Hi! I have a printing question. Can you help?";
        $finalMessage = $message ?: $defaultMessage;
        $cleanPhone = $this->phoneNumber;
        $encodedMessage = urlencode($finalMessage);
        return "https://wa.me/{$cleanPhone}?text={$encodedMessage}";
    }

    /**
     * Get message templates
     */
    public function getMessageTemplates(): array
    {
        return [
            'general' => "Hi! I have a printing question. Can you help?",
            'business_cards' => "Hi! I need a quote for business cards. Can you help?",
            'banners' => "Hi! I need a quote for banners. Can you help?",
            'urgent' => "Hi! I have an urgent printing job. Can you help?",
            'quote_followup' => "Hi! I'd like to follow up on my quote request. Can you help?",
            'order_status' => "Hi! I'd like to check the status of my order. Can you help?"
        ];
    }

    /**
     * Get message template by key
     */
    public function getMessageTemplate(string $key): string
    {
        $templates = $this->getMessageTemplates();
        return $templates[$key] ?? $templates['general'];
    }

    /**
     * Test the WhatsApp API connection
     */
    public function test(): array
    {
        if (!$this->isConfigured()) {
            return [
                'status' => 'not_configured',
                'message' => 'WhatsApp API not configured, using fallback URLs',
                'fallback_url' => $this->generateWhatsAppUrl()
            ];
        }

        try {
            $status = $this->getInstanceStatus();
            
            if (isset($status['error'])) {
                return [
                    'status' => 'error',
                    'message' => 'Failed to connect to WhatsApp API',
                    'error' => $status['error']
                ];
            }

            return [
                'status' => 'connected',
                'message' => 'WhatsApp API connection successful',
                'instance_status' => $status,
                'api_url' => $this->apiUrl,
                'instance_id' => $this->instanceId
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'WhatsApp API test failed',
                'error' => $e->getMessage()
            ];
        }
    }
}
