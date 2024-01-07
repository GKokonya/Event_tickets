<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stk_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('merchantRequestID')->index();
            $table->string('checkoutRequestID')->index();
            $table->foreign('checkoutRequestID')->references('mpesa_checkout_id')->on('orders');
            $table->string('responseDescription');
            $table->longtext('responseCode');
            $table->string('customerMessage');
            $table->string('status'); //requested , paid , failed
            $table->string('resultCode')->index()->nullable();
            $table->longtext('resultDesc')->nullable();
            $table->float('amount')->nullable();
            $table->string('mpesaReceiptNumber')->nullable()->index();
            $table->string('balance')->nullable();
            $table->datetime('transactionDate')->nullable();
            $table->string('phoneNumber')->nullable();            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stk_payments');
    }
};
