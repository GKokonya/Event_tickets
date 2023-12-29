<?php

namespace App\Http\Controllers\Payments\StripePayments;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Order;
use App\Models\Payment;
use App\Models\orderItem;
use App\Models\Ticket;
use App\Models\StripePayment;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Traits\OrderTrait;
use App\Http\Controllers\Traits\EmailTicketTrait;

class StripeController extends Controller
{
    //
    use OrderTrait;

    
    public function checkout(Request $request){

        try{
            $stripeSecretKey=env('STRIPE_SECRET_KEY');
            $stripe=\Stripe\Stripe::setApiKey($stripeSecretKey);

            $cart=Inertia::getShared('cart');
            $line_items=[];
            $orderItems=[];
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

                $orderItems[]=[
                    'event_ticket_type_id' => $cart['items'][$key]['event_ticket_type_id'],
                    'unit_price'=>$cart['items'][$key]['unit_price'],
                    'quantity'=>$cart['items'][$key]['quantity'],
                    'total_price'=>$cart['items'][$key]['total_price']
                ];
            }
            $stripe_checkout_session = \Stripe\Checkout\Session::create([
            'line_items'  => $line_items,
            'mode' => 'payment',
            'success_url' => route('checkout.stripe.success').'?session_id={CHECKOUT_SESSION_ID}',

            'cancel_url' => route('checkout.stripe.failure'),
            ]); 

            DB::beginTransaction();

            $this->placeOrder($orderItems,$cart['total_price'],$stripe_checkout_session->id);

            DB::commit();

            //remove items from cart
            $request->session()->forget('cart');

            return Inertia::location($stripe_checkout_session->url);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            //return redirect()->back()->withErrors(['error' => 'failed to make transacrion']);
        }
    }

    public function success(Request $request){
        try{
            $session_id=$request->get('session_id');
            $stripeSecretKey=env('STRIPE_SECRET_KEY');
            \Stripe\Stripe::setApiKey($stripeSecretKey);
            $session=\Stripe\Checkout\Session::retrieve($session_id);

            //if exist i s not valid then failrure
            if(!$session){
                return Inertia::Render('Checkout/Stripe/Failure',['checkout_failure_message'=>'invalid session id']);
            }

            $payment=Payment::where('checkout_id',$session->id)->whereIn('status',[PaymentStatus::Pending,PaymentStatus::Paid])->first();

            if(!$payment  ){
                return Inertia::Render('Checkout/Stripe/Failure',['checkout_failure_message'=>'payment does not exist']);
                //throw new NotFoundHttpException();
            } 

            if($payment->status){
                $this->completeOrder($payment,$session);
            }
       
            return Inertia::Render('Checkout/Stripe/Success',['customer'=>$session->customer_details]);

       } catch (\Exception $e) {
            return Inertia::Render('Checkout/Stripe/Failure',['checkout_failure_message'=>$e->getMessage()]);
            
        }
        

    }

    public function failure(Request $request){

    }

    public function webhook(){
        $stripeSecretKey=env('STRIPE_SECRET_KEY');
        \Stripe\Stripe::setApiKey($stripeSecretKey);

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = 'whsec_e30567ed813b9374b9f67313ad3df6b445efdf0078b970e152f2edd44023be30';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
        } catch(\UnexpectedValueException $e) {
        // Invalid payload
        return response('',401);
        exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid signature
        return response('',402);
        exit();
        }

        // Handle the event
        switch ($event->type) {
        case 'checkout.session.completed':
            $paymentIntent = $event->data->object;
            $sessionId=$paymentIntent['id'];

            $payment=Payment::where(['checkout_id'=>$sessionId,'status'=> PaymentStatus::Pending])->first();

            if($payment){
                $this->completeOrder($payment,$paymentIntent);
                $this->generateTicketandSendEmail($payment->order_id);
            }
        // ... handle other event types
        default:
            echo 'Received unknown event type ' . $event->type;
        }

        return response('',200);

    }

    #update payment status and order status
    private function completeOrder(Payment $payment,$paymentIntent){
        try{
            $this->insertStripePayment($paymentIntent);
            $this->updatePaymentStatusAndOrderStatus($payment);
            $this->generateTickets($payment->order_id);

        }catch(\Exception $e){
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
       
    }


    #create ticket in database
    private function generateTickets($id){
        $orderItems=OrderItem::where('order_id',$id)->get();

        if(count($orderItems)>0){
            foreach($orderItems as $orderItem){
                for($i=0; $i<$orderItem['quantity']; $i++){
                    Ticket::create(['order_item_id'=>$orderItem['id'] ]);
                }

            }
        }
        
    }

    private function insertStripePayment($paymentIntent){

        StripePayment::create([
            'session_id'=>$paymentIntent['id'],
            'payment_intent'=>$paymentIntent['payment_intent'],
            'amount_total'=>$paymentIntent['amount_total']/100,
            'customer_email'=>$paymentIntent['customer_details']['email'],
            'customer_name'=>$paymentIntent['customer_details']['name'],
            'payment_method_types'=>$paymentIntent['payment_method_types'][0],
            'payment_status'=>$paymentIntent['payment_status']
        ]);

    }



}

