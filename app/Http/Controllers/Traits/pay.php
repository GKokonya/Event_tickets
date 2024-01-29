<?php
namespace App\Http\Controllers\Traits;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetail;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
trait OrderTrait {
    
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

        return ['order_id' => $order->id, 'amount' => $total_price,'email' => $email, 'phone' => $phone]
    }

    // public function placeOrder($orderDetails,$total_price,$stripe_checkout_id='',$mpesa_checkout_id='',$payment_type='',$email=''){
    //     #create order
    //     $order_data=['status'=>OrderStatus::Pending,'original_total_price'=>$total_price ,'final_total_price'=>$total_price,'stripe_checkout_id'=>$stripe_checkout_id,'mpesa_checkout_id','payment_type'=>$payment_type ,'customer_email' => $email];
    //     $order=Order::create($order_data);

    //     #create order item
    //     foreach($orderDetails as $orderDetail){
    //         $orderDetail['order_id']= $order->id;
    //         OrderDetail::create($orderDetail);
    //     }

    //     return ['order_id' => $order->id, 'amount' => $total_price,'email' => $email]
    // }

    #update the stus of an order
    public function updateOrderStatus(Order $order,$order_status){
        $order->status= $order_status;
        $order->update();

    }


    public function getCart(){
        
        try{

            $cart=Inertia::getShared('cart');
            $line_items=[];
            $orderDetails=[];
            foreach($cart['items'] as $key=>$value){
                $line_items[]=[
                    'price_data' => [
                        'currency'     => 'kes',
                        'product_data' => [
                            'name' => 'Event: '.$cart['items'][$key]['event'],
                            'description'=>'Ticket: '.$cart['items'][$key]['ticket_type'],
                            ],
                        'unit_amount'  => $cart['items'][$key]['unit_price']*100,
                    ],
                    'quantity'   =>$cart['items'][$key]['quantity'],
                ];

                $orderDetails[]=[
                    'event_ticket_type_id' => $cart['items'][$key]['event_ticket_type_id'],
                    'unit_price'=>$cart['items'][$key]['unit_price'],
                    'quantity'=>$cart['items'][$key]['quantity'],
                    'total_price'=>$cart['items'][$key]['total_price']
                ];
            }

            // DB::beginTransaction();

            // $this->placeOrder($orderDetails,$cart['total_price'],$stripe_checkout_session->id,'',$payment_type='stripe','');
            // DB::commit();
            $request->session()->forget('cart');

            return ['orderDetails'=> $orderDetails,'total_price' => $cart['total_price'] ];

            // return Inertia::location($stripe_checkout_session->url);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            //return redirect()->back()->withErrors(['error' => 'failed to make transacrion']);
        }
    }
}