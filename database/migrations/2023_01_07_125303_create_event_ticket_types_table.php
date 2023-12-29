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
        Schema::create('event_ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->index()->constrained('events')->onUpdate('cascade');
            $table->string('title');
            $table->integer('unit_price');
            $table->integer('quantity');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_ticket_types');
    }
};
