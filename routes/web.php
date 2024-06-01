<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\feController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::controller(feController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::get('/shop', 'shop')->name('shop');
    Route::get('/detail/{id}', 'detail')->name('detail');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/login', 'showlogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'register')->name('register');
    Route::middleware(['auth:customer'])->group(function () {
        Route::get('/profile', 'profile')->name('profile');
        Route::get('/editprofile', 'editprofile')->name('editprofile');
        Route::get('/cart', 'cart')->name('cart');
        Route::post('/cart/add', 'addcart')->name('addcart');
        Route::delete('/cart/remove/{id}', 'removecart')->name('removecart');
        Route::get('/checkout', 'checkout')->name('checkout');
        Route::post('/checkout', 'checkout');
        Route::delete('/delete_cart', 'delete_cart')->name('delete_cart');
        Route::post('/logout', 'logout')->name('logout');
    });
});

Auth::routes([
    'login'    => false,
    'logout'   => false,
    'register' => false,
    'reset'    => false,
    'confirm'  => false,
    'verify'   => false,
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
