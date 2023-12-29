<?php
namespace App\Http\Controllers\Traits;

use Mail;
use App\Mail\TicketMail;

trait EmailTicketTrait {

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