<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class CheckoutController extends Controller
{
    //
    public function index(){
        $payment_methods=['M-PESA','CARD'];
        return Inertia::render('Checkout',['payment_methods'=>$payment_methods]);
    }

    public function paymentMethod(Request $request){
        $validated=$request->validate([
            'payment-method'=>'required'
        ]);

        if($validated=='M-PESA'){
            return Inertia::render('Mpesa');
        }

        if($validated=='CARD'){
            return Inertia::render('Stripe');
        }
    }
    
}
