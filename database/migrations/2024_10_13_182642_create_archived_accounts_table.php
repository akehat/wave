<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('archived_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();     // User ID
            $table->string('account_name')->nullable();            // Account Name
            $table->string('broker_name')->nullable();            // Broker Name
            $table->unsignedBigInteger('broker_id')->nullable();   // Broker ID
            $table->string('account_number')->nullable();          // Account Number
            $table->json('meta')->nullable();                      // Meta for additional random data
            $table->timestamp('slice_time')->nullable();           // Slice Time (timestamp of when the archive happens)
            $table->timestamps();                                  // Created_at and Updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_accounts');
    }
};
