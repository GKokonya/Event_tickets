<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketMail;
use App\Models\Viewticket;
use PDF;
class TicketController extends Controller
{
    //

    
    public function  viewPdf($id){
        $ticket=\DB::table('view_tickets')->where('id',$id)->first();

       /* $pdf = PDF::loadView('ticket', ['ticket'=>$ticket])
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);
        return $pdf->download('ticket.pdf');*/

        return view('ticket',['ticket'=>$ticket]);


    }

    public function  generatePdf($id){
        $ticket=\DB::table('view_tickets')->where('id',$id)->first();

        $path=Storage::url($ticket->image);
        $type=pathinfo($_SERVER['HTTP_HOST'].$path,PATHINFO_EXTENSION);
        $data=file_get_contents($path);
        $image='data:image/'.$type.';base64,'.base64_encode($data);

        $data=['ticket'=>$ticket,'image'=>$image];
        $pdf = Pdf::loadView('ticket', $data)->setPaper('a4', 'potrait');
        return $pdf->download();


    }

    public function show($orderID){
        //$ticket=\DB::table('view_tickets')->where('id',$id)->first();
        $tickets=DB::table('view_tickets')->where('order_id',$orderID)->get();
        //$ticket=DB::table('view_tickets')->where('order_id',$orderID)->first();
       // return view('emails.ticket',compact('tickets'));
        return $ticket;
    }

    public function send(){
        // Ship the order...
        $data=[];

        try{

            Mail::to('gbkoks196@gmail.com')->send(new TicketMail(3));
            return 'email sent successfully';
        }catch(\Exception $e){
            return  $e->getMessage();
        }

    }

}
