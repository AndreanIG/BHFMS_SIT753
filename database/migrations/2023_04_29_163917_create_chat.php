<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->references('id')
            ->on('users')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unsignedBigInteger('receiver_id');
            $table->foreign('receiver_id')->references('id')
            ->on('users')->cascadeOnUpdate()->cascadeOnDelete();

            $table->longtext('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
