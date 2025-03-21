<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id')->nullable(); // Account ID
            $table->unsignedBigInteger('user_id')->nullable();    // User ID
            $table->string('broker_name')->nullable();            // Broker Name
            $table->unsignedBigInteger('broker_id')->nullable();  // Broker ID
            $table->string('stock_name')->nullable();             // Stock Name
            $table->integer('shares')->nullable();                // Number of Shares
            $table->decimal('price', 15, 2)->nullable();          // Price per Share
            $table->json('meta')->nullable();                     // Meta for additional random data
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
};

