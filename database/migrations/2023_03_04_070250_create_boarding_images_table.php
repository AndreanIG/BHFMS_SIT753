<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardingImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boarding_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('boarding_id');
            $table->foreign('boarding_id')->references('id')
            ->on('boardings')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('image');
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
        Schema::dropIfExists('boarding_images');
    }
}
