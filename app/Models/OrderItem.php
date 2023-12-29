<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable=['order_id','event_ticket_type_id','quantity', 'unit_price','total_price'];

    public function order(){
        return belongsTo(Order::class);
    }

    public function eventTicketType(){
        return belongsTo(EventTicketType::class);
    }
}
