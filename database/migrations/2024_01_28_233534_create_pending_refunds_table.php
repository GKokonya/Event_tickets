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
        \DB::statement("
        CREATE VIEW IF NOT EXISTS pending_refunds AS   
        SELECT 
        t3.id,
        t1.order_id,
        t3.ticket_id,
        t3.refund_initiator_id,
        t1.unit_price
        FROM
        (SELECT id,unit_price,order_id FROM order_details) t1
        INNER JOIN
        (SELECT id,order_detail_id FROM tickets) t2
        ON t1.id=t2.order_detail_id
        INNER JOIN
        (SELECT id,ticket_id,refund_initiator_id,refund_approved_at FROM refunds WHERE refund_approved_at IS NULL ) t3
        ON t2.id=t3.ticket_id;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("DROP VIEW IF EXISTS pending_refunds;");
    }
};
