<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complains', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')
            ->on('users')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unsignedBigInteger('boarding_id');
            $table->foreign('boarding_id')->references('id')
            ->on('boardings')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unsignedBigInteger('complain_type_id');
            $table->foreign('complain_type_id')->references('id')
            ->on('complain_types')->cascadeOnUpdate()->cascadeOnDelete();

            $table->longtext('complain_desc');
            $table->longtext('complain_image_url');
            $table->enum('complain_status',['pending','finished','on progress'])->default('pending');


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
        Schema::dropIfExists('complains');
    }
}
