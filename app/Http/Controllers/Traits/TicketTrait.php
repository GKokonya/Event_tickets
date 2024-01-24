<?php
namespace App\Http\Controllers\Traits;

use App\Models\OrderDetail;
use App\Models\Ticket;
use App\Mail\TicketMail;
use Illuminate\Support\Facades\DB;

use App\Models\TicketDetail;
use Illuminate\Support\Facades\Mail;

// use SimpleSoftwareIO\QrCode\Facades\QrCode;

trait TicketTrait {

    #create ticket in database
    private function generateTicket($order_id){
        $order_details=OrderDetail::where('order_id',$order_id)->get(['id','quantity']);
        $order_items_ids=array_column($order_details->toArray(),'id');
        
        if(count($order_details)>0){
            try{
                $tickets=Ticket::whereIn('order_detail_id',$order_items_ids)->get();

                if(count($tickets)==0){
                    DB::beginTransaction();
                    foreach($order_details as $order_detail){
                        for($i=0; $i<$order_detail['quantity']; $i++){
                        Ticket::create(['order_detail_id'=>$order_detail['id'] ]);
                        }
                    }
                    DB::commit();
                }


            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => $e->getMessage()]);
            }
        }
        
    }

    public function sendEmailAndAttachTicket($order_id){
        $subject = 'Purchased ticket';
        $body = 'Ticket purchased successfully';

        $ticket_details = TicketDetail::where('order_id',$order_id)->get();
        $email='gbkoks196@gmail.com';

        Mail::to($email)->send(new TicketMail($subject,$body,$ticket_details));

    }


}