<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=['total_price','status','customer_email','stripe_checkout_id','mpesa_checkout_id','payment_type'];

    public function payment(){
        return $this->hasOne(Payment::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }
}
