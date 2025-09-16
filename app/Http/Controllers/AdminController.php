<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\QuoteRequest;
use App\Models\ContactMessage;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $quoteRequests = QuoteRequest::latest()->take(10)->get();
        $contactMessages = ContactMessage::latest()->take(10)->get();
        
        $stats = [
            'total_quotes' => QuoteRequest::count(),
            'new_quotes' => QuoteRequest::where('status', 'new')->count(),
            'total_contacts' => ContactMessage::count(),
            'new_contacts' => ContactMessage::where('status', 'new')->count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'quoteRequests' => $quoteRequests,
            'contactMessages' => $contactMessages,
            'stats' => $stats
        ]);
    }

    /**
     * Show all quote requests
     */
    public function quoteRequests()
    {
        $quoteRequests = QuoteRequest::latest()->paginate(20);
        
        return Inertia::render('Admin/QuoteRequests', [
            'quoteRequests' => $quoteRequests
        ]);
    }

    /**
     * Show all contact messages
     */
    public function contactMessages()
    {
        $contactMessages = ContactMessage::latest()->paginate(20);
        
        return Inertia::render('Admin/ContactMessages', [
            'contactMessages' => $contactMessages
        ]);
    }

    /**
     * Update quote request status
     */
    public function updateQuoteStatus(Request $request, QuoteRequest $quoteRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,reviewing,quoted,accepted,rejected',
            'notes' => 'nullable|string'
        ]);

        $quoteRequest->update($validated);

        return back()->with('success', 'Quote request status updated successfully.');
    }

    /**
     * Update contact message status
     */
    public function updateContactStatus(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied,archived',
            'notes' => 'nullable|string'
        ]);

        $contactMessage->update($validated);

        return back()->with('success', 'Contact message status updated successfully.');
    }
}
