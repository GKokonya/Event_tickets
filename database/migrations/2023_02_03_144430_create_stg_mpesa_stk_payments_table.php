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
        Schema::create('stg_stk_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->index();
            $table->string('MerchantRequestID');
            $table->string('CheckoutRequestID');
            $table->longtext('ResponseDescription');
            $table->longtext('ResponseCode');
            $table->string('CustomerMessage');
            $table->string('Status');
            $table->string('ResultCode')->index()->nullable();
            $table->longtext('ResultDesc')->nullable();
            $table->integer('Amount')->nullable();
            $table->string('MpesaReceiptNumber')->nullable()->index();
            $table->string('Balance')->nullable();
            $table->datetime('TransactionDate')->nullable();
            $table->string('PhoneNumber')->nullable();            
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stg_stk_payments');
    }
};
