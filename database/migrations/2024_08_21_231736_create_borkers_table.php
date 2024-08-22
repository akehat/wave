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
        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id'); // Reference to users table
            $table->string('broker_name'); // Name of the broker
            $table->boolean('enabled')->default(false)->nullable(); // Is the broker enabled?
            $table->boolean('confirmed')->default(false)->nullable(); // Is the broker enabled?
            $table->string('username')->nullable(); // Username for the broker
            $table->string('password')->nullable(); // Password for the broker
            $table->string('token')->nullable(); // Token (if applicable)
            $table->string('phone_last_four')->nullable(); // Last four digits of the phone (for brokers like Vanguard)
            $table->string('debug')->nullable(); // Debug flag
            $table->string('email')->nullable(); // Email for brokers like Fennel
            $table->string('pin')->nullable(); // PIN for brokers like Firstrade
            $table->string('totp')->nullable(); // TOTP for brokers with 2FA (e.g., Robinhood, Schwab)
            $table->string('did')->nullable(); // DID for Webull
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brokers');
    }
};
