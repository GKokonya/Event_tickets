<?php

namespace App\Http\Controllers\Payments\Mpesa;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MpesaResponseController extends Controller
{
    //
    public function validation(Request $request){
        Log::info('Validation endpoint hit');
        Log::info(json_encode($request->all()));

        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000, 10000)
        ];
    }

    public function confirmation(Request $request){
        Log::info('Confirmation endpoint hit');
        Log::info($request->all());

        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000, 10000)
        ];
    }
    
    public function stkPush(Request $request){
        Log::info('STK Push endpoint hit');
        Log::info($request->all());
        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
            'ThirdPartyTransID' => rand(3000, 10000)
        ];
    }

}