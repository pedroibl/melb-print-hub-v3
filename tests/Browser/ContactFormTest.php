<?php

namespace Tests\Browser;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\DuskTestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactFormTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Fake mail to prevent actual emails during testing
        Mail::fake();
        
        // Clear any existing contact messages
        ContactMessage::truncate();
    }

    /**
     * Test contact form page loads correctly
     */
    public function test_contact_page_loads_correctly(): void
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);
            
            $browser->visit('/contact')
                    ->waitFor('form', 10)
                    ->assertSee('Contact Melbourne Print Hub')
                    ->assertSee('0449 598 440')
                    ->assertSee('info@melbourneprinthub.com.au')
                    ->assertSee('Monday to Friday, 08:00 AM to 06:00 PM');
            
            $loadTime = microtime(true) - $startTime;
            
            // Performance assertion - page should load within 3 seconds
            $this->assertLessThan(3.0, $loadTime, "Contact page took too long to load: {$loadTime}s");
            
            Log::info('Contact page load test completed', [
                'load_time' => $loadTime,
                'url' => $browser->driver->getCurrentURL()
            ]);
        });
    }

    /**
     * Test contact form validation
     */
    public function test_contact_form_validation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contact')
                    ->waitFor('form', 10)
                    ->type('name', '')
                    ->type('email', 'invalid-email')
                    ->type('message', '')
                    ->press('Send Message')
                    ->waitFor('.error', 5)
                    ->assertSee('The name field is required')
                    ->assertSee('The email field must be a valid email address')
                    ->assertSee('The message field is required');
        });
    }

    /**
     * Test successful contact form submission
     */
    public function test_contact_form_successful_submission(): void
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);
            
            $browser->visit('/contact')
                    ->waitFor('form', 10)
                    ->type('name', 'John Doe')
                    ->type('email', 'john.doe@example.com')
                    ->type('message', 'This is a test message for Melbourne Print Hub contact form.')
                    ->waitFor('button[type="submit"]:not([disabled])', 10)
                    ->press('Send Message')
                    ->waitFor('.success', 10)
                    ->assertSee('Thank you! Your message has been sent successfully');
            
            $submissionTime = microtime(true) - $startTime;
            
            // Performance assertion - form submission should complete within 5 seconds
            $this->assertLessThan(5.0, $submissionTime, "Contact form submission took too long: {$submissionTime}s");
            
            // Verify database record was created
            $this->assertDatabaseHas('contact_messages', [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'message' => 'This is a test message for Melbourne Print Hub contact form.',
                'status' => 'new'
            ]);
            
            Log::info('Contact form submission test completed', [
                'submission_time' => $submissionTime,
                'contact_message_id' => ContactMessage::where('email', 'john.doe@example.com')->first()->id ?? null
            ]);
        });
    }

    /**
     * Test contact form with special characters
     */
    public function test_contact_form_with_special_characters(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contact')
                    ->waitFor('form', 10)
                    ->type('name', 'José María O\'Connor-Smith')
                    ->type('email', 'jose.maria@example.com')
                    ->type('message', 'I need printing services for my business. Can you help with: 1) Business cards 2) Flyers 3) Brochures? Thanks! 😊')
                    ->waitFor('button[type="submit"]:not([disabled])', 10)
                    ->press('Send Message')
                    ->waitFor('.success', 10)
                    ->assertSee('Thank you! Your message has been sent successfully');
            
            // Verify database record with special characters
            $this->assertDatabaseHas('contact_messages', [
                'name' => 'José María O\'Connor-Smith',
                'email' => 'jose.maria@example.com',
                'message' => 'I need printing services for my business. Can you help with: 1) Business cards 2) Flyers 3) Brochures? Thanks! 😊',
                'status' => 'new'
            ]);
        });
    }

    /**
     * Test contact form performance under load
     */
    public function test_contact_form_performance(): void
    {
        $this->browse(function (Browser $browser) {
            $performanceMetrics = [];
            
            // Test multiple submissions to check performance
            for ($i = 1; $i <= 3; $i++) {
                $startTime = microtime(true);
                
                $browser->visit('/contact')
                        ->waitFor('form', 10)
                        ->type('name', "Test User {$i}")
                        ->type('email', "test{$i}@example.com")
                        ->type('message', "Performance test message {$i}")
                        ->waitFor('button[type="submit"]:not([disabled])', 10)
                        ->press('Send Message')
                        ->waitFor('.success', 10);
                
                $submissionTime = microtime(true) - $startTime;
                $performanceMetrics[] = $submissionTime;
                
                Log::info("Contact form performance test iteration {$i}", [
                    'submission_time' => $submissionTime,
                    'iteration' => $i
                ]);
            }
            
            // Calculate average performance
            $averageTime = array_sum($performanceMetrics) / count($performanceMetrics);
            
            // Performance assertion - average submission time should be under 4 seconds
            $this->assertLessThan(4.0, $averageTime, "Average contact form submission time too high: {$averageTime}s");
            
            Log::info('Contact form performance test summary', [
                'average_time' => $averageTime,
                'individual_times' => $performanceMetrics
            ]);
        });
    }

    /**
     * Test contact form accessibility
     */
    public function test_contact_form_accessibility(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contact')
                    ->waitFor('form', 10)
                    ->assertAttribute('input[name="name"]', 'autocomplete', 'name')
                    ->assertAttribute('input[name="email"]', 'autocomplete', 'email')
                    ->assertAttribute('textarea[name="message"]', 'autocomplete', 'off')
                    ->assertPresent('label[for="name"]')
                    ->assertPresent('label[for="email"]')
                    ->assertPresent('label[for="message"]');
        });
    }

    /**
     * Test contact form mobile responsiveness
     */
    public function test_contact_form_mobile_responsive(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                    ->visit('/contact')
                    ->waitFor('form', 10)
                    ->assertVisible('form')
                    ->assertVisible('input[name="name"]')
                    ->assertVisible('input[name="email"]')
                    ->assertVisible('textarea[name="message"]')
                    ->assertVisible('button[type="submit"]');
        });
    }

    /**
     * Test contact form error handling
     */
    public function test_contact_form_error_handling(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contact')
                    ->waitFor('form', 10)
                    ->type('name', 'Test User')
                    ->type('email', 'test@example.com')
                    ->type('message', 'Test message')
                    ->waitFor('button[type="submit"]:not([disabled])', 10)
                    ->press('Send Message')
                    ->waitFor('.success', 10)
                    ->assertSee('Thank you! Your message has been sent successfully');
            
            // Test that the form is properly reset after successful submission
            $browser->assertValue('input[name="name"]', '')
                    ->assertValue('input[name="email"]', '')
                    ->assertValue('textarea[name="message"]', '');
        });
    }

    /**
     * Test contact form CSRF protection
     */
    public function test_contact_form_csrf_protection(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/contact')
                    ->waitFor('form', 10)
                    ->assertPresent('input[name="_token"]')
                    ->assertAttribute('input[name="_token"]', 'value');
        });
    }

    /**
     * Test contact form rate limiting
     */
    public function test_contact_form_rate_limiting(): void
    {
        $this->browse(function (Browser $browser) {
            // Submit form multiple times quickly to test rate limiting
            for ($i = 1; $i <= 5; $i++) {
                $browser->visit('/contact')
                        ->waitFor('form', 10)
                        ->type('name', "Rate Test User {$i}")
                        ->type('email', "ratetest{$i}@example.com")
                        ->type('message', "Rate limiting test message {$i}")
                        ->waitFor('button[type="submit"]:not([disabled])', 10)
                        ->press('Send Message');
                
                // Wait a bit between submissions
                $browser->pause(1000);
            }
            
            // Check that rate limiting is working (should see error after multiple submissions)
            $browser->assertSee('Too many attempts');
        });
    }
}
