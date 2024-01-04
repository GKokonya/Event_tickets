<?php
namespace App\Http\Controllers\Traits;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;

trait OrderTrait {
    
    #enter order in database
    public function placeOrder($orderItems,$total_price,$stripe_checkout_id='',$mpesa_checkout_id='',$payment_type='',$email=''){
        #create order
        $order_data=['status'=>OrderStatus::Pending,'total_price'=>$total_price ,'stripe_checkout_id'=>$stripe_checkout_id,'mpesa_checkout_id','payment_type'=>$payment_type ,'customer_email' => $email];
        $order=Order::create($order_data);

        #create order item
        foreach($orderItems as $orderItem){
            $orderItem['order_id']= $order->id;
            OrderItem::create($orderItem);
        }
    }

    #update the stus of an order
    public function updateOrderStatus(Order $order,$order_status){
        $order->status= $order_status;
        $order->update();
        // dd($order);

    }
}