<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\TicketDetail;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $orders = Order::select('id','customer_email','original_total_price', 'refunded_amount','final_total_price','status','mpesa_stk_checkout_id','stripe_checkout_id','payment_type')
        ->get();
        
        return Inertia::render('Orders/Index', [
            'orders' => $orders //Order::paginate(10)
        ]);
    }




    public function refund($order_id){
     
        $ticket_details = TicketDetail::select('id', 'order_id','event_title','event_ticket_type','event_ticket_type_id')->where('order_id',$order_id)->get();
        session()->put('selected_order_id', $order_id);
         return Inertia::render('Orders/Refund', [
              'ticket_details' => $ticket_details
         ]);
     
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
