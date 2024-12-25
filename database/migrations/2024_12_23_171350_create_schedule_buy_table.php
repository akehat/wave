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
        Schema::create('schedule_buy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('timezone', 64)->nullable(); 
            $table->integer('recurring')->nullable(); 
            $table->time('time')->nullable(); 
            $table->time('server_time')->nullable(); 
            $table->date('date')->nullable(); 
            $table->json('action_json')->nullable(); 
            $table->string('broker', 100)->nullable(); 
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('schedule_buy');
    }
};
