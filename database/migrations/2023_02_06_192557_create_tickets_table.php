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
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id')->startingValue(1000);
            $table->foreignId('order_detail_id')->index()->constrained('order_details')->onUpdate('cascade');
            $table->foreignId('scanned_by')->index()->nullable()->constrained('users')->onUpdate('cascade');
            $table->timestamp('scanned_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
