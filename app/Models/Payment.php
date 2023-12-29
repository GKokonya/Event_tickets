<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable=['order_id','amount','status','type','checkout_id'];

    
    public function order(){
        return $this->hasOne('App\Models\Order','id','order_id');
    }
}
