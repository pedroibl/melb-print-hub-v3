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
            if (!Schema::hasColumn('quote_requests', 'address_street')) {
                $table->string('address_street')->nullable()->after('artwork_file');
            }
            if (!Schema::hasColumn('quote_requests', 'address_suburb')) {
                $table->string('address_suburb')->nullable()->after('address_street');
            }
            if (!Schema::hasColumn('quote_requests', 'address_state')) {
                $table->string('address_state', 3)->nullable()->after('address_suburb');
            }
            if (!Schema::hasColumn('quote_requests', 'address_postcode')) {
                $table->string('address_postcode', 4)->nullable()->after('address_state');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_requests', function (Blueprint $table) {
            if (Schema::hasColumn('quote_requests', 'address_postcode')) {
                $table->dropColumn('address_postcode');
            }
            if (Schema::hasColumn('quote_requests', 'address_state')) {
                $table->dropColumn('address_state');
            }
            if (Schema::hasColumn('quote_requests', 'address_suburb')) {
                $table->dropColumn('address_suburb');
            }
            if (Schema::hasColumn('quote_requests', 'address_street')) {
                $table->dropColumn('address_street');
            }
        });
    }
};
