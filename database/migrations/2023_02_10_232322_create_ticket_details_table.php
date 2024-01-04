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
        \DB::statement("
        CREATE VIEW IF NOT EXISTS ticket_details  AS 
        SELECT 
        t1.id,
        t3.id AS order_id,
        t3.customer_email ,
        t2.event_ticket_type_id,
        t2.id AS order_items_id,
        t2.unit_price,
        t4.title AS event_ticket_type,
        t5.title,
        t5.image,
        t5.venue, 
        t5.town,
        t5.country,
        t5.start_date as start_date,
        t5.start_time as start_time,
        t5.end_date as end_date,
        t5.end_time as end_time
        FROM
        (SELECT id,order_item_id FROM tickets) t1
        INNER JOIN
        (SELECT id,order_id,event_ticket_type_id ,unit_price FROM order_items) t2
        ON t1.order_item_id=t2.id
        INNER JOIN 
        (SELECT id,customer_email  from orders) t3
        ON t2.order_id=t3.id
        INNER JOIN
        (SELECT id,event_id,title from event_ticket_types)  t4
        ON t2.event_ticket_type_id=t4.id
        INNER join 
        (SELECT id,title,image,venue,town,country,start_date,start_time,end_date,end_time from events ) t5
        ON t4.event_id=t5.id
        ");
       
    }


        /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("DROP VIEW IF EXISTS ticket_details;");
    }

};