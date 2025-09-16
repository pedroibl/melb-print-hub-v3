<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WhatsAppController extends Controller
{
    protected WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Get WhatsApp configuration and templates
     */
    public function getConfig(): JsonResponse
    {
        return response()->json([
            'enabled' => $this->whatsappService->isConfigured(),
            'phone_number' => $this->whatsappService->getPhoneNumber(),
            'templates' => $this->whatsappService->getMessageTemplates(),
        ]);
    }

    /**
     * Generate WhatsApp URL for specific service
     */
    public function getServiceUrl(Request $request): JsonResponse
    {
        $request->validate([
            'service' => 'required|string',
            'custom_message' => 'nullable|string|max:500',
        ]);

        $service = $request->input('service');
        $customMessage = $request->input('custom_message');

        $url = $customMessage 
            ? $this->whatsappService->generateWhatsAppUrl($customMessage)
            : $this->whatsappService->getServiceWhatsAppUrl($service);

        return response()->json([
            'url' => $url,
            'service' => $service,
            'message' => $customMessage ?: $this->whatsappService->getMessageTemplate($service),
        ]);
    }

    /**
     * Generate WhatsApp URL for urgent requests
     */
    public function getUrgentUrl(Request $request): JsonResponse
    {
        $request->validate([
            'service' => 'nullable|string',
            'deadline' => 'nullable|string|max:100',
        ]);

        $service = $request->input('service', 'printing');
        $deadline = $request->input('deadline', 'ASAP');

        $url = $this->whatsappService->getUrgentWhatsAppUrl($service, $deadline);

        return response()->json([
            'url' => $url,
            'service' => $service,
            'deadline' => $deadline,
        ]);
    }

    /**
     * Generate WhatsApp URL for quote follow-up
     */
    public function getQuoteFollowUpUrl(Request $request): JsonResponse
    {
        $request->validate([
            'quote_id' => 'nullable|string|max:50',
        ]);

        $quoteId = $request->input('quote_id');
        $url = $this->whatsappService->getQuoteFollowUpUrl($quoteId);

        return response()->json([
            'url' => $url,
            'quote_id' => $quoteId,
        ]);
    }

    /**
     * Generate WhatsApp URL for order status
     */
    public function getOrderStatusUrl(Request $request): JsonResponse
    {
        $request->validate([
            'order_number' => 'nullable|string|max:50',
        ]);

        $orderNumber = $request->input('order_number');
        $url = $this->whatsappService->getOrderStatusUrl($orderNumber);

        return response()->json([
            'url' => $url,
            'order_number' => $orderNumber,
        ]);
    }

    /**
     * Get all available message templates
     */
    public function getTemplates(): JsonResponse
    {
        return response()->json([
            'templates' => $this->whatsappService->getMessageTemplates(),
        ]);
    }

    /**
     * Test WhatsApp integration
     */
    public function test(): JsonResponse
    {
        $isConfigured = $this->whatsappService->isConfigured();
        $phoneNumber = $this->whatsappService->getPhoneNumber();
        $testUrl = $this->whatsappService->generateWhatsAppUrl('Test message from Melbourne Print Hub');
        $hasVonageClient = $this->whatsappService->hasVonageClient();

        return response()->json([
            'status' => $isConfigured ? 'configured' : 'not_configured',
            'phone_number' => $phoneNumber,
            'test_url' => $testUrl,
            'has_vonage_client' => $hasVonageClient,
            'message' => 'WhatsApp integration test successful',
        ]);
    }

    /**
     * Send WhatsApp message
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'to' => 'required|string|max:20',
            'message' => 'nullable|string|max:500',
            'template' => 'nullable|string|max:50',
        ]);

        $to = $request->input('to');
        $message = $request->input('message');
        $template = $request->input('template', 'general');

        $result = $this->whatsappService->sendMessage($to, $message, $template);

        return response()->json($result);
    }

    /**
     * Send service-specific message
     */
    public function sendServiceMessage(Request $request): JsonResponse
    {
        $request->validate([
            'to' => 'required|string|max:20',
            'service' => 'required|string|max:50',
            'custom_message' => 'nullable|string|max:500',
        ]);

        $to = $request->input('to');
        $service = $request->input('service');
        $customMessage = $request->input('custom_message');

        $result = $this->whatsappService->sendServiceMessage($to, $service, $customMessage);

        return response()->json($result);
    }

    /**
     * Send urgent message
     */
    public function sendUrgentMessage(Request $request): JsonResponse
    {
        $request->validate([
            'to' => 'required|string|max:20',
            'service' => 'nullable|string|max:100',
            'deadline' => 'nullable|string|max:100',
        ]);

        $to = $request->input('to');
        $service = $request->input('service', 'printing');
        $deadline = $request->input('deadline', 'ASAP');

        $result = $this->whatsappService->sendUrgentMessage($to, $service, $deadline);

        return response()->json($result);
    }
}
