<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripePayment extends Model
{
    use HasFactory;

    protected $fillable=[
        'session_id',
        'payment_intent',
        'amount_total',
        'customer_email',
        'customer_name',
        'payment_method_types',
        'payment_status'
    ];

}
