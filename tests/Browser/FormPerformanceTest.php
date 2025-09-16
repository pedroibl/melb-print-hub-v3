<?php

namespace Tests\Browser;

use App\Models\ContactMessage;
use App\Models\QuoteRequest;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\DuskTestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FormPerformanceTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Fake mail to prevent actual emails during testing
        Mail::fake();
        
        // Clear any existing data
        ContactMessage::truncate();
        QuoteRequest::truncate();
        
        // Create test products
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
    }

    /**
     * Test both forms performance under concurrent load
     */
    public function test_forms_concurrent_performance(): void
    {
        $this->browse(function (Browser $browser) {
            $performanceMetrics = [
                'contact' => [],
                'quote' => []
            ];
            
            // Test 5 submissions for each form
            for ($i = 1; $i <= 5; $i++) {
                // Test Contact Form
                $contactStartTime = microtime(true);
                
                $browser->visit('/contact')
                        ->waitFor('form', 10)
                        ->type('name', "Concurrent Contact User {$i}")
                        ->type('email', "concurrent.contact{$i}@example.com")
                        ->type('message', "Concurrent performance test message {$i}")
                        ->waitFor('button[type="submit"]:not([disabled])', 10)
                        ->press('Send Message')
                        ->waitFor('.success', 10);
                
                $contactTime = microtime(true) - $contactStartTime;
                $performanceMetrics['contact'][] = $contactTime;
                
                // Test Quote Form
                $quoteStartTime = microtime(true);
                
                $browser->visit('/get-quote')
                        ->waitFor('form', 10)
                        ->type('name', "Concurrent Quote User {$i}")
                        ->type('email', "concurrent.quote{$i}@example.com")
                        ->type('phone', '0412 345 678')
                        ->select('service', 'Business Cards')
                        ->type('description', "Concurrent performance test quote {$i}")
                        ->type('quantity', '100')
                        ->waitFor('button[type="submit"]:not([disabled])', 10)
                        ->press('Get Quote')
                        ->waitFor('.success', 10);
                
                $quoteTime = microtime(true) - $quoteStartTime;
                $performanceMetrics['quote'][] = $quoteTime;
                
                Log::info("Concurrent performance test iteration {$i}", [
                    'contact_time' => $contactTime,
                    'quote_time' => $quoteTime,
                    'iteration' => $i
                ]);
            }
            
            // Calculate averages
            $avgContactTime = array_sum($performanceMetrics['contact']) / count($performanceMetrics['contact']);
            $avgQuoteTime = array_sum($performanceMetrics['quote']) / count($performanceMetrics['quote']);
            
            // Performance assertions
            $this->assertLessThan(4.0, $avgContactTime, "Average contact form time too high: {$avgContactTime}s");
            $this->assertLessThan(5.0, $avgQuoteTime, "Average quote form time too high: {$avgQuoteTime}s");
            
            Log::info('Concurrent performance test summary', [
                'avg_contact_time' => $avgContactTime,
                'avg_quote_time' => $avgQuoteTime,
                'contact_times' => $performanceMetrics['contact'],
                'quote_times' => $performanceMetrics['quote']
            ]);
        });
    }

    /**
     * Test database performance during form submissions
     */
    public function test_database_performance(): void
    {
        $this->browse(function (Browser $browser) {
            $dbMetrics = [];
            
            // Test database write performance
            for ($i = 1; $i <= 10; $i++) {
                $startTime = microtime(true);
                
                // Submit contact form
                $browser->visit('/contact')
                        ->waitFor('form', 10)
                        ->type('name', "DB Test User {$i}")
                        ->type('email', "dbtest{$i}@example.com")
                        ->type('message', "Database performance test message {$i}")
                        ->waitFor('button[type="submit"]:not([disabled])', 10)
                        ->press('Send Message')
                        ->waitFor('.success', 10);
                
                $contactTime = microtime(true) - $startTime;
                
                // Submit quote form
                $startTime = microtime(true);
                
                $browser->visit('/get-quote')
                        ->waitFor('form', 10)
                        ->type('name', "DB Quote User {$i}")
                        ->type('email', "dbquote{$i}@example.com")
                        ->type('phone', '0412 345 678')
                        ->select('service', 'Business Cards')
                        ->type('description', "Database performance test quote {$i}")
                        ->type('quantity', '100')
                        ->waitFor('button[type="submit"]:not([disabled])', 10)
                        ->press('Get Quote')
                        ->waitFor('.success', 10);
                
                $quoteTime = microtime(true) - $startTime;
                
                $dbMetrics[] = [
                    'contact_time' => $contactTime,
                    'quote_time' => $quoteTime,
                    'iteration' => $i
                ];
            }
            
            // Verify database records
            $contactCount = ContactMessage::count();
            $quoteCount = QuoteRequest::count();
            
            $this->assertEquals(10, $contactCount, "Expected 10 contact messages, got {$contactCount}");
            $this->assertEquals(10, $quoteCount, "Expected 10 quote requests, got {$quoteCount}");
            
            Log::info('Database performance test completed', [
                'contact_messages_created' => $contactCount,
                'quote_requests_created' => $quoteCount,
                'performance_metrics' => $dbMetrics
            ]);
        });
    }

    /**
     * Test form validation performance
     */
    public function test_validation_performance(): void
    {
        $this->browse(function (Browser $browser) {
            $validationMetrics = [];
            
            // Test validation performance for both forms
            for ($i = 1; $i <= 5; $i++) {
                // Contact form validation
                $startTime = microtime(true);
                
                $browser->visit('/contact')
                        ->waitFor('form', 10)
                        ->type('name', '')
                        ->type('email', 'invalid-email')
                        ->type('message', '')
                        ->press('Send Message')
                        ->waitFor('.error', 5);
                
                $contactValidationTime = microtime(true) - $startTime;
                
                // Quote form validation
                $startTime = microtime(true);
                
                $browser->visit('/get-quote')
                        ->waitFor('form', 10)
                        ->type('name', '')
                        ->type('email', 'invalid-email')
                        ->type('phone', '')
                        ->select('service', '')
                        ->type('description', '')
                        ->press('Get Quote')
                        ->waitFor('.error', 5);
                
                $quoteValidationTime = microtime(true) - $startTime;
                
                $validationMetrics[] = [
                    'contact_validation_time' => $contactValidationTime,
                    'quote_validation_time' => $quoteValidationTime,
                    'iteration' => $i
                ];
            }
            
            // Calculate average validation times
            $avgContactValidation = array_sum(array_column($validationMetrics, 'contact_validation_time')) / count($validationMetrics);
            $avgQuoteValidation = array_sum(array_column($validationMetrics, 'quote_validation_time')) / count($validationMetrics);
            
            // Performance assertions for validation
            $this->assertLessThan(2.0, $avgContactValidation, "Contact validation too slow: {$avgContactValidation}s");
            $this->assertLessThan(2.5, $avgQuoteValidation, "Quote validation too slow: {$avgQuoteValidation}s");
            
            Log::info('Validation performance test completed', [
                'avg_contact_validation' => $avgContactValidation,
                'avg_quote_validation' => $avgQuoteValidation,
                'validation_metrics' => $validationMetrics
            ]);
        });
    }

    /**
     * Test memory usage during form submissions
     */
    public function test_memory_usage(): void
    {
        $this->browse(function (Browser $browser) {
            $memoryMetrics = [];
            
            // Test memory usage over multiple submissions
            for ($i = 1; $i <= 10; $i++) {
                $memoryBefore = memory_get_usage(true);
                
                // Submit both forms
                $browser->visit('/contact')
                        ->waitFor('form', 10)
                        ->type('name', "Memory Test User {$i}")
                        ->type('email', "memorytest{$i}@example.com")
                        ->type('message', "Memory test message {$i}")
                        ->waitFor('button[type="submit"]:not([disabled])', 10)
                        ->press('Send Message')
                        ->waitFor('.success', 10);
                
                $browser->visit('/get-quote')
                        ->waitFor('form', 10)
                        ->type('name', "Memory Quote User {$i}")
                        ->type('email', "memoryquote{$i}@example.com")
                        ->type('phone', '0412 345 678')
                        ->select('service', 'Business Cards')
                        ->type('description', "Memory test quote {$i}")
                        ->type('quantity', '100')
                        ->waitFor('button[type="submit"]:not([disabled])', 10)
                        ->press('Get Quote')
                        ->waitFor('.success', 10);
                
                $memoryAfter = memory_get_usage(true);
                $memoryUsed = $memoryAfter - $memoryBefore;
                
                $memoryMetrics[] = [
                    'memory_used' => $memoryUsed,
                    'memory_used_mb' => round($memoryUsed / 1024 / 1024, 2),
                    'iteration' => $i
                ];
            }
            
            // Calculate average memory usage
            $avgMemoryUsed = array_sum(array_column($memoryMetrics, 'memory_used')) / count($memoryMetrics);
            $avgMemoryUsedMB = round($avgMemoryUsed / 1024 / 1024, 2);
            
            // Memory usage assertion - should not exceed 50MB average
            $this->assertLessThan(50 * 1024 * 1024, $avgMemoryUsed, "Average memory usage too high: {$avgMemoryUsedMB}MB");
            
            Log::info('Memory usage test completed', [
                'avg_memory_used_mb' => $avgMemoryUsedMB,
                'memory_metrics' => $memoryMetrics
            ]);
        });
    }

    /**
     * Test form submission with large data
     */
    public function test_large_data_performance(): void
    {
        $this->browse(function (Browser $browser) {
            $largeDataMetrics = [];
            
            // Test with large text inputs
            $largeMessage = str_repeat('This is a large message for testing performance. ', 50);
            $largeDescription = str_repeat('This is a large description for testing quote form performance with extensive details. ', 100);
            
            // Contact form with large message
            $startTime = microtime(true);
            
            $browser->visit('/contact')
                    ->waitFor('form', 10)
                    ->type('name', 'Large Data Test User')
                    ->type('email', 'largedata@example.com')
                    ->type('message', $largeMessage)
                    ->waitFor('button[type="submit"]:not([disabled])', 10)
                    ->press('Send Message')
                    ->waitFor('.success', 10);
            
            $contactLargeTime = microtime(true) - $startTime;
            
            // Quote form with large description
            $startTime = microtime(true);
            
            $browser->visit('/get-quote')
                    ->waitFor('form', 10)
                    ->type('name', 'Large Quote Test User')
                    ->type('email', 'largequote@example.com')
                    ->type('phone', '0412 345 678')
                    ->select('service', 'Business Cards')
                    ->type('description', $largeDescription)
                    ->type('quantity', '1000')
                    ->type('size', 'Custom size with detailed specifications')
                    ->type('delivery_address', 'Very long delivery address with multiple lines and detailed information')
                    ->type('special_requirements', 'Extensive special requirements with detailed specifications and multiple paragraphs')
                    ->waitFor('button[type="submit"]:not([disabled])', 10)
                    ->press('Get Quote')
                    ->waitFor('.success', 10);
            
            $quoteLargeTime = microtime(true) - $startTime;
            
            $largeDataMetrics = [
                'contact_large_time' => $contactLargeTime,
                'quote_large_time' => $quoteLargeTime,
                'large_message_length' => strlen($largeMessage),
                'large_description_length' => strlen($largeDescription)
            ];
            
            // Performance assertions for large data
            $this->assertLessThan(6.0, $contactLargeTime, "Large contact form submission too slow: {$contactLargeTime}s");
            $this->assertLessThan(7.0, $quoteLargeTime, "Large quote form submission too slow: {$quoteLargeTime}s");
            
            Log::info('Large data performance test completed', [
                'large_data_metrics' => $largeDataMetrics
            ]);
        });
    }

    /**
     * Test form error recovery performance
     */
    public function test_error_recovery_performance(): void
    {
        $this->browse(function (Browser $browser) {
            $errorRecoveryMetrics = [];
            
            // Test error recovery performance
            for ($i = 1; $i <= 5; $i++) {
                // Submit invalid data first
                $startTime = microtime(true);
                
                $browser->visit('/contact')
                        ->waitFor('form', 10)
                        ->type('name', '')
                        ->type('email', 'invalid-email')
                        ->type('message', '')
                        ->press('Send Message')
                        ->waitFor('.error', 5);
                
                // Then submit valid data
                $browser->type('name', "Error Recovery User {$i}")
                        ->type('email', "errorrecovery{$i}@example.com")
                        ->type('message', "Error recovery test message {$i}")
                        ->press('Send Message')
                        ->waitFor('.success', 10);
                
                $contactRecoveryTime = microtime(true) - $startTime;
                
                // Same for quote form
                $startTime = microtime(true);
                
                $browser->visit('/get-quote')
                        ->waitFor('form', 10)
                        ->type('name', '')
                        ->type('email', 'invalid-email')
                        ->type('phone', '')
                        ->select('service', '')
                        ->type('description', '')
                        ->press('Get Quote')
                        ->waitFor('.error', 5);
                
                $browser->type('name', "Error Recovery Quote User {$i}")
                        ->type('email', "errorrecoveryquote{$i}@example.com")
                        ->type('phone', '0412 345 678')
                        ->select('service', 'Business Cards')
                        ->type('description', "Error recovery test quote {$i}")
                        ->type('quantity', '100')
                        ->press('Get Quote')
                        ->waitFor('.success', 10);
                
                $quoteRecoveryTime = microtime(true) - $startTime;
                
                $errorRecoveryMetrics[] = [
                    'contact_recovery_time' => $contactRecoveryTime,
                    'quote_recovery_time' => $quoteRecoveryTime,
                    'iteration' => $i
                ];
            }
            
            // Calculate average recovery times
            $avgContactRecovery = array_sum(array_column($errorRecoveryMetrics, 'contact_recovery_time')) / count($errorRecoveryMetrics);
            $avgQuoteRecovery = array_sum(array_column($errorRecoveryMetrics, 'quote_recovery_time')) / count($errorRecoveryMetrics);
            
            // Performance assertions for error recovery
            $this->assertLessThan(8.0, $avgContactRecovery, "Contact error recovery too slow: {$avgContactRecovery}s");
            $this->assertLessThan(10.0, $avgQuoteRecovery, "Quote error recovery too slow: {$avgQuoteRecovery}s");
            
            Log::info('Error recovery performance test completed', [
                'avg_contact_recovery' => $avgContactRecovery,
                'avg_quote_recovery' => $avgQuoteRecovery,
                'error_recovery_metrics' => $errorRecoveryMetrics
            ]);
        });
    }
}
