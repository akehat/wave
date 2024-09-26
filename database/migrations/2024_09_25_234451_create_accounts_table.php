<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();     // User ID
            $table->string('account_name')->nullable();            // Account Name
            $table->string('broker_name')->nullable();            // Account Name
            $table->unsignedBigInteger('broker_id')->nullable();            // Account Name
            $table->string('account_number')->nullable();          // Account Number
            $table->json('meta')->nullable();                      // Meta for additional random data
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};
