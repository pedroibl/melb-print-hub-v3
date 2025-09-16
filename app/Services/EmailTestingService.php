<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\ContactMessage;

use App\Services\HtmlEmailService;

class EmailTestingService
{
    protected $htmlEmailService;

    public function __construct(HtmlEmailService $htmlEmailService)
    {
        $this->htmlEmailService = $htmlEmailService;
    }

    /**
     * Test email functionality with detailed logging
     */
    public function testEmailSending(array $data): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'details' => [],
            'logs' => []
        ];

        try {
            // Log the test attempt
            Log::info('Email testing started', [
                'data' => $data,
                'timestamp' => now()->toISOString(),
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip(),
                'platform' => $this->detectPlatform()
            ]);

            $result['details']['platform'] = $this->detectPlatform();
            $result['details']['user_agent'] = request()->userAgent();
            $result['details']['ip'] = request()->ip();

            // Store in database
            $contactMessage = ContactMessage::create([
                'name' => $data['name'] ?? 'Test User',
                'email' => $data['email'] ?? 'test@example.com',
                'message' => $data['message'] ?? 'Test message from email testing service',
                'status' => 'new'
            ]);

            $result['details']['contact_message_id'] = $contactMessage->id;

            // Send HTML email with detailed content
            $testData = [
                'platform' => $this->detectPlatform(),
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ];
            
            $this->htmlEmailService->sendEmailTestNotification($contactMessage, $testData);

            // Log success
            Log::info('Email test successful', [
                'contact_message_id' => $contactMessage->id,
                'platform' => $result['details']['platform'],
                'email_sent_to' => 'info@melbourneprinthub.com.au'
            ]);

            $result['success'] = true;
            $result['message'] = 'Email test completed successfully';
            $result['details']['email_sent'] = true;
            $result['details']['email_content'] = $this->htmlEmailService->generateTestEmailContent($contactMessage, $testData);

        } catch (\Exception $e) {
            // Log error
            Log::error('Email test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
                'platform' => $this->detectPlatform()
            ]);

            $result['message'] = 'Email test failed: ' . $e->getMessage();
            $result['details']['error'] = $e->getMessage();
            $result['details']['error_trace'] = $e->getTraceAsString();
        }

        // Add recent logs
        $result['logs'] = $this->getRecentLogs();

        return $result;
    }

    /**
     * Detect the platform (desktop/mobile)
     */
    private function detectPlatform(): string
    {
        $userAgent = request()->userAgent() ?? '';
        
        if (preg_match('/(android|iphone|ipad|mobile|tablet)/i', $userAgent)) {
            return 'mobile';
        }
        
        return 'desktop';
    }

    /**
     * Generate detailed email content for testing
     */
    private function generateEmailContent(ContactMessage $contactMessage): string
    {
        $platform = $this->detectPlatform();
        $userAgent = request()->userAgent() ?? 'Unknown';
        $ip = request()->ip() ?? 'Unknown';
        $timestamp = now()->format('Y-m-d H:i:s');

        return "=== EMAIL TEST - MELBOURNE PRINT HUB ===

PLATFORM: {$platform}
TIMESTAMP: {$timestamp}
IP ADDRESS: {$ip}
USER AGENT: {$userAgent}

CONTACT DETAILS:
Name: {$contactMessage->name}
Email: {$contactMessage->email}

MESSAGE:
{$contactMessage->message}

DATABASE RECORD:
Contact Message ID: {$contactMessage->id}
Status: {$contactMessage->status}
Created: {$contactMessage->created_at}

EMAIL CONFIGURATION:
Mail Driver: " . config('mail.default') . "
From Address: " . config('mail.from.address') . "
From Name: " . config('mail.from.name') . "

TEST RESULTS:
✅ Form submission: Successful
✅ Database storage: Successful  
✅ Email sending: Successful
✅ Platform detection: {$platform}

This is a test email to verify email functionality on {$platform} platform.

=== END TEST EMAIL ===";
    }

    /**
     * Get recent application logs
     */
    private function getRecentLogs(): array
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            if (file_exists($logFile)) {
                $logs = file($logFile);
                return array_slice($logs, -20); // Last 20 lines
            }
        } catch (\Exception $e) {
            return ['Error reading logs: ' . $e->getMessage()];
        }

        return ['No log file found'];
    }

    /**
     * Get email configuration status
     */
    public function getEmailConfigStatus(): array
    {
        return [
            'mail_driver' => config('mail.default'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
            'smtp_username' => config('mail.mailers.smtp.username') ? 'Configured' : 'Not configured',
            'smtp_password' => config('mail.mailers.smtp.password') ? 'Configured' : 'Not configured',
            'app_url' => config('app.url'),
            'app_env' => config('app.env')
        ];
    }

    /**
     * Test email configuration
     */
    public function testEmailConfiguration(): array
    {
        $config = $this->getEmailConfigStatus();
        $issues = [];

        if ($config['mail_driver'] === 'log') {
            $issues[] = 'Using log driver - emails will be logged instead of sent';
        }

        if (!$config['smtp_username'] || $config['smtp_username'] === 'Not configured') {
            $issues[] = 'SMTP username not configured';
        }

        if (!$config['smtp_password'] || $config['smtp_password'] === 'Not configured') {
            $issues[] = 'SMTP password not configured';
        }

        return [
            'config' => $config,
            'issues' => $issues,
            'ready_for_production' => empty($issues)
        ];
    }
}
