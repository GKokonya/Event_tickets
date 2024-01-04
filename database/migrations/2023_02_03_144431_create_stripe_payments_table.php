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
        Schema::create('stripe_payments', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->foreign('session_id')->references('stripe_checkout_id')->on('orders')->onUpdate('cascade');
            $table->string('payment_intent')->index();
            $table->string('payment_method_types');
            $table->string('payment_status');
            $table->string('customer_name')->index()->nullable();
            $table->string('customer_email')->index()->nullable();
            $table->decimal('amount_total',20,2);
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
        Schema::dropIfExists('stripe_payments');
    }
};
