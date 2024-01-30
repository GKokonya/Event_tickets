<?php
namespace App\Http\Controllers\Traits;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetail;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
trait OrderTrait {
    
    #enter order in database
    public function order( $orderDetails, $total_price, $payment_type, $phone='', $email='', ){
        #create order
        $order_data=['status'=>OrderStatus::Pending,'original_total_price'=>$total_price ,'phone'=>$phone,'final_total_price'=>$total_price,'payment_type'=>$payment_type ,'customer_email' => $email];
        $order=Order::create($order_data);

        #create order item
        foreach($orderDetails as $orderDetail){
            $orderDetail['order_id']= $order->id;
            OrderDetail::create($orderDetail);
        }


    }

    #enter order in database
    public function placeOrder($orderDetails,$total_price,$phone,$email=''){
        #create order
        $order_data=['status'=>OrderStatus::Pending,'original_total_price'=>$total_price ,'final_total_price'=>$total_price,'stripe_checkout_id'=>$stripe_checkout_id,'mpesa_checkout_id','payment_type'=>$payment_type ,'customer_email' => $email];
        $order=Order::create($order_data);

        #create order item
        foreach($orderDetails as $orderDetail){
            $orderDetail['order_id']= $order->id;
            OrderDetail::create($orderDetail);
        }

        return ['order_id' => $order->id, 'amount' => $total_price,'email' => $email, 'phone' => $phone];
    }


    #update the stus of an order
    public function updateOrderStatus(Order $order,$order_status){
        $order->status= $order_status;
        $order->update();

    }


}