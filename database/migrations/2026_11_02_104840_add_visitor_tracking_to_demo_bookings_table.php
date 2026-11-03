<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Links a booking to the anonymous analytics visit it came from, so admins can see the visitor's journey.
     */
    public function up(): void
    {
        Schema::table('demo_bookings', function (Blueprint $table) {
            $table->uuid('visitor_id')->nullable()->index()->after('company_info');
            $table->foreignId('site_visit_id')->nullable()->after('visitor_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('site_visit_id');
            $table->dropColumn('visitor_id');
        });
    }
};
