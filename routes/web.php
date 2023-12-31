<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CartController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Payments\StripePayments\StripeController;
use App\Http\Controllers\Payments\Mpesa\StkController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CheckoutController;

use Illuminate\Support\Facades\Storage;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


#Event
Route::get('/', [EventController::class,'index']);
Route::get('event/{event}', [EventController::class,'show'])->name('event.show');

#Cart
Route::prefix('cart')->group(function(){
    Route::get('/',function(){  return Inertia::render('Cart',[]);})->name('cart');
    Route::post('/', [CartController::class,'addToCart'])->name('cart.store');
    Route::delete('/{id}', [CartController::class,'destroy'])->name('cart.destroy');
});


Route::get('/cart1',function(){  return Inertia::render('Cart1',[]);})->name('cart1');

Route::get('/test',[CartController::class,'test'])->name('test');

#checkout routes
Route::prefix('checkout')->name('checkout')->group(function(){
    #checkout route
    Route::get('/', function(){ return Inertia::render('Checkout');});

    #Stripe
    Route::prefix('/stripe')->name('.stripe')->group(function(){
        Route::post('/',[StripeController::class,'checkout']);
        Route::get('success',[StripeController::class,'success'])->name('.success');
        Route::get('/failture',[StripeController::class,'failure'])->name('.failure');
        Route::post('/webhook',[StripeController::class,'webhook'])->name('.webhook');
    });

    #M-Pesa
    Route::prefix('/mpesa')->name('.mpesa')->group(function(){
        Route::view('/','mpesa');
        Route::get('/stk/stk',[StkController::class, 'stk'])->name('.stk.stk');
        Route::post('/stk/checkout', [StkController::class, 'checkout'])->name('.stk.checkout');
        Route::post('/stk/process-stk-callback', [StkController::class, 'processStkCallback'])->name('.stk.process-stk-callback');
        Route::get('/stk/processing/{checkoutRequestID}', [StkController::class, 'processing'])->name('.stk.processing');
        Route::post('/stk/confirm-payment', [StkController::class, 'confirmPayment'])->name('.stk.confirm-payment');
        Route::get('/stk/success', [StkController::class, 'success'])->name('.stk.success');
        Route::get('/stk/failure', [StkController::class, 'failture'])->name('.stk.failure');
    });

});


Route::get('fakeStk', [StkController::class, 'fakeStk'])->name('fakeStk');
Route::get('store', [StkController::class, 'store'])->name('store');


/**SoldTicket */
Route::get('generatePdf/{id}',[TicketController::class,'generatePdf'])->name('generatePdf');
Route::get('send',[TicketController::class,'send'])->name('send');
Route::get('show/{id}',[TicketController::class,'show'])->name('show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';


