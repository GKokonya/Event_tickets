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
        CREATE VIEW IF NOT EXISTS event_organizer_revenue  AS 
        SELECT 
        t1.id,
        t1.title AS event,
        t1.organizer_id AS organizer_id,
        sum(t2.unit_price) amount,
        t3.payment_type
        FROM
        (SELECT a.id,a.title,a.organizer_id,b.id AS event_ticket_type_id from events a INNER JOIN event_ticket_types b ON a.id=b.event_id) t1
        INNER JOIN
        (SELECT id,order_id,event_ticket_type_id ,unit_price FROM order_details) t2
        ON t1.event_ticket_type_id =t2.event_ticket_type_id 

        INNER JOIN 
        (SELECT id,payment_type from orders) t3
        ON t2.order_id=t3.id

        INNER JOIN
        (SELECT a.id,order_detail_id FROM tickets a LEFT JOIN refunds b ON a.id=b.ticket_id WHERE  b.ticket_id IS NULL ) t4
        ON t2.id=t4.order_detail_id
        GROUP BY id,event,organizer_id,payment_type;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("DROP VIEW IF EXISTS event_organizer_revenue;");
        
    }
};
