<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Approximate location only: country from the browser's time zone, plus city/region when Cloudflare provides it.
     */
    public function up(): void
    {
        Schema::table('site_visits', function (Blueprint $table) {
            $table->string('country', 60)->nullable()->after('browser');
            $table->string('region', 120)->nullable()->after('country');
            $table->string('timezone', 50)->nullable()->after('region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_visits', function (Blueprint $table) {
            $table->dropColumn(['country', 'region', 'timezone']);
        });
    }
};
