<?php

namespace App\Jobs;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendContactEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contactMessage;

    /**
     * Create a new job instance.
     */
    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Send email to admin
            Mail::raw("New Contact Message Received\n\n" .
                     "Name: " . $this->contactMessage->name . "\n" .
                     "Email: " . $this->contactMessage->email . "\n" .
                     "Message: " . $this->contactMessage->message . "\n\n" .
                     "Submitted at: " . $this->contactMessage->created_at->format('d/m/Y H:i'),
                function ($message) {
                    $message->to('pedroibl@yahoo.com')
                           ->subject('New Contact Message - Melbourne Print Hub');
                });

            // Send confirmation email to customer
            Mail::raw("Thank you for contacting us!\n\n" .
                     "Hi " . $this->contactMessage->name . ",\n\n" .
                     "We have received your message and will get back to you within 24 hours.\n\n" .
                     "Your Message Summary:\n" .
                     "- Message: " . $this->contactMessage->message . "\n\n" .
                     "If you need immediate assistance, please call us at 0449 598 440 or email pedroibl@yahoo.com.\n\n" .
                     "Best regards,\nThe Melbourne Print Hub Team",
                function ($message) {
                    $message->to($this->contactMessage->email)
                           ->subject('Message Received - Melbourne Print Hub');
                });

            Log::info('Contact email job completed successfully', [
                'contact_message_id' => $this->contactMessage->id,
                'customer_email' => $this->contactMessage->email
            ]);
        } catch (\Exception $e) {
            Log::error('Contact email job failed', [
                'contact_message_id' => $this->contactMessage->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}