<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Traits\OrderTrait;
class PaymentController extends Controller
{

     #make http request to ip
    private function makeHttp($url, $body)
    {
        $url = $url;
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                    CURLOPT_URL => $url,
                    CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($body)
                )
        );
        $curl_response = curl_exec($curl);
        curl_close($curl);
        return $curl_response;
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

            DB::beginTransaction();


            DB::commit();
            $request->session()->forget('cart');

            return Inertia::location($stripe_checkout_session->url);
        } catch (\Exception $e) {
            //DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            //return redirect()->back()->withErrors(['error' => 'failed to make transacrion']);
        }
    }

    public function pay(Request $request)
    {



        // $fields = $request->validate([
        //     'oid' => 'required',
        //     'ttl' => ['required','integer'],
        //     'tel' => ['integer','required','min:10','max:15'],
        //     'eml' => ['email','required']
        // ]);

        $fields = array(
            'oid' => 110,
            'ttl' => 1,
            'tel' => 254717149701,
            'eml' => 'gbkoks196:@gmail.com'
        );

        $p1="";
        $p2="";
        $p3="";
        $p4="";

        /*
        This is a sample PHP script of how you would ideally integrate with iPay Payments Gateway and also handling the 
        callback from iPay and doing the IPN check

        /*
        ----------------------------------------------------------------------------------------------------
        ************(A.) GENERATING THE HASH PARAMETER FROM THE DATASTRING *********************************
        ----------------------------------------------------------------------------------------------------
    
        The datastring IS concatenated from the data above
        */
        $datastring = env("IPAY_LIVE").$fields['oid'].$fields['oid'].$fields['ttl'].$fields['tel'].$fields['eml'].env("IPAY_VID"). env("IPAY_CURR").$p1.$p2.$p3.$p4.env("IPAY_CBK").env("IPAY_CST").env("IPAY_CRL");
        $hashkey ="demoCHANGED";//use "demoCHANGED" for testing where vid is set to "demo"
    

            /********************************************************************************************************
        * Generating the HashString sample
        */
        $generated_hash = hash_hmac('sha1',$datastring , $hashkey);

        /*
        ----------------------------------------------------------------------------------------------------
                    ************(B.) INTEGRATING WITH iPAY ***********************************************
        ----------------------------------------------------------------------------------------------------
        */
        //Data needed by iPay a fair share of it obtained from the user from a form e.g email, number etc...
             $curl_post_data = array(
                "live"=> env("IPAY_LIVE"),
                "oid"=>$fields['oid'],
                "inv"=> $fields['oid'],
                "ttl"=> $fields['ttl'],
                "tel"=> $fields['tel'],
                "eml"=> $fields['eml'],
                "vid"=> env("IPAY_VID"),
                "curr"=>  env("IPAY_CURR"),
                "p1"=> $p1,
                "p2"=> $p2,
                "p3"=> $p3,
                "p4"=> $p4,
                "cbk"=> $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"],
                "cst"=> env("IPAY_CST"),
                "crl"=> env("IPAY_CRL"),
                "hsh"=> $generated_hash
              );
    
            $url = 'https://payments.ipayafrica.com/v3/ke';

            $get_cart=$this->getCart();

            $this->order($get_cart['orderDetails'],$get_cart['total_price'],$fields['tel'],$fields['eml'],$payment_type='ipay');

           return $response = $this->makeHttp($url, $curl_post_data);
    
    }


    public function paymentVerification(){
        $val = ""; //assigned iPay Vendor ID... hard code it here.
        /*
        these values below are picked from the incoming URL and assigned to variables that we
        will use in our security check URL
        */
        $val1 = $_GET["id"];
        $val2 = $_GET["ivm"];
        $val3 = $_GET["qwh"];
        $val4 = $_GET["afd"];
        $val5 = $_GET["poi"];
        $val6 = $_GET["uyt"];
        $val7 = $_GET["ifd"];
        
        $ipnurl = "https://www.ipayafrica.com/ipn/?vendor=".$val."&id=".$val1."&ivm=".
        $val2."&qwh=".$val3."&afd=".$val4."&poi=".$val5."&uyt=".$val6."&ifd=".$val7;
        $fp = fopen($ipnurl, "rb");
        $status = stream_get_contents($fp, -1, -1);
        fclose($fp);
        //the value of the parameter “vendor”, in the url being opened above, is your iPay assignedVendor ID.
        //this is the correct iPay status code corresponding to this transaction.
        //Use it to validate your incoming transaction(not the one supplied in the incoming url)
        
        //continue your shopping cart update routine code here below....
        //then redirect to to the customer notification page here...

        # The status variable has the following possible values:-
        # fe2707etr5s4wq = Failed transaction. Not all parameters fulfilled. A notification of this transaction sent to the merchant.
        # aei7p7yrx4ae34 = Success: The transaction is valid. Therefore you can update this transaction.
        # bdi6p2yy76etrs = Pending: Incoming Mobile Money Transaction Not found. Please try again in 5 minutes.
        # cr5i3pgy9867e1 = Used: This code has been used already. A notification of this transaction sent to the merchant.
        # dtfi4p7yty45wq = Less: The amount that you have sent via mobile money is LESS than what was required to validate this transaction.
        # eq3i7p5yt7645e = More: The amount that you have sent via mobile money is MORE than what was required to validate this transaction. (Up to the merchant to decide what to do with this transaction; whether to pass it or not)
        Storage::disk('local')->put('stk.txt',   $status);

        #Failed
        if($tatus=='fe2707etr5s4wq'){
            dd('Failed transaction. Not all parameters fulfilled. A notification of this transaction sent to the merchant')
        }

        #Success
        if($status=='aei7p7yrx4ae34'){
            dd('Success: The transaction is valid. Therefore you can update this transaction.')
        }

        #Pending
        if($status=='bdi6p2yy76etrs'){
            dd('Pending: Incoming Mobile Money Transaction Not found. Please try again in 5 minutes.')
        }

        
        #Used
        if($status=='cr5i3pgy9867e1'){
            dd('Used: This code has been used already. A notification of this transaction sent to the merchant.')
        }

        
        #Less
        if($status=='dtfi4p7yty45wq'){
            dd('Less: The amount that you have sent via mobile money is LESS than what was required to validate this transaction.')
        }

        
        #More
        if($status=='eq3i7p5yt7645e'){
            dd('More: The amount that you have sent via mobile money is MORE than what was required to validate this transaction. (Up to the merchant to decide what to do with this transaction; whether to pass it or not)')
        }

    
    }

}
