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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // e.g., "MPH-2025-001"
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, confirmed, in_production, completed, shipped, delivered
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 8, 2)->default(0);
            $table->decimal('shipping', 8, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_method')->nullable(); // credit_card, bank_transfer, paypal
            $table->text('shipping_address')->nullable();
            $table->string('shipping_method')->nullable(); // standard, express, pickup
            $table->date('expected_delivery')->nullable();
            $table->text('special_instructions')->nullable(); // Design requirements, rush orders
            $table->text('design_files')->nullable(); // JSON of uploaded design files
            $table->text('proof_approval')->nullable(); // Proof approval status and notes
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
