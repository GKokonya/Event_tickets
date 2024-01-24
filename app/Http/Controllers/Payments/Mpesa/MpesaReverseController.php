<?php

namespace App\Http\Controllers\Payments\Mpesa;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Http\Controllers\Traits\MpesaTrait;

use App\Models\MpesaIpAddress;
use App\Models\Order;
use App\Enums\OrderStatus;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Http;

use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MpesaReversalController extends Controller
{
    use MpesaTrait, OrderTrait;

    #function to initiate stk push
    private function reverseTransaction(Request $request)
    {    
        $validated=$request->validate([
            'transaction_id' => 'string | required',
            'transaction_amount' => 'integer | required'
        ]);

        $curl_post_data = array(
            "Initiator":env('MPESA_INITIATOR'),    
            "SecurityCredential": env('MPESA_SHORTCODE'),    
            "CommandID":"TransactionReversal",    
            "TransactionID": $validated['transaction_id'],    
            "Amount":$validated['transaction_amount'],    
            "ReceiverParty":env('MPESA_SHORTCODE'),    
            "RecieverIdentifierType":"11",    
            "ResultURL":env('MPESA_URL').'/api/reversal/process-mpesa-reversal',    
            "QueueTimeOutURL":env('MPESA_URL').'/api/reversal/process-mpesa-reversal',    
            "Remarks":"Reversal request",    
            "Occasion":"Order refund"
          );

        $url = '/reversal/v1/request';

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


        //[HTTP_CF_CONNECTING_IP]=>196.201.214.208
        $clientIP=$_SERVER['REMOTE_ADDR'];
        $mpesaIpAddress=MpesaIpAddress::where('ip_address',$clientIP);


        if(!empty($mpesaIpAddress)){
            #Process Mpesa Reversal Transaction 
            $this->processMpesaReversal($jsonMpesaResponse);
            Storage::disk('local')->put('mpesa-reversal.txt',   $mpesaResponse);
        } 
        
        if(empty($mpesaIpAddress)){
            Storage::disk('local')->put('fake-mpesa-reversal.txt',$mpesaResponse);
        }
        
    }


    #Process Mpesa Reversal Transaction 
    private function processMpesaReversal($jsonMpesaResponse){
        $ResultType=$jsonMpesaResponse->Result->ResultType;
        $ResultCode=$jsonMpesaResponse->Result->ResultCode;
        $ResultDesc=$jsonMpesaResponse->Result->ResultDesc;
        $OriginatorConversationID=$jsonMpesaResponse->Result->OriginatorConversationID;
        $ConversationID=$jsonMpesaResponse->Result->ConversationID;
        $TransactionID=$jsonMpesaResponse->Result->TransactionID;

        $DebitAccountBalance=$jsonMpesaResponse->Result->ResultParameters->ResultParamete[0]->Value;
        $Amount=$jsonMpesaResponse->Result->ResultParameters->ResultParamete[1]->Value;
        $TransCompletedTime=$jsonMpesaResponse->Result->ResultParameters->ResultParamete[2]->Value;
        $OriginalTransactionID=$jsonMpesaResponse->Result->ResultParameters->ResultParamete[3]->Value;
        $Charge=$jsonMpesaResponse->Result->ResultParameters->ResultParamete[4]->Value;
        $CreditPartyPublicName=$jsonMpesaResponse->Result->ResultParameters->ResultParamete[5]->Value;
        $DebitPartyPublicName=$jsonMpesaResponse->Result->ResultParameters->ResultParamete[6]->Value;


        $order = Order::where('mpesa_reversal_conversation_id',$ConversationID)->first();

        $mpesaReversal = MpesaReversal::where('ConversationID',$ConversationID)->first();

        if($order && empty($mpesaReversal) && $ResultCode==0){
            $this->insert($ResultType,$ResultCode,$ResultDesc,$OriginatorConversationID,$ConversationID,$TransactionID,$DebitAccountBalance,$Amount,$TransCompletedTime,$OriginalTransactionID,$Charge,$CreditPartyPublicName,$DebitPartyPublicName);
           
            if($Amount==round($order->total_price)){
                $this->updateOrderStatus($order,OrderStatus::Refunded)
            }

            if($Amount!=round($order->total_price)){
                $this->updateOrderStatus($order,OrderStatus::PartiallyRefunded)
            }
        }

        #check if transaction exist in database
        if($order &&  empty($mpesaReversal) && $ResultCode!=0){
            $this->insert($ResultType,$ResultCode,$ResultDesc,$OriginatorConversationID,$ConversationID,$TransactionID,$DebitAccountBalance="",$Amount="",$TransCompletedTime="",$OriginalTransactionID="",$Charge="",$CreditPartyPublicName="",$DebitPartyPublicName="");
        }

        if($order && $mpesaReversal && $ResultCode==0){
            $this->update($ResultType,$ResultCode,$ResultDesc,$OriginatorConversationID,$ConversationID,$TransactionID,$DebitAccountBalance,$Amount,$TransCompletedTime,$OriginalTransactionID,$Charge,$CreditPartyPublicName,$DebitPartyPublicName);
        }
        
    }

    private function insert($ResultType,$ResultCode,$ResultDesc,$OriginatorConversationID,$ConversationID,$TransactionID,$DebitAccountBalance="",$Amount="",$TransCompletedTime="",$OriginalTransactionID="",$Charge="",$CreditPartyPublicName="",$DebitPartyPublicName=""){
        #store in database
        $mpesaReversal=MpesaReversal::where('ConversationID',$ConversationID)->first();

        if(empty($mpesaReversal)){
            MpesaReversal::create([
                'ResultType' => $ResultType,
                'ResultCode' => $ResultCode,
                'ResultDesc' => $ResultDesc,
                'OriginatorConversationID' => $OriginatorConversationID,
                'ConversationID' => $ConversationID,
                'TransactionID' => $TransactionID,
                'DebitAccountBalance' => $DebitAccountBalance,
                'Amount' => $Amount,
                'TransCompletedTime' => $TransCompletedTime,
                'OriginalTransactionID' => $OriginalTransactionID,
                'Charge' => $Charge,
                'CreditPartyPublicName' => $CreditPartyPublicName,
                'DebitPartyPublicName' => $DebitPartyPublicName,
            ]);
        }

    }

    private function update($ResultType,$ResultCode,$ResultDesc,$OriginatorConversationID,$ConversationID,$TransactionID,$DebitAccountBalance,$Amount,$TransCompletedTime,$OriginalTransactionID,$Charge,$CreditPartyPublicName,$DebitPartyPublicName){
        #retrive from database
        $mpesaReversal=MpesaReversal::where('ConversationID',$ConversationID)->first();

        if($mpesaReversal){

            $reversal=[
                'ResultType' => $ResultType,
                'ResultCode' => $ResultCode,
                'ResultDesc' => $ResultDesc,
                'OriginatorConversationID' => $OriginatorConversationID,
                'TransactionID' => $TransactionID,
                'DebitAccountBalance' => $DebitAccountBalance,
                'Amount' => $Amount,
                'TransCompletedTime' => $TransCompletedTime,
                'OriginalTransactionID' => $OriginalTransactionID,
                'Charge' => $Charge,
                'CreditPartyPublicName' => $CreditPartyPublicName,
                'DebitPartyPublicName' => $DebitPartyPublicName,
                'Status' => $DebitPartyPublicName,
                'updated_at' => date('Y-m-d H:i:s');
            ];

            $mpesaReversal->update($reversal);
        }

    }
        

    public function index(){
        #$permissions = Permission::search('name',$this->search_keyword)->latest()->paginate(10);
 
        $mpesaReversals = QueryBuilder::for(StkPayment::class)
        ->defaultSort('id')
        ->allowedSorts(['id','ResultType','ResultCode','ResultDesc','OriginatorConversationID','ConversationID','TransactionID','DebitAccountBalance','Amount','TransCompletedTime','OriginalTransactionID','Charge','CreditPartyPublicName','DebitPartyPublicName'])
        ->allowedFilters(['id','ResultCode','ConversationID','TransactionID','phoneNumber','mpesaReceiptNumber'])
        ->paginate(10)
        ->withQueryString();
         
         return Inertia::render('Mpesa/Reversals/Index', [
             'mpesaReversals' => $mpesaReversals
         ])->table(function(InertiaTable $table){
             $table
             ->defaultSort('id')
             ->column(key: 'ResultType', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'ResultCode', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'ResultDesc', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'OriginatorConversationID', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'ConversationID', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'TransactionID', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'DebitAccountBalance', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'Amount', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'TransCompletedTime', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'OriginalTransactionID', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'Charge', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'CreditPartyPublicName', searchable: true, sortable: true, canBeHidden: false)
             ->column(key: 'DebitPartyPublicName', searchable: true, sortable: true, canBeHidden: false)
             ;
         }); 
     }

}