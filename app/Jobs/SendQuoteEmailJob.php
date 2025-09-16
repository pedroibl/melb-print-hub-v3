<?php

namespace App\Jobs;

use App\Models\QuoteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendQuoteEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $quoteRequest;

    /**
     * Create a new job instance.
     */
    public function __construct(QuoteRequest $quoteRequest)
    {
        $this->quoteRequest = $quoteRequest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Send email to admin
            Mail::raw("New Quote Request Received\n\n" .
                     "Name: " . $this->quoteRequest->name . "\n" .
                     "Email: " . $this->quoteRequest->email . "\n" .
                     "Phone: " . $this->quoteRequest->phone . "\n" .
                     "Service: " . $this->quoteRequest->service . "\n" .
                     "Quantity: " . $this->quoteRequest->quantity . "\n" .
                     "Description: " . $this->quoteRequest->description . "\n\n" .
                     "Submitted at: " . $this->quoteRequest->created_at->format('d/m/Y H:i'),
                function ($message) {
                    $message->to('pedroibl@yahoo.com')
                           ->subject('New Quote Request - Melbourne Print Hub');
                });

            // Send confirmation email to customer
            Mail::raw("Thank you for your quote request!\n\n" .
                     "Hi " . $this->quoteRequest->name . ",\n\n" .
                     "We have received your quote request for " . $this->quoteRequest->service . " and will get back to you within 24 hours with a detailed quote.\n\n" .
                     "Your Request Summary:\n" .
                     "- Service: " . $this->quoteRequest->service . "\n" .
                     "- Quantity: " . $this->quoteRequest->quantity . "\n" .
                     "- Size: " . ($this->quoteRequest->size ?: 'Not specified') . "\n\n" .
                     "If you have any questions in the meantime, please contact us at 0449 598 440 or pedroibl@yahoo.com\n\n" .
                     "Best regards,\nThe Melbourne Print Hub Team",
                function ($message) {
                    $message->to($this->quoteRequest->email)
                           ->subject('Quote Request Received - Melbourne Print Hub');
                });

            Log::info('Quote email job completed successfully', [
                'quote_request_id' => $this->quoteRequest->id,
                'customer_email' => $this->quoteRequest->email
            ]);
        } catch (\Exception $e) {
            Log::error('Quote email job failed', [
                'quote_request_id' => $this->quoteRequest->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
