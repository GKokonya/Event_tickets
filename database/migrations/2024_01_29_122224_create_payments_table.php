<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('payment_id');
            $table->string('txncd')->nullable()->index();
            $table->string('qwh')->nullable()->index();
            $table->string('afd')->nullable()->index();
            $table->string('poi')->nullable()->index();
            $table->string('uyt')->nullable()->index();
            $table->string('ifd')->nullable()->index();
            $table->string('agt')->nullable()->index();
            $table->foreignId('id')->index()->constrained('orders')->onUpdate('cascade');
            $table->string('status')->nullable()->index();
            $table->string('ivm')->nullable()->index();
            $table->string('mc')->nullable()->index();
            $table->string('p1')->nullable()->index();
            $table->string('p2')->nullable()->index();
            $table->string('p3')->nullable()->index();
            $table->string('p4')->nullable()->index();
            $table->string('msisdn_id')->nullable()->index();
            $table->string('msisdn_idnum')->nullable()->index();
            $table->string('channel')->nullable()->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
