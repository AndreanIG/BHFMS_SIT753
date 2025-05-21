<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

     
    protected $guarded = [];

    public function up()
    {
        Schema::create('rent_transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tenant_boarding_id');
            $table->foreign('tenant_boarding_id')->references('id')
            ->on('tenant_boardings')->cascadeOnUpdate()->cascadeOnDelete();
            
            $table->unsignedBigInteger('transaction_type_id');
            $table->foreign('transaction_type_id')->references('id')
            ->on('transaction_types')->cascadeOnUpdate()->cascadeOnDelete();
            
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->foreign('payment_method_id')->references('id')
            ->on('payment_methods')->cascadeOnUpdate()->cascadeOnDelete();
            
            $table->unsignedBigInteger('invoice_id');
            $table->integer('amount');
            $table->date('payment_date');
            $table->enum('payment_status',['Pending','Processing','Approved','Rejected','Late','Canceled'])->default('Pending');
            $table->enum('payment_transferred_status',['Pending','Successful','Declined','Processing_Refund','Processing_Refund_Success'])->default('Pending');
            $table->boolean('repeat_payment')->default(false);
            $table->timestamps();

            
            $table->unique('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rent_transactions');
    }
}
