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
        Schema::create('fct_refunds', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('ticket_id')->index();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('refund_initiator_id')->index()->nullable();
            $table->unsignedBigInteger('refund_approver_id')->index()->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('refund_initiated_at')->nullable();
            $table->timestamp('refund_declined_at')->nullable();
            $table->timestamp('refund_approved_at')->nullable();
            $table->timestamp('refund_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fct_refunds');
    }
};
