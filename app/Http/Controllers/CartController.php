<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventTicketType;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
class CartController extends Controller
{
    //
    public function addToCart(Request $request){
        //fetch event details from database

        $validated=$request->validate([
            'event_ticket_type_id'=>'required',
            'quantity'=>'required|integer| min:1| max:10',
        ]);

        $event_ticket_type_id=$validated['event_ticket_type_id'];
        $quantity=$validated['quantity'];

        $ticket=EventTicketType::select(
            'events.id as event_id',
            'events.title as title',
            'events.image as image',
            'event_ticket_types.id as event_ticket_type_id',
            'event_ticket_types.title as ticket_type',
            'event_ticket_types.unit_price as unit_price'
        )
        ->join('events', 'events.id', '=', 'event_ticket_types.event_id')
        ->where('event_ticket_types.id', $event_ticket_type_id)
        ->firstorfail();

        #get cart from session
        $cart = session()->get('cart');

        #If cart is empty then add first item into cart
        if(empty($cart)) {
            $cart[$event_ticket_type_id] = [
                "event_ticket_type_id"=>$event_ticket_type_id,
                'event_id'=>$ticket->event_id,
                "event" => $ticket->title,
                "ticket_type"=>$ticket->ticket_type,
                "quantity" => $quantity,
                "unit_price" => $ticket->unit_price,
                "total_price"=> $ticket->unit_price*$quantity,
                "image" => Storage::url($ticket->image)
            ];
            session()->put('cart', $cart);
            return redirect()->back()->with('cart_success', 'Ticket added!');
        }

        #If cart is not empty and product exist 
        // if( !empty($cart) && !in_array($ticket->event_id ,array_column($cart,'event_id')) ) {
        //     #error
        //     return redirect()->back()->with('cart_error', 'You can only buy ticket for a single event at a time!');

        // }
    
        #If cart is not empty and product exist 
        if( !empty($cart) && isset($cart[$event_ticket_type_id]) && in_array($ticket->event_id ,array_column($cart,'event_id'))) {
            #product exist then increment quantity
            $cart[$event_ticket_type_id]['quantity']=$quantity;
            $cart[$event_ticket_type_id]['total_price']=$cart[$event_ticket_type_id]['unit_price'] * $cart[$event_ticket_type_id]['quantity'];
            session()->put('cart', $cart);
            return redirect()->back()->with('cart_success', 'Ticket updated!');
        }

        #If cart is not empty and item does not exist
        if(!empty($cart) && !isset($cart[$event_ticket_type_id]) ) {
            // if item not exist in cart then add to cart with quantity = 1
            $cart[$event_ticket_type_id] = [
                "event_ticket_type_id"=>$event_ticket_type_id,
                'event_id'=>$ticket->event_id,
                "event" => $ticket->title,
                "ticket_type"=>$ticket->ticket_type,
                "quantity" => $quantity,
                "unit_price" => $ticket->unit_price,
                "total_price"=> $ticket->unit_price*$quantity,
                "image" => Storage::url($ticket->image)
            ];
            
            session()->put('cart', $cart);
            return redirect()->back()->with('cart_succees', 'Ticket added');
        }
            
    }

    //update product
    public function update(Request $request){
        if($request->event_ticket_type_id && $request->quantity){
            if($request->quantity===0){
                $this->destroy($request->event_ticket_type_id);
            }else{
                $cart = session()->get('cart');
                $cart[$request->event_ticket_type_id]['quantity']=$request->quantity;
                session()->put('cart',$cart);
                return redirect()->back()->with('cart_succees', 'Cart updated successfully');
            }
        }
        
    }

    public function destroy($event_ticket_type_id){
        if($event_ticket_type_id){
            $cart = session()->get('cart');
            if(isset($cart[$event_ticket_type_id])){
                unset($cart[$event_ticket_type_id]);
                session()->put('cart',$cart);
            }
            return redirect()->back()->with('cart_succees', 'item remove successfully');
        }

    }

    public function destroyAll(){
        $request->session()->forget('cart');
    }

    public function test(){
        $cart = session()->get('cart');
        dd($cart);




        
    }

}
