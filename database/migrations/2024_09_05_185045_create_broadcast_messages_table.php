<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBroadcastMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('broadcast_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();  // Add user_id for optional targeting
            $table->json('data');  // JSON field for message data
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('broadcast_messages');
    }
}
