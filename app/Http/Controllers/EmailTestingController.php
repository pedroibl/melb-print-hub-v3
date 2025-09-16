<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmailTestingService;
use Inertia\Inertia;

class EmailTestingController extends Controller
{
    protected $emailTestingService;

    public function __construct(EmailTestingService $emailTestingService)
    {
        $this->emailTestingService = $emailTestingService;
    }

    /**
     * Show the email testing page
     */
    public function show()
    {
        $configStatus = $this->emailTestingService->getEmailConfigStatus();
        
        return Inertia::render('EmailTesting', [
            'emailConfig' => $configStatus,
            'testResults' => null
        ]);
    }

    /**
     * Process email test submission
     */
    public function test(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000'
        ]);

        $result = $this->emailTestingService->testEmailSending($validated);

        return response()->json($result);
    }

    /**
     * Get email configuration status
     */
    public function getConfig()
    {
        $configStatus = $this->emailTestingService->getEmailConfigStatus();
        $testResult = $this->emailTestingService->testEmailConfiguration();

        return response()->json([
            'config' => $configStatus,
            'test_result' => $testResult
        ]);
    }

    /**
     * Test email configuration only
     */
    public function testConfig()
    {
        $result = $this->emailTestingService->testEmailConfiguration();
        
        return response()->json($result);
    }

    /**
     * Show a simple test page for mobile testing
     */
    public function mobileTest()
    {
        return view('email-mobile-test');
    }

    /**
     * Process mobile test submission
     */
    public function mobileTestSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000'
        ]);

        $result = $this->emailTestingService->testEmailSending($validated);

        return response()->json($result);
    }
}
