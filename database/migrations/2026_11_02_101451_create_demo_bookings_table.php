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
        Schema::create('demo_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_number', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('company_info');
            $table->string('status', 20)->default('new')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_bookings');
    }
};
