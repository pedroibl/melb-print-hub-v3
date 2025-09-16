<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Product;
use App\Models\QuoteRequest;
use App\Models\ContactMessage;

class WebpageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test 1: Homepage route loads successfully
     */
    public function test_homepage_route_loads()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Melbourne Print Hub');
    }

    /**
     * Test 2: Services page route loads and products are seeded
     */
    public function test_services_page_and_products()
    {
        // Seed the database with products
        $this->artisan('db:seed', ['--class' => 'ProductSeeder']);

        $response = $this->get('/services');
        $response->assertStatus(200);
        
        // Check that products were seeded correctly
        $products = Product::all();
        $this->assertCount(13, $products); // Should have 13 services

        // Verify all categories are present
        $categories = $products->pluck('category')->unique();
        $this->assertContains('Business Essentials', $categories);
        $this->assertContains('Banner Solutions', $categories);
        $this->assertContains('Signage & Display', $categories);
    }

    /**
     * Test 3: Quote form route loads
     */
    public function test_quote_form_route_loads()
    {
        $response = $this->get('/get-quote');
        $response->assertStatus(200);
    }

    /**
     * Test 4: Contact form route loads
     */
    public function test_contact_form_route_loads()
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    /**
     * Test 5: Database connectivity and basic CRUD operations
     */
    public function test_database_connectivity_and_crud()
    {
        // Test database connection
        $this->assertDatabaseHas('migrations', []);

        // Test products table structure and seeding
        $this->artisan('db:seed', ['--class' => 'ProductSeeder']);
        
        $products = Product::all();
        $this->assertCount(13, $products); // Should have 13 services

        // Test quote_requests table structure
        $quoteRequest = QuoteRequest::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0449 000 000',
            'service' => 'Business Cards',
            'quantity' => '100',
            'description' => 'Test description'
        ]);

        $this->assertDatabaseHas('quote_requests', [
            'id' => $quoteRequest->id,
            'name' => 'Test User'
        ]);

        // Test contact_messages table structure
        $contactMessage = ContactMessage::create([
            'name' => 'Test Contact',
            'email' => 'contact@example.com',
            'phone' => '0449 111 111',
            'subject' => 'Test Subject',
            'message' => 'Test message content'
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'id' => $contactMessage->id,
            'name' => 'Test Contact'
        ]);

        // Verify urgency field is completely removed
        $this->assertFalse(\Schema::hasColumn('quote_requests', 'urgency'));
    }
}
