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
        $databasePath = database_path('schedule.sqlite');
        if (!file_exists($databasePath)) {
            touch($databasePath); // Create an empty SQLite file
        }
        Schema::connection('sqlite_schedule')->create('schedule_buy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('timezone', 64);
            $table->time('time');
            $table->time('server_time');
            $table->date('date');
            $table->json('action_json');
            $table->string('broker', 100);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::connection('sqlite_schedule')->dropIfExists('schedule_buy');
         // Delete the schedule.sqlite file
         $databasePath = database_path('schedule.sqlite');
         if (file_exists($databasePath)) {
             unlink($databasePath);
         }
    }
};
