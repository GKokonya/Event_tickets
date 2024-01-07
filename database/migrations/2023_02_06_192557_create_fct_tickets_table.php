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
        Schema::create('fct_tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->index();
            $table->foreignId('order_detail_id')->index()->constrained('order_details')->onUpdate('cascade');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();;
            $table->dateTime('read_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fct_tickets');
    }
};
