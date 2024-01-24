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
        Schema::create('stg_stripe_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->index();
            $table->string('session_id')->index();
            $table->string('payment_intent')->index();
            $table->string('payment_method_types');
            $table->string('payment_status');
            $table->string('customer_name')->index()->nullable();
            $table->string('customer_email')->index()->nullable();
            $table->decimal('amount_total',20,2);
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
        Schema::dropIfExists('stg_stripe_payments');
    }
};
