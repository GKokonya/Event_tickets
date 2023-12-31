<?php
namespace App\Http\Controllers\Traits;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;

use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderItem;

trait OrderTrait {
    
    #enter order in database
    public function placeOrder($orderItems,$total_price,$checkout_id,$email=''){
        #create order
        $order_data=[ 'total_price'=>$total_price , 'status'=>OrderStatus::Unpaid ,'customer_email' => $email];
        $order=Order::create($order_data);

        #create order item
        foreach($orderItems as $orderItem){
            $orderItem['order_id']= $order->id;
            OrderItem::create($orderItem);
        }

        #create payment
        $payment_data=['order_id'=>$order->id, 'amount'=>$total_price, 'status'=>PaymentStatus::Pending,'type'=>'mpesa','checkout_id'=>$checkout_id];
        $payment=Payment::create($payment_data);
    }

    #make payment status and order status as paid
    public function updatePaymentStatusAndOrderStatus(Payment $payment){
        #update payment status to paid
        $payment->status =PaymentStatus::Paid;
        $payment->update();

        #update payment status to paid
        $order=$payment->order;
        $order->status= OrderStatus::Paid;
        $order->update();

    }
}