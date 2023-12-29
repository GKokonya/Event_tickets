<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable=['start_date'];

    /*
    protected $casts = [ 
        'ticket_details' => 'array' // save songs as a json column
     ];
     */

    public function eventTicketType(){
       return $this->hasMany('App\Models\Ticket');
    }
}
