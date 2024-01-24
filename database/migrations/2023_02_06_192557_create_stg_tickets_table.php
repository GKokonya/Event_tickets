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
        Schema::create('stg_tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->index();
            $table->unsignedBigInteger('order_detail_id')->nullable();
            $table->unsignedBigInteger('refund_initiator_id')->nullable();;
            $table->unsignedBigInteger('refund_approver_id')->nullable();;
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();;
            $table->dateTime('read_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stg_tickets');
    }
};
