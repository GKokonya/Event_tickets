<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable=['order_id','ticket_id','status','refund_initiator_id','refund_approver_id','refund_initiated_at','refund_declined_at','refund_approved_at','refund_at'];

}
