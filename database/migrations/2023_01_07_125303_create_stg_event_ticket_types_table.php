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
        Schema::create('stg_event_ticket_types', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->index();
            $table->unsignedBigInteger('event_id')->index();
            $table->string('title');
            $table->integer('unit_price');
            $table->integer('quantity');
            $table->string('status');
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('stg_event_ticket_types');
    }
};
