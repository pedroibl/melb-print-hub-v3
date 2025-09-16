<?php

namespace Tests\Browser;

use App\Models\QuoteRequest;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\DuskTestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class QuoteFormTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Fake mail to prevent actual emails during testing
        Mail::fake();
        
        // Clear any existing quote requests
        QuoteRequest::truncate();
        
        // Create test products for quote form
        $this->createTestProducts();
    }

    /**
     * Create test products for the quote form
     */
    private function createTestProducts(): void
    {
        Product::create([
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'category' => 'Printing',
            'description' => 'Professional business cards',
            'price' => 50.00,
            'is_active' => true,
            'sort_order' => 1
        ]);

        Product::create([
            'name' => 'Flyers',
            'slug' => 'flyers',
            'category' => 'Marketing',
            'description' => 'High-quality flyers',
            'price' => 100.00,
            'is_active' => true,
            'sort_order' => 2
        ]);

        Product::create([
            'name' => 'Brochures',
            'slug' => 'brochures',
            'category' => 'Marketing',
            'description' => 'Professional brochures',
            'price' => 150.00,
            'is_active' => true,
            'sort_order' => 3
        ]);
    }

    /**
     * Test quote form page loads correctly
     */
    public function test_quote_page_loads_correctly(): void
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);
            
            $browser->visit('/get-quote')
                    ->waitFor('form', 10)
                    ->assertSee('Get a Quote')
                    ->assertSee('0449 598 440')
                    ->assertSee('info@melbourneprinthub.com.au')
                    ->assertSee('Business Cards')
                    ->assertSee('Flyers')
                    ->assertSee('Brochures');
            
            $loadTime = microtime(true) - $startTime;
            
            // Performance assertion - page should load within 4 seconds
            $this->assertLessThan(4.0, $loadTime, "Quote page took too long to load: {$loadTime}s");
            
            Log::info('Quote page load test completed', [
                'load_time' => $loadTime,
                'url' => $browser->driver->getCurrentURL()
            ]);
        });
    }

    /**
     * Test quote form validation
     */
    public function test_quote_form_validation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/get-quote')
                    ->waitFor('form', 10)
                    ->type('name', '')
                    ->type('email', 'invalid-email')
                    ->type('phone', '')
                    ->select('service', '')
                    ->type('description', '')
                    ->press('Get Quote')
                    ->waitFor('.error', 5)
                    ->assertSee('The name field is required')
                    ->assertSee('The email field must be a valid email address')
                    ->assertSee('The phone field is required')
                    ->assertSee('The service field is required')
                    ->assertSee('The description field is required');
        });
    }

    /**
     * Test successful quote form submission
     */
    public function test_quote_form_successful_submission(): void
    {
        $this->browse(function (Browser $browser) {
            $startTime = microtime(true);
            
            $browser->visit('/get-quote')
                    ->waitFor('form', 10)
                    ->type('name', 'Jane Smith')
                    ->type('email', 'jane.smith@example.com')
                    ->type('phone', '0412 345 678')
                    ->select('service', 'Business Cards')
                    ->type('description', 'I need 500 business cards for my new business. Standard size, full color printing.')
                    ->type('quantity', '500')
                    ->type('size', '90mm x 55mm')
                    ->type('delivery_address', '123 Main Street, Melbourne VIC 3000')
                    ->type('special_requirements', 'Please include spot UV finish')
                    ->waitFor('button[type="submit"]:not([disabled])', 10)
                    ->press('Get Quote')
                    ->waitFor('.success', 10)
                    ->assertSee('Thank you! Your quote request has been submitted');
            
            $submissionTime = microtime(true) - $startTime;
            
            // Performance assertion - form submission should complete within 6 seconds
            $this->assertLessThan(6.0, $submissionTime, "Quote form submission took too long: {$submissionTime}s");
            
            // Verify database record was created
            $this->assertDatabaseHas('quote_requests', [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '0412 345 678',
                'service' => 'Business Cards',
                'description' => 'I need 500 business cards for my new business. Standard size, full color printing.',
                'quantity' => '500',
                'size' => '90mm x 55mm',
                'delivery_address' => '123 Main Street, Melbourne VIC 3000',
                'special_requirements' => 'Please include spot UV finish',
                'status' => 'new'
            ]);
            
            Log::info('Quote form submission test completed', [
                'submission_time' => $submissionTime,
                'quote_request_id' => QuoteRequest::where('email', 'jane.smith@example.com')->first()->id ?? null
            ]);
        });
    }

    /**
     * Test quote form with different service categories
     */
    public function test_quote_form_different_services(): void
    {
        $this->browse(function (Browser $browser) {
            $services = ['Business Cards', 'Flyers', 'Brochures'];
            
            foreach ($services as $service) {
                $browser->visit('/get-quote')
                        ->waitFor('form', 10)
                        ->type('name', "Test User for {$service}")
                        ->type('email', "test.{$service}@example.com")
                        ->type('phone', '0412 345 678')
                        ->select('service', $service)
                        ->type('description', "I need {$service} for my business")
                        ->type('quantity', '100')
                        ->waitFor('button[type="submit"]:not([disabled])', 10)
                        ->press('Get Quote')
                        ->waitFor('.success', 10)
                        ->assertSee('Thank you! Your quote request has been submitted');
                
                // Verify database record
                $this->assertDatabaseHas('quote_requests', [
                    'name' => "Test User for {$service}",
                    'email' => "test.{$service}@example.com",
                    'service' => $service,
                    'status' => 'new'
                ]);
            }
        });
    }

    /**
     * Test quote form with special characters and long text
     */
    public function test_quote_form_with_special_characters(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/get-quote')
                    ->waitFor('form', 10)
                    ->type('name', 'María José García-López')
                    ->type('email', 'maria.jose@example.com')
                    ->type('phone', '+61 412 345 678')
                    ->select('service', 'Brochures')
                    ->type('description', 'I need professional brochures for my café "Café del Sol" ☕. The design should include our logo and showcase our menu items. We\'re looking for a modern, elegant design that reflects our brand identity.')
                    ->type('quantity', '1000')
                    ->type('size', 'A4 tri-fold')
                    ->type('delivery_address', '456 Collins Street, Melbourne VIC 3000, Australia')
                    ->type('special_requirements', 'Please use eco-friendly paper and soy-based inks. Include spot UV on the front cover. 😊')
                    ->waitFor('button[type="submit"]:not([disabled])', 10)
                    ->press('Get Quote')
                    ->waitFor('.success', 10)
                    ->assertSee('Thank you! Your quote request has been submitted');
            
            // Verify database record with special characters
            $this->assertDatabaseHas('quote_requests', [
                'name' => 'María José García-López',
                'email' => 'maria.jose@example.com',
                'phone' => '+61 412 345 678',
                'service' => 'Brochures',
                'description' => 'I need professional brochures for my café "Café del Sol" ☕. The design should include our logo and showcase our menu items. We\'re looking for a modern, elegant design that reflects our brand identity.',
                'quantity' => '1000',
                'size' => 'A4 tri-fold',
                'delivery_address' => '456 Collins Street, Melbourne VIC 3000, Australia',
                'special_requirements' => 'Please use eco-friendly paper and soy-based inks. Include spot UV on the front cover. 😊',
                'status' => 'new'
            ]);
        });
    }

    /**
     * Test quote form performance under load
     */
    public function test_quote_form_performance(): void
    {
        $this->browse(function (Browser $browser) {
            $performanceMetrics = [];
            $services = ['Business Cards', 'Flyers', 'Brochures'];
            
            // Test multiple submissions to check performance
            for ($i = 1; $i <= 3; $i++) {
                $startTime = microtime(true);
                $service = $services[($i - 1) % count($services)];
                
                $browser->visit('/get-quote')
                        ->waitFor('form', 10)
                        ->type('name', "Performance Test User {$i}")
                        ->type('email', "perftest{$i}@example.com")
                        ->type('phone', '0412 345 678')
                        ->select('service', $service)
                        ->type('description', "Performance test quote request {$i}")
                        ->type('quantity', '100')
                        ->waitFor('button[type="submit"]:not([disabled])', 10)
                        ->press('Get Quote')
                        ->waitFor('.success', 10);
                
                $submissionTime = microtime(true) - $startTime;
                $performanceMetrics[] = $submissionTime;
                
                Log::info("Quote form performance test iteration {$i}", [
                    'submission_time' => $submissionTime,
                    'service' => $service,
                    'iteration' => $i
                ]);
            }
            
            // Calculate average performance
            $averageTime = array_sum($performanceMetrics) / count($performanceMetrics);
            
            // Performance assertion - average submission time should be under 5 seconds
            $this->assertLessThan(5.0, $averageTime, "Average quote form submission time too high: {$averageTime}s");
            
            Log::info('Quote form performance test summary', [
                'average_time' => $averageTime,
                'individual_times' => $performanceMetrics
            ]);
        });
    }

    /**
     * Test quote form accessibility
     */
    public function test_quote_form_accessibility(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/get-quote')
                    ->waitFor('form', 10)
                    ->assertAttribute('input[name="name"]', 'autocomplete', 'name')
                    ->assertAttribute('input[name="email"]', 'autocomplete', 'email')
                    ->assertAttribute('input[name="phone"]', 'autocomplete', 'tel')
                    ->assertPresent('label[for="name"]')
                    ->assertPresent('label[for="email"]')
                    ->assertPresent('label[for="phone"]')
                    ->assertPresent('label[for="service"]')
                    ->assertPresent('label[for="description"]');
        });
    }

    /**
     * Test quote form mobile responsiveness
     */
    public function test_quote_form_mobile_responsive(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->resize(375, 667) // iPhone SE dimensions
                    ->visit('/get-quote')
                    ->waitFor('form', 10)
                    ->assertVisible('form')
                    ->assertVisible('input[name="name"]')
                    ->assertVisible('input[name="email"]')
                    ->assertVisible('input[name="phone"]')
                    ->assertVisible('select[name="service"]')
                    ->assertVisible('textarea[name="description"]')
                    ->assertVisible('button[type="submit"]');
        });
    }

    /**
     * Test quote form with file upload (artwork)
     */
    public function test_quote_form_with_file_upload(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/get-quote')
                    ->waitFor('form', 10)
                    ->type('name', 'File Upload Test User')
                    ->type('email', 'fileupload@example.com')
                    ->type('phone', '0412 345 678')
                    ->select('service', 'Business Cards')
                    ->type('description', 'I have artwork ready for my business cards')
                    ->type('quantity', '500')
                    ->attach('artwork_file', __DIR__ . '/../../storage/app/test-artwork.pdf')
                    ->waitFor('button[type="submit"]:not([disabled])', 10)
                    ->press('Get Quote')
                    ->waitFor('.success', 10)
                    ->assertSee('Thank you! Your quote request has been submitted');
            
            // Verify database record includes file upload
            $this->assertDatabaseHas('quote_requests', [
                'name' => 'File Upload Test User',
                'email' => 'fileupload@example.com',
                'service' => 'Business Cards',
                'artwork_file' => 'test-artwork.pdf',
                'status' => 'new'
            ]);
        });
    }

    /**
     * Test quote form error handling
     */
    public function test_quote_form_error_handling(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/get-quote')
                    ->waitFor('form', 10)
                    ->type('name', 'Error Test User')
                    ->type('email', 'errortest@example.com')
                    ->type('phone', '0412 345 678')
                    ->select('service', 'Business Cards')
                    ->type('description', 'Test description for error handling')
                    ->type('quantity', '100')
                    ->waitFor('button[type="submit"]:not([disabled])', 10)
                    ->press('Get Quote')
                    ->waitFor('.success', 10)
                    ->assertSee('Thank you! Your quote request has been submitted');
            
            // Test that the form is properly reset after successful submission
            $browser->assertValue('input[name="name"]', '')
                    ->assertValue('input[name="email"]', '')
                    ->assertValue('input[name="phone"]', '')
                    ->assertValue('textarea[name="description"]', '')
                    ->assertValue('input[name="quantity"]', '');
        });
    }

    /**
     * Test quote form CSRF protection
     */
    public function test_quote_form_csrf_protection(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/get-quote')
                    ->waitFor('form', 10)
                    ->assertPresent('input[name="_token"]')
                    ->assertAttribute('input[name="_token"]', 'value');
        });
    }

    /**
     * Test quote form rate limiting
     */
    public function test_quote_form_rate_limiting(): void
    {
        $this->browse(function (Browser $browser) {
            // Submit form multiple times quickly to test rate limiting
            for ($i = 1; $i <= 5; $i++) {
                $browser->visit('/get-quote')
                        ->waitFor('form', 10)
                        ->type('name', "Rate Test User {$i}")
                        ->type('email', "ratetest{$i}@example.com")
                        ->type('phone', '0412 345 678')
                        ->select('service', 'Business Cards')
                        ->type('description', "Rate limiting test quote {$i}")
                        ->type('quantity', '100')
                        ->waitFor('button[type="submit"]:not([disabled])', 10)
                        ->press('Get Quote');
                
                // Wait a bit between submissions
                $browser->pause(1000);
            }
            
            // Check that rate limiting is working (should see error after multiple submissions)
            $browser->assertSee('Too many attempts');
        });
    }

    /**
     * Test quote form with minimum required fields
     */
    public function test_quote_form_minimum_fields(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/get-quote')
                    ->waitFor('form', 10)
                    ->type('name', 'Minimal User')
                    ->type('email', 'minimal@example.com')
                    ->type('phone', '0412 345 678')
                    ->select('service', 'Business Cards')
                    ->type('description', 'Simple business cards')
                    ->waitFor('button[type="submit"]:not([disabled])', 10)
                    ->press('Get Quote')
                    ->waitFor('.success', 10)
                    ->assertSee('Thank you! Your quote request has been submitted');
            
            // Verify database record with minimal fields
            $this->assertDatabaseHas('quote_requests', [
                'name' => 'Minimal User',
                'email' => 'minimal@example.com',
                'phone' => '0412 345 678',
                'service' => 'Business Cards',
                'description' => 'Simple business cards',
                'status' => 'new'
            ]);
        });
    }
}
