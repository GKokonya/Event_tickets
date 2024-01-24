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
        Schema::create('stg_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->index();
            $table->string('customer_email')->nullable();
            $table->decimal('original_total_price',20,2);
            $table->decimal('refunded_amount',20,2);
            $table->decimal('final_total_price',20,2);
            $table->string('status');
            $table->string('mpesa_stk_checkout_id')->nullable();
            $table->string('mpesa_reversal_conversation_id')->nullable();
            $table->string('stripe_checkout_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stg_orders');
    }
};
