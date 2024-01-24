<?php

namespace App\Http\Controllers\Payments\Mpesa;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Http\Controllers\Traits\MpesaTrait;
use App\Http\Controllers\Traits\OrderTrait;

use App\Models\MpesaIpAddress;
use App\Models\MpesaStkPayment;
use App\Models\Order;

use App\Enums\MpesaStkPaymentStatus;
use App\Enums\OrderStatus;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Http;

use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MpesaStkController extends Controller
{
    use MpesaTrait, OrderTrait;

    #function to retrun STK push UI/view
    public function stk(){
        return Inertia::render('Checkout/Mpesa/Stk/Stk');
    }

    public function fakeStk(){
        $url='https://12700.eu-1.sharedwithexpose.com//checkout/stk/process-stk-callback';
        $data='
        {
            "Body": {
            
                "stkCallback": {
                
                    "MerchantRequestID": "29115-34620561-11",
                    
                    "CheckoutRequestID": "ws_CO_1912201910203639725",
                    
                    "ResultCode": 0,
                    
                    "ResultDesc": "The service request is processed successfully.",
                
                    "CallbackMetadata": {
                    
                        "Item": [
                            {"Name": "Amount","Value": 2000.00},
                            {"Name": "MpesaReceiptNumber","Value": "ZNLJ7RTD1SK"},
                            {"Name": "TransactionDate","Value": 20191219102115},
                            {"Name": "PhoneNumber","Value": 254700000000}
                        ]
                    }
                
                }
            }
        }';

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                    CURLOPT_URL => $url,
                    CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($data)
                )
            );
        $curl_response = curl_exec($curl);
        curl_close($curl);
        return $curl_response;
    }

     #checkout with mpesa Sim TooKit(STK)
     public function checkout(Request $request){
        try{
            $validated=$request->validate([
                'phone_number'=>'integer | required | min:9',
                'country_code'=>'integer| required',
                'email'=> 'email | required'
            ]);

            $phoneNumber=$validated['country_code'].$validated['phone_number'];

            $cart=Inertia::getShared('cart');

            $stk=$this->initiateStk($phoneNumber,$cart['total_price']);
            $lnmo_response=json_decode($stk);
            
            $checkoutRequestID=$lnmo_response->CheckoutRequestID;
            $merchantRequestID=$lnmo_response->MerchantRequestID;
            $responseCode=$lnmo_response->ResponseCode;
            $responseDescription=$lnmo_response->ResponseDescription;
            $customerMessage=$lnmo_response->CustomerMessage;

            if($responseCode==0){
                $orderItems=[];
                foreach($cart['items'] as $key=>$value){
                    $orderItems[]=[
                        'event_ticket_type_id' => $cart['items'][$key]['event_ticket_type_id'],
                        'unit_price'=>$cart['items'][$key]['unit_price'],
                        'quantity'=>$cart['items'][$key]['quantity'],
                        'total_price'=>$cart['items'][$key]['total_price']
                    ];
                }

                DB::beginTransaction();

                $this->placeOrder( $orderItems, $cart['total_price'], $lnmo_response->CheckoutRequestID,$payment_type='mpesa', $validated['email'] );
                
                #store in database
                MpesaStkPayment::create([
                    'responseDescription'=>$responseDescription,
                    'responseCode'=>$responseCode,
                    'customerMessage'=>$customerMessage,
                    "merchantRequestID"=>$merchantRequestID, 
                    "checkoutRequestID"=>$checkoutRequestID,
                    "amount"=>$cart['total_price'], 
                    "status"=>MpesaStkPaymentStatus::Requested,
                    "phoneNumber"=>$validated['country_code'].$validated['phone_number']
                ]);
           
                DB::commit();

                //remove items from cart
               // $request->session()->forget('cart');
                return redirect()->route('checkout.mpesa.stk.processing',['checkoutRequestID'=>$checkoutRequestID]);
                
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            //return redirect()->back()->withErrors(['error' => 'failed to make transacrion']);
        }
        
    }

    #function to initiate stk push
    private function initiateStk($phone_number,$amount)
    {        
        $curl_post_data = array(
            "BusinessShortCode"=> env('MPESA_BUSINESS_SHORT_CODE'),
            "Password"=> base64_encode(env('MPESA_BUSINESS_SHORT_CODE').env('MPESA_PASSKEY').date('YmdHis')),
            "Timestamp"=> date('YmdHis'),
            "TransactionType"=> "CustomerPayBillOnline",
            "Amount"=> $amount,
            "PartyA"=> $phone_number,
            "PartyB"=>  env('MPESA_BUSINESS_SHORT_CODE'),
            "PhoneNumber"=> $phone_number,
            "CallBackURL"=>env('MPESA_URL').'/checkout/stk/process-mpesa-stk-callback',
            "AccountReference"=> "CompanyXLTD",
            "TransactionDesc"=> "Payment of X" 
          );

        $url = '/stkpush/v1/processrequest';

       return $response = $this->makeHttp($url, $curl_post_data);


    }

    /**
     * Use this function to process the STK push request callback
     * @return string
     */

    public function processStkCallback(){
        /**Set timezone To Kenyan timezone */
        date_default_timezone_set('Africa/Nairobi');
        
        /**Get raw Response */
        $mpesaResponse = file_get_contents('php://input');

        /**Json decode raw Mpesa callback response */
        $jsonMpesaResponse=json_decode($mpesaResponse);

        $resultCode=$jsonMpesaResponse->Body->stkCallback->ResultCode;
        $checkoutRequestID=$jsonMpesaResponse->Body->stkCallback->CheckoutRequestID;

        //[HTTP_CF_CONNECTING_IP]=>196.201.214.208
        $clientIP=$_SERVER['REMOTE_ADDR'];
        $mpesaIpAddress=MpesaIpAddress::where('ip_address',$clientIP);


        if(!empty($mpesaIpAddress) && $resultCode==0){
            #Process Mpesa Transaction 
            Storage::disk('local')->put('stk.txt',   $mpesaResponse);
            $this->insertSuccessfulMpesaStkPayment($jsonMpesaResponse);
        } 

        if(!empty($mpesaIpAddress) && $resultCode!=0){
            #insert failed stk request 
            Storage::disk('local')->put('stk.txt',$mpesaResponse);
            $this->insertFailedMpesaStkPayment($jsonMpesaResponse);
        } 
        
        if(empty($mpesaIpAddress)){
            Storage::disk('local')->put('fake-stk.txt',$mpesaResponse);
        }
        
    }

    //public function verifyPayment(Request $request){
    public function confirmPayment(Request $request){
        try{
            $validated=$request->validate(['checkoutRequestID'=>'required']);
            
            $mpesaStkPayment=MpesaStkPayment::where('checkoutRequestID',$validated['checkoutRequestID'])->first();

            if($mpesaStkPayment){

                DB::beginTransaction();
                #store in database
                    $order=Order::where('checkout_id',$validated['checkoutRequestID'])->first();
                    $this->updateOrderStatus(OrderStatus::Paid);
                DB::commit();

                return array('checkoutRequestID'=>$validated['checkoutRequestID'],'resultCode'=>$mpesaStkPayment->resultCode);
            }

        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }

    #load success page
    public function success(){
        return Inertia::render('Checkout/Mpesa/Stk/Success');
    }

    #load failurepage
    public function failture(){
        return Inertia::render('Checkout/Mpesa/Stk/Failure');
    }

    #retrun processing page
    public function processing($checkoutRequestID){
        return Inertia::render('Checkout/Mpesa/Stk/Processing',['checkoutRequestID'=>$checkoutRequestID]);
    }

    #store successful stk transaction into database
    private function insertSuccessfulMpesaStkPayment($jsonMpesaResponse){
        $ResultCode=$jsonMpesaResponse->Body->stkCallback->ResultCode;
        $ResultDesc=$jsonMpesaResponse->Body->stkCallback->ResultDesc;
        $CheckoutRequestID=$jsonMpesaResponse->Body->stkCallback->CheckoutRequestID;
        $Amount=$jsonMpesaResponse->Body->stkCallback->CallbackMetadata->Item[0]->Value;
        $mpesaReceiptNumber=$jsonMpesaResponse->Body->stkCallback->CallbackMetadata->Item[1]->Value;
        $balance=$jsonMpesaResponse->Body->stkCallback->CallbackMetadata->Item[2]->Value;
        $transactionDate=$jsonMpesaResponse->Body->stkCallback->CallbackMetadata->Item[3]->Value;

        #store in database
        $mpesaStkPayment=MpesaStkPayment::where('checkoutRequestID',$checkoutRequestID)->first();

        #check if transaction exist in database
        if($mpesaStkPayment){
            $payment=["ResultDesc"=>$ResultDesc,"ResultCode"=>$resultCode,'Status'=>MpesaStkPaymentStatus::Success,"MpesaReceiptNumber"=>$MpesaReceiptNumber, "Balance"=>$Balance, "TransactionDate"=>$TransactionDate];
            $mpesaStkPayment->update($payment);
        }
        
    }

    private function insertFailedMpesaStkPayment($jsonMpesaResponse){
        $checkoutRequestID=$jsonMpesaResponse->Body->stkCallback->checkoutRequestID;
        #store in database
        $mpesaStkPayment=MpesaStkPayment::where('CheckoutRequestID',$checkoutRequestID)->first();

        if($mpesaStkPayment){
            $payment=["resultDesc"=>$resultDesc,"resultCode"=>$resultCode,'status'=>MpesaStkPaymentStatus::Failed];
            $mpesaStkPayment->update($payment);
        }
        
    }

    public function index(){
        #$permissions = Permission::search('name',$this->search_keyword)->latest()->paginate(10);
 
         $mpesaStkPayments = QueryBuilder::for(MpesaStkPayment::class)
         ->defaultSort('id')
        ->allowedSorts(['id','merchantRequestID','checkoutRequestID','responseDescription','responseCode','customerMessage','status','resultCode','resultDesc','mpesaReceiptNumber','balance','transactionDate','phoneNumber'])
         ->allowedFilters(['id','merchantRequestID','checkoutRequestID','mpesaReceiptNumber'])
         ->paginate(10)
         ->withQueryString();
         


         return Inertia::render('Mpesa/StkPayments/Index', [
             'mpesaStkPayments' => $mpesaStkPayments
         ])->table(function(InertiaTable $table){
             $table
             ->defaultSort('id')
             ->column(key: 'merchantRequestID', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'checkoutRequestID', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'responseDescription', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'responseCode', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'customerMessage', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'status', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'resultCode', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'resultDesc', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'mpesaReceiptNumber', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'balance', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'transactionDate', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'phoneNumber', searchable: true, sortable: true, canBeHidden: false)
             ;
         }); 
     }

}