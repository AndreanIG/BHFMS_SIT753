<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantBoardingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_boardings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')
            ->on('users')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unsignedBigInteger('boarding_id');
            $table->foreign('boarding_id')->references('id')
            ->on('boardings')->cascadeOnUpdate()->cascadeOnDelete();

            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('tenant_status',['pending','pending_payment','approved','declined', 'checkout'])->default('pending');
            $table->longtext('declined_reason')->nullable();
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
        Schema::dropIfExists('tenant_boardings');
    }
}
