<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boardings', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('boarding_types');
            
            
            $table->string('boarding_name');
            $table->longText('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->integer('rooms');
            $table->boolean('shared_bathroom');
            $table->integer('price');
            $table->longText('boarding_desc');
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
        Schema::dropIfExists('boardings');
    }
}
