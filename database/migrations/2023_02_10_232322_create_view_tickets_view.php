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
        CREATE VIEW IF NOT EXISTS view_tickets  AS 
        SELECT 
        tickets.id AS id,
        orders.customer_email as customer_email,
        order_items.event_ticket_type_id AS event_ticket_type_id,
        order_items.id AS order_items_id,
        order_items.unit_price AS unit_price,
        orders.id AS order_id,
        event_ticket_types.title AS event_ticket_type,
        events.title AS event,
        events.image AS image,
        events.venue AS venue, 
        events.town AS town,
        events.country AS country,
        events.start_date as start_date,
        events.start_time as start_time,
        events.end_date as end_date,
        events.end_time as end_time
        FROM tickets
        INNER JOIN order_items ON tickets.order_item_id=order_items.id
        INNER JOIN orders ON order_items.order_id=orders.id
        INNER JOIN event_ticket_types ON order_items.event_ticket_type_id=event_ticket_types.id
        INNER join events ON event_ticket_types.event_id=events.id;
        ");
       
    }


        /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("DROP VIEW IF EXISTS view_tickets;");
    }

};
