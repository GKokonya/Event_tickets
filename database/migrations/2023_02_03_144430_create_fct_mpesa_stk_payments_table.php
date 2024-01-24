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
        Schema::create('fct_stk_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->index();
            $table->string('MerchantRequestID')->index();
            $table->string('CheckoutRequestID')->index();
            $table->longtext('ResponseDescription');
            $table->longtext('ResponseCode');
            $table->string('CustomerMessage');
            $table->string('Status'); //requested , paid , failed
            $table->string('ResultCode')->index()->nullable();
            $table->longtext('ResultDesc')->nullable();
            $table->integer('Amount')->nullable();
            $table->string('MpesaReceiptNumber')->nullable()->index();
            $table->string('Balance')->nullable();
            $table->datetime('TransactionDate')->nullable();
            $table->string('PhoneNumber')->nullable();            
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
        Schema::dropIfExists('fct_stk_payments');
    }
};
