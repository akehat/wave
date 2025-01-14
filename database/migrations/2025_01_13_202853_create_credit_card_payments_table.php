<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditCardPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('credit_cards', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id')->nullable(); // Foreign key to users table
            $table->string('email')->nullable(); // User's email
            $table->string('phone')->nullable(); // User's phone number
            $table->string('area_code', 5)->nullable(); // Area code
            $table->string('stripe_id')->nullable(); // Stripe customer ID
            $table->string('card_brand')->nullable(); // Brand of the card (e.g., Visa, Mastercard)
            $table->string('last_four_digits')->nullable(); // Last four digits of the card
            $table->string('card_holder_name')->nullable(); // Cardholder's name
            $table->string('billing_address_line1')->nullable(); // Billing address line 1
            $table->string('billing_address_line2')->nullable(); // Billing address line 2
            $table->string('billing_city')->nullable(); // Billing city
            $table->string('billing_state')->nullable(); // Billing state
            $table->string('billing_zip')->nullable(); // Billing ZIP code
            $table->string('billing_country')->nullable(); // Billing country
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_cards');
    }
};
