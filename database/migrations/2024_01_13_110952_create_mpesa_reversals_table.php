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
        Schema::create('mpesa_reversals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ResultCode');
            $table->string('ResultDesc');
            $table->string('ResultType');
            $table->longtext('OriginatorConversationID')->index();
            $table->string('ConversationID');
            $table->foreign('ConversationID')->references('mpesa_reversal_conversation_id')->on('orders')->index();;
            $table->string('TransactionID')->index();
            $table->longtext('DebitAccountBalance');
            $table->string('Amount')->nullable();
            $table->string('TransCompletedTime')->nullable()->index();
            $table->string('OriginalTransactionID')->nullable();
            $table->string('Charge')->nullable();
            $table->longtext('CreditPartyPublicName')->nullable();            
            $table->string('DebitPartyPublicName')->nullable(); 
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_reversals');
    }
};
