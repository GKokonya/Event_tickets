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
            $table->unsignedBigInteger('order_detail_id')->nullable();
            $table->unsignedBigInteger('scanned_by')->nullable();
            $table->timestamp('scanned_at')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
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
