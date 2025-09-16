<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Business Cards", "Flyers"
            $table->string('slug')->unique(); // e.g., "business-cards"
            $table->text('description');
            $table->string('category'); // e.g., "Business Cards", "Signs & Banners", "Promotional Products"
            $table->string('subcategory')->nullable(); // e.g., "Standard", "Premium", "Folded"
            $table->decimal('base_price', 8, 2); // Starting price
            $table->json('pricing_options')->nullable(); // Different quantities and prices
            $table->json('specifications')->nullable(); // Size, paper type, finish options
            $table->json('design_templates')->nullable(); // Available design templates
            $table->string('image')->nullable(); // Product image
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
