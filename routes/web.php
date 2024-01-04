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
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

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

Route::get('/database',function(){
    return Inertia::location('/adminer');
})->middleware('auth')->name('adminer');

#Event
Route::get('/', [EventController::class,'home']);
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
        Route::get('/failure',[StripeController::class,'failure'])->name('.failure');
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


Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/events', [EventController::class,'index'])->name('events.index');
    Route::get('/orders', [OrderController::class,'index'])->name('orders.index');
    Route::get('/mpesa', [StkController::class,'index'])->name('mpesa.index');
    Route::get('/stripe', [StripeController::class,'index'])->name('stripe.index');

    #roles
    Route::group(['prefix'=> 'roles',],function(){
        Route::get('/',[RoleController::class,'index'])->name('roles.index')->middleware('can:view roles');
        Route::get('/create',[RoleController::class,'create'])->name('roles.create')->middleware('can:create role');
        Route::post('/',[RoleController::class,'store'])->name('roles.store')->middleware('can:create role');
        Route::get('/{role}/edit',[RoleController::class,'edit'])->name('roles.edit')->middleware('can:edit role');
        Route::patch('/{role}',[RoleController::class,'update'])->name('roles.update')->middleware('can:edit role');
        Route::delete('/{role}',[RoleController::class,'destroy'])->name('roles.destroy')->middleware('can:delete role');
    });
        
    #permissions
    Route::group(['prefix'=> 'permissions',],function(){
        Route::get('/',[PermissionController::class,'index'])->name('permissions.index')->middleware('can:view permissions');
        Route::get('/create',[PermissionController::class,'create'])->name('permissions.create')->middleware('can:create permission');
        Route::post('/',[PermissionController::class,'store'])->name('permissions.store')->middleware('can:create permission');
        Route::get('/{permission}/edit',[PermissionController::class,'edit'])->name('permissions.edit')->middleware('can:edit permission');
        Route::patch('/{permission}',[PermissionController::class,'update'])->name('permissions.update')->middleware('can:edit permission');
        Route::delete('/{permission}',[permissionController::class,'destroy'])->name('permissions.destroy')->middleware('can:delete permission');
    });
    
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';


