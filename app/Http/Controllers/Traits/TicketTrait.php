<?php
namespace App\Http\Controllers\Traits;

use Mail;
use App\Models\OrderDetail;
use App\Models\Ticket;
use App\Mail\TicketMail;
use Illuminate\Support\Facades\DB;

trait TicketTrait {

    #create ticket in database
    private function generateTicket($order_id){
        $orderDetails=OrderDetail::where('order_id',$order_id)->get(['id','quantity']);
        $order_items_ids=array_column($orderDetails->toArray(),'id');
        
        if(count($orderDetails)>0){
            try{
                $tickets=Ticket::whereIn('order_item_id',$order_items_ids)->get();
                if(count($tickets)==0){
                    DB::beginTransaction();
                    foreach($orderDetails as $orderDetail){
                        for($i=0; $i<$orderDetail['quantity']; $i++){
                        Ticket::create(['order_item_id'=>$orderDetail['id'] ]);
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

    public function generateTicketandSendEmail($order_id){

        /*
        $pdf = PDF::loadView('emails.ticket', $data)->setPaper('a4', 'landscape')->setWarnings(false);

        try{
            Mail::send('emails.body', $data, function($message)use($data,$pdf) {
            $message->to($data["email"], $data["email"])
            ->subject($data["subject"])
            ->attachData($pdf->output(), "invoice.pdf");
            });

            return 'email sent successfully';
        }catch(\Exception $e){
            return  $e->getMessage();
        }

        return response()->json(compact('this'));
        */
        try{

            Mail::to('gbkoks196@gmail.com')->send(new TicketMail($order_id));
            return 'email sent successfully';
        }catch(\Exception $e){
            return  $e->getMessage();
        }
    }


    


}