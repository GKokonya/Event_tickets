<?php

namespace App\Http\Controllers\Payments\StripePayments;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\StripePayment;

use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Traits\OrderTrait;
use App\Http\Controllers\Traits\EmailTicketTrait;

use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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

            $this->placeOrder($orderItems,$cart['total_price'],$stripe_checkout_session->id,'',$payment_type='stripe','');
            DB::commit();
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

            $order=Order::where('stripe_checkout_id',$session->id)->first();

            if(!$order){
                return Inertia::Render('Checkout/Stripe/Failure',['checkout_failure_message'=>'order does not exist']);
                //throw new NotFoundHttpException();
            } 

            if($order->status===OrderStatus::Pending->value){
                $this->completeOrder($order,$session);
            }
       
            return Inertia::Render('Checkout/Stripe/Success',['customer'=>$session->customer_details]);

       } catch (\Exception $e) {
            return Inertia::Render('Checkout/Stripe/Failure',['checkout_failure_message'=>$e->getMessage()]);
            
        }
        

    }

    public function failure(Request $request){
        $order=Order::where('stripe_checkout_id','cs_test_a1oPQfEXEuXoJWkgWTXKynXycwcnLIy4RH72lLiv4EQnlSfeGpCBD9EcW8')->first();
        if($order->status===OrderStatus::Pending->value){

        $this->updateOrderStatus($order,OrderStatus::Paid->value);
                dd('good');
        }
        #dd();
    }

    public function webhook(){
        $stripeSecretKey=env('STRIPE_SECRET_KEY');
        \Stripe\Stripe::setApiKey($stripeSecretKey);

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

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

            $order=Order::where('stripe_mpesa_checkout_id',$sessionId)->first();

            if($order && $order->status==OrderStatus::Pending){
                $this->completeOrder($payment,$paymentIntent);
            }
        // ... handle other event types
        default:
            echo 'Received unknown event type ' . $event->type;
        }

        return response('',200);

    }

    #update order status
    private function completeOrder(Order $order,$paymentIntent){
        try{
            $this->insertStripePayment($paymentIntent);
            $this->updateOrderStatus($order,OrderStatus::Paid->value);
            #send email to customer
            #$this->generateTickets($payment->order_id);

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

    public function index(){
        #$permissions = Permission::search('name',$this->search_keyword)->latest()->paginate(10);
 
         $stripePayments = QueryBuilder::for(StripePayment::class)
         ->defaultSort('id')
        ->allowedSorts(['id','session_id','payment_intent','payment_method_types','payment_status','customer_name','customer_email','amount_total'])
         ->allowedFilters(['id','session_id','payment_intent','payment_method_types','payment_status','customer_name','customer_email','amount_total'])
         ->paginate(10)
         ->withQueryString();
         


         return Inertia::render('Stripe/Index', [
             'stripePayments' => $stripePayments
         ])->table(function(InertiaTable $table){
             $table
             ->defaultSort('id')
             ->column(key: 'session_id', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'payment_intent', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'payment_method_types', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'payment_status', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'customer_name', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'customer_email', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'amount_total', searchable: true, sortable: true, canBeHidden: false)
             ;
         }); 
     }

}

