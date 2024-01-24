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
        Schema::create('stg_order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('event_ticket_type_id')->index();
            $table->integer('quantity');
            $table->decimal('unit_price',20,2);
            $table->decimal('total_price',20,2);
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
        Schema::dropIfExists('stg_order_details');
    }
};
