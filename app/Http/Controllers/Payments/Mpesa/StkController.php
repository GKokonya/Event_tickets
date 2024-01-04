<?php

namespace App\Http\Controllers\Payments\Mpesa;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Http\Controllers\Traits\MpesaTrait;
use App\Http\Controllers\Traits\OrderTrait;

use App\Models\MpesaIpAddress;
use App\Models\StkPayment;
use App\Models\Order;

use App\Enums\StkPaymentStatus;
use App\Enums\OrderStatus;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Http;

use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StkController extends Controller
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
                StkPayment::create([
                    'responseDescription'=>$responseDescription,
                    'responseCode'=>$responseCode,
                    'customerMessage'=>$customerMessage,
                    "merchantRequestID"=>$merchantRequestID, 
                    "checkoutRequestID"=>$checkoutRequestID,
                    "amount"=>$cart['total_price'], 
                    "status"=>StkPaymentStatus::Requested,
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
            "BusinessShortCode"=> env('MPESA_STK_SHORTCODE'),
            "Password"=> base64_encode(env('MPESA_STK_SHORTCODE').env('MPESA_PASSKEY').date('YmdHis')),
            "Timestamp"=> date('YmdHis'),
            "TransactionType"=> "CustomerPayBillOnline",
            "Amount"=> $amount,
            "PartyA"=> $phone_number,
            "PartyB"=>  env('MPESA_STK_SHORTCODE'),
            "PhoneNumber"=> $phone_number,
            "CallBackURL"=>env('MPESA_CALLBACK_URL').'/checkout/stk/process-stk-callback',
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
            $this->insertSuccessfulStkPayment($jsonMpesaResponse);
        } 

        if(!empty($mpesaIpAddress) && $resultCode!=0){
            #insert failed stk request 
            Storage::disk('local')->put('stk.txt',$mpesaResponse);
            $this->insertFailedStkPayment($jsonMpesaResponse);
        } 
        
        if(empty($mpesaIpAddress)){
            Storage::disk('local')->put('fake-stk.txt',$mpesaResponse);
        }
        
    }

    //public function verifyPayment(Request $request){
    public function confirmPayment(Request $request){
        try{
            $validated=$request->validate(['checkoutRequestID'=>'required']);
            
            $stkPayment=StkPayment::where('checkoutRequestID',$validated['checkoutRequestID'])->first();

            if($stkPayment){

                DB::beginTransaction();
                #store in database
                    $order=Order::where('checkout_id',$validated['checkoutRequestID'])->first();
                    $this->updateOrderStatus(OrderStatus::Paid);
                DB::commit();

                return array('checkoutRequestID'=>$validated['checkoutRequestID'],'resultCode'=>$stkPayment->resultCode);
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
    private function insertSuccessfulStkPayment($jsonMpesaResponse){
        $resultCode=$jsonMpesaResponse->Body->stkCallback->ResultCode;
        $resultDesc=$jsonMpesaResponse->Body->stkCallback->ResultDesc;
        $checkoutRequestID=$jsonMpesaResponse->Body->stkCallback->CheckoutRequestID;
        $amount=$jsonMpesaResponse->Body->stkCallbackCallbackMetadata->Item[0]->Value;
        $mpesaReceiptNumber=$jsonMpesaResponse->Body->stkCallbackCallbackMetadata->Item[1]->Value;
        $balance=$jsonMpesaResponse->Body->stkCallbackCallbackMetadata->Item[2]->Value;
        $transactionDate=$jsonMpesaResponse->Body->stkCallbackCallbackMetadata->Item[3]->Value;

        #store in database
        $stkPayment=StkPayment::where('checkoutRequestID',$checkoutRequestID)->first();

        #check if transaction exist in database
        if($StkPayment){
            $payment=["resultDesc"=>$resultDesc,"resultCode"=>$resultCode,'status'=>StkPaymentStatus::Paid,"mpesaReceiptNumber"=>$mpesaReceiptNumber, "balance"=>$balance, "transactionDate"=>$transactionDate];
            $stkPayment->update($payment);
        }
        
    }

    private function insertFailedStkPayment($jsonMpesaResponse){
        $checkoutRequestID=$jsonMpesaResponse->Body->stkCallback->checkoutRequestID;
        #store in database
        $stkPayment=StkPayment::where('CheckoutRequestID',$checkoutRequestID)->first();

        if($StkPayment){
            $payment=["resultDesc"=>$resultDesc,"resultCode"=>$resultCode,'status'=>StkPaymentStatus::Failed];
            $stkPayment->update($payment);
        }
        
    }

    public function index(){
        #$permissions = Permission::search('name',$this->search_keyword)->latest()->paginate(10);
 
         $stkPayments = QueryBuilder::for(StkPayment::class)
         ->defaultSort('id')
        ->allowedSorts(['id','merchantRequestID','checkoutRequestID','responseDescription','responseCode','customerMessage','status','resultCode','resultDesc','mpesaReceiptNumber','balance','transactionDate','phoneNumber'])
         ->allowedFilters(['id','merchantRequestID','checkoutRequestID','mpesaReceiptNumber'])
         ->paginate(10)
         ->withQueryString();
         


         return Inertia::render('Mpesa/Index', [
             'stkPayments' => $stkPayments
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