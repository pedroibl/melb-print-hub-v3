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
        Schema::table('quote_requests', function (Blueprint $table) {
            $table->string('service_category')->nullable()->after('service');
            $table->string('size')->nullable()->after('quantity');
            $table->string('artwork_file')->nullable()->after('size');
            $table->text('delivery_address')->nullable()->after('description');
            $table->text('special_requirements')->nullable()->after('delivery_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_requests', function (Blueprint $table) {
            $table->dropColumn([
                'service_category',
                'size',
                'artwork_file',
                'delivery_address',
                'special_requirements'
            ]);
        });
    }
};
