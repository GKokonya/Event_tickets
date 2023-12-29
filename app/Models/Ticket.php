<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable=['order_item_id'];




    public function eventTicketType(){
       return $this->belongsTo('App\Models\EventTicketType');   
    }

    public function order(){
        return $this->belongsTo('App\Models\Order');   
    }
}
