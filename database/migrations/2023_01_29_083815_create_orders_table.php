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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('customer_email')->nullable()->index();
            $table->decimal('original_total_price',20,2);
            $table->decimal('refunded_amount',20,2)->default(0);
            $table->decimal('final_total_price',20,2);
            $table->string('status')->index();
            $table->string('mpesa_stk_checkout_id')->nullable()->unique()->index();
            $table->string('mpesa_reversal_conversation_id')->nullable()->unique()->index();
            $table->string('stripe_checkout_id')->nullable()->index();
            $table->string('payment_type')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
