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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Snapshot of product name at time of order
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_price', 8, 2);
            $table->json('options')->nullable(); // Size, paper type, finish, quantity breaks
            $table->text('design_notes')->nullable(); // Specific design requirements
            $table->string('design_file')->nullable(); // Path to uploaded design file
            $table->string('proof_status')->default('pending'); // pending, approved, rejected, revised
            $table->text('proof_notes')->nullable(); // Feedback on proofs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
