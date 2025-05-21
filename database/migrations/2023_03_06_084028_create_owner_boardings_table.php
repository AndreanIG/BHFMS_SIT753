<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnerBoardingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owner_boardings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')
            ->on('users')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unsignedBigInteger('boarding_id');
            $table->foreign('boarding_id')->references('id')
            ->on('boardings')->cascadeOnUpdate()->cascadeOnDelete();
            
            $table->enum('owner_status',['pending','approved','declined','banned','disabled'])->default('pending');
            $table->longtext('declined_reason')->nullable();

            $table->unique(['user_id', 'boarding_id']);
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
        Schema::dropIfExists('owner_boardings');
    }
}
