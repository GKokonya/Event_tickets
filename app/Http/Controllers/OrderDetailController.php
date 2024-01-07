<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrderDetailController extends Controller
{
    //
    public function index($order_id){
        $query = OrderDetail::where('order_id', $order_id);

        $order_details = QueryBuilder::for($query)
        ->defaultSort('id')
        ->allowedSorts(['id','order_id','event_ticket_type_id','quantity','unit_price','total_price'])
        ->allowedFilters(['id','order_id','event_ticket_type_id','quantity','unit_price','total_price'])
        ->paginate(10)
        ->withQueryString();
    
        return Inertia::render('OrderDetails/Index', [
            'order_details' => $order_details
        ])->table(function(InertiaTable $table){
            $table
            ->defaultSort('id')
            ->column(key: 'id', searchable: true, sortable: true, canBeHidden: false)
            ->column(key: 'order_id', searchable: true, sortable: true, canBeHidden: false)
            ->column(key: 'event_ticket_type_id', searchable: true, sortable: true, canBeHidden: false)
            ->column(key: 'quantity', searchable: true, sortable: true, canBeHidden: false)
            ->column(key: 'unit_price', searchable: true, sortable: true, canBeHidden: false)
            ->column(key: 'total_price', searchable: true, sortable: true, canBeHidden: false)
            ;
        });  
    }
}
