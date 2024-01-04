<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;
use ProtoneMedia\LaravelQueryBuilderInertiaJs\InertiaTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    $orders = QueryBuilder::for(Order::class)
    ->defaultSort('id')
    ->allowedSorts(['id','customer_email','total_price','status','mpesa_checkout_id','stripe_checkout_id','payment_type'])
    ->allowedFilters(['id','customer_email','total_price','status','mpesa_checkout_id','stripe_checkout_id','payment_type'])
    ->paginate(10)
    ->withQueryString();
    
    return Inertia::render('Orders/Index', [
        'orders' => $orders
    ])->table(function(InertiaTable $table){
        $table
        ->defaultSort('id')
        ->column(key: 'id', searchable: true, sortable: true, canBeHidden: false)
        ->column(key: 'customer_email', searchable: true, sortable: true, canBeHidden: false)
        ->column(key: 'total_price', searchable: true, sortable: true, canBeHidden: false)
        ->column(key: 'status', searchable: true, sortable: true, canBeHidden: false)
        ->column(key: 'mpesa_checkout_id', searchable: true, sortable: true, canBeHidden: false)
        ->column(key: 'stripe_checkout_id', searchable: true, sortable: true, canBeHidden: false)
        ->column(key: 'payment_type', searchable: true, sortable: true, canBeHidden: false)
        ;
    }); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
