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
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('organizer_id')->constrained('users')->onUpdate('cascade');
            $table->string('title');
            $table->string('venue');
            $table->longtext('town');
            $table->string('organizer');
            $table->longtext('country');
            $table->longtext('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->longtext('image')->nullable();
            $table->longtext('twitter')->nullable();
            $table->longtext('instagram')->nullable();
            $table->longtext('facebook')->nullable();
            $table->string('status')->default('active')->index();
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
        Schema::dropIfExists('events');
    }
};
