<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Storage;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $cart = session()->get('cart');
        $item_count = is_array($cart) ? count($cart) : 0 ;
        $total_price = !empty($cart) ?array_column($cart, 'total_price') : 0 ;


        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'images' =>[
                'logo'=> Storage::url('public/images/tiko_safi.jpeg')
            ],

            'cart'=>[
                'items'=> $cart,
                'item_count'=>$item_count,
                'total_price'=>empty($total_price)? 0 : array_sum($total_price),
                'currency'=> 'KES',
            ],

            'flash' => [
                'cart_success' => fn () => $request->session()->get('cart_success'),
                'cart_error' => fn () => $request->session()->get('cart_error')
            ],
        ];
    }
}
