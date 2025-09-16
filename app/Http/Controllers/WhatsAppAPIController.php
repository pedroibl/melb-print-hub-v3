<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppAPIService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WhatsAppAPIController extends Controller
{
    protected WhatsAppAPIService $whatsappService;

    public function __construct(WhatsAppAPIService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Get WhatsApp API configuration and status
     */
    public function getConfig(): JsonResponse
    {
        $isConfigured = $this->whatsappService->isConfigured();
        $phoneNumber = $this->whatsappService->generateWhatsAppUrl();
        
        $config = [
            'status' => $isConfigured ? 'configured' : 'not_configured',
            'phone_number' => $phoneNumber,
            'api_configured' => $isConfigured,
            'message' => $isConfigured ? 'WhatsApp API is configured and ready' : 'WhatsApp API not configured, using fallback URLs'
        ];

        if ($isConfigured) {
            $instanceStatus = $this->whatsappService->getInstanceStatus();
            $config['instance_status'] = $instanceStatus;
        }

        return response()->json($config);
    }

    /**
     * Get WhatsApp instance status
     */
    public function getInstanceStatus(): JsonResponse
    {
        $status = $this->whatsappService->getInstanceStatus();
        
        if (isset($status['error'])) {
            return response()->json($status, 400);
        }

        return response()->json($status);
    }

    /**
     * Get QR code for authentication
     */
    public function getQRCode(): JsonResponse
    {
        $qrCode = $this->whatsappService->getQRCode();
        
        if (isset($qrCode['error'])) {
            return response()->json($qrCode, 400);
        }

        return response()->json($qrCode);
    }

    /**
     * Send WhatsApp message via API
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'to' => 'required|string',
            'message' => 'required|string',
            'template' => 'string'
        ]);

        $message = $validated['message'] ?: $this->whatsappService->getMessageTemplate($validated['template'] ?? 'general');
        
        $result = $this->whatsappService->sendMessage($validated['to'], $message);

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        return response()->json([
            'success' => true,
            'message_id' => $result['id'] ?? null,
            'result' => $result
        ]);
    }

    /**
     * Send WhatsApp file via API
     */
    public function sendFile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'to' => 'required|string',
            'file' => 'required|url',
            'caption' => 'string'
        ]);

        $result = $this->whatsappService->sendFile(
            $validated['to'], 
            $validated['file'], 
            $validated['caption'] ?? ''
        );

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        return response()->json([
            'success' => true,
            'result' => $result
        ]);
    }

    /**
     * Send WhatsApp contact via API
     */
    public function sendContact(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'to' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|string',
            'description' => 'string'
        ]);

        $result = $this->whatsappService->sendContact(
            $validated['to'],
            $validated['name'],
            $validated['phone'],
            $validated['description'] ?? ''
        );

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        return response()->json([
            'success' => true,
            'result' => $result
        ]);
    }

    /**
     * Send WhatsApp location via API
     */
    public function sendLocation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'to' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'name' => 'string',
            'address' => 'string'
        ]);

        $result = $this->whatsappService->sendLocation(
            $validated['to'],
            $validated['latitude'],
            $validated['longitude'],
            $validated['name'] ?? '',
            $validated['address'] ?? ''
        );

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        return response()->json([
            'success' => true,
            'result' => $result
        ]);
    }

    /**
     * Get WhatsApp service URL
     */
    public function getServiceUrl(Request $request): JsonResponse
    {
        $service = $request->input('service', 'general');
        $message = $this->whatsappService->getMessageTemplate($service);
        $url = $this->whatsappService->generateWhatsAppUrl($message);
        
        return response()->json([
            'service' => $service,
            'message' => $message,
            'url' => $url
        ]);
    }

    /**
     * Get message templates
     */
    public function getTemplates(): JsonResponse
    {
        $templates = $this->whatsappService->getMessageTemplates();
        
        return response()->json([
            'templates' => $templates,
            'count' => count($templates)
        ]);
    }

    /**
     * Get chat dialogs
     */
    public function getDialogs(): JsonResponse
    {
        $dialogs = $this->whatsappService->getDialogs();
        
        if (isset($dialogs['error'])) {
            return response()->json($dialogs, 400);
        }

        return response()->json([
            'dialogs' => $dialogs,
            'count' => count($dialogs)
        ]);
    }

    /**
     * Get messages from a specific chat
     */
    public function getMessages(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'chatId' => 'required|string',
            'limit' => 'integer|min:1|max:100'
        ]);

        $messages = $this->whatsappService->getMessages(
            $validated['chatId'],
            $validated['limit'] ?? 50
        );
        
        if (isset($messages['error'])) {
            return response()->json($messages, 400);
        }

        return response()->json([
            'messages' => $messages,
            'count' => count($messages),
            'chatId' => $validated['chatId']
        ]);
    }

    /**
     * Test WhatsApp API integration
     */
    public function test(): JsonResponse
    {
        $result = $this->whatsappService->test();
        
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
            'data' => $result
        ]);
    }
}
