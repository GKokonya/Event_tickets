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
        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('id')->startingValue(1000);
            $table->foreignId('order_id')->index()->constrained('orders')->onUpdate('cascade');
            $table->foreignId('ticket_id')->unique()->index()->constrained('tickets')->onUpdate('cascade');
            $table->string('status')->nullable();
            $table->foreignId('refund_initiator_id')->index()->nullable()->constrained('users')->onUpdate('cascade');
            $table->foreignId('refund_approver_id')->index()->nullable()->constrained('users')->onUpdate('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
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
        Schema::dropIfExists('refunds');
    }
};
