<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Refund;
use App\Models\PendingRefund;
use Illuminate\Support\Facades\DB;
class RefundController extends Controller
{
    public function intiate(Request $request){
        $selected_order_id = session()->get('selected_order_id');

        $datas = $request->validate([
            'ids' => ['array'],
        ]);

        foreach($datas as $data){
            for($i=0; $i<count($data); $i++){
                Refund::create([
                    'order_id' => $selected_order_id, 
                    'ticket_id' => $data[$i],
                    'refund_initiator_id' => Auth::user()->id,
                    'refund_initiated_at' => date("Y-m-d h:i:s")
                ]);
            }
        }
        
       //return to_route('orders.refund',$selected_order_id);

        return Inertia::location(route('orders.refund',$selected_order_id));

    }

    public function orders(){

        $refunds = Refund::select('order_id','refund_initiator_id')
        ->groupBy('order_id','refund_initiator_id')
        ->get();
        
        return Inertia::render('Refunds/Orders', [
            'refunds' => $refunds
        ]);
    }

    public function show($order_id,$refund_initiator_id){

        $refunds = DB::table('id','order_id','ticket_id','status','refund_initiator_id','refund_approver_id','refund_initiated_at','refund_declined_at','refund_approved_at','refund_at')
        ->where(['order_id'=>$order_id,'refund_initiator_id'=>$refund_initiator_id])
        ->get();

        
        
        return Inertia::render('Refunds/Index', [
            'refunds' => $refunds
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        Refund::destroy([$id]);
        return redirect()->route('refunds.index');
    }

    public function destroyRefundOrder($order_id)
    {
        $refund = Refund::where('order_id',$order_id)->delete();
        return to_route('refunds.orders');
    }

    public function approval($order_id,$refund_initiator_id){


        $refunds = PendingRefund::select('id','order_id','ticket_id','refund_initiator_id','unit_price')
            ->where('order_id',$order_id)
            ->where('refund_initiator_id',$refund_initiator_id)
            ->get();
        
        
        return Inertia::render('Refunds/Approval', [
            'refunds' => $refunds
        ]);
    }

    // public function approve(){
    //     $data = $request->validate([
    //         'ids' => ['array'],
    //         'order_id' => 'required'
    //     ]);
        
    //     $refund_initiator_ids=Refund::select('refund_initiator_id')->whereIn($data->ids);

    //     $current_user=User::find(Auth::user()->id);
    //     if( $current_user->hasRole('admin') && !in_array($current_user->id,$refund_initiator_ids)){
    //         //abort(403);
    //         $tickets=Refund::whereIn('id',$data->ids)->update([
    //             'refund_approver_id' => $current_user,
    //             'refund_approved_at' => 
    //         ]);
    //     }

    //     if( $current_user->hasRole('admin') && in_array($current_user->id,$refund_initiator_ids)){
    //         //abort(403);
    //         echo 'you cannot iniate a refund and approve a refund';
    //     }

    //     if( !$current_user->hasRole('admin'))){
    //         abort(403);
    //     }
    // }
    // public function decline(){

    // }
}
