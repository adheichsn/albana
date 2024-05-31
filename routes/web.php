<?php

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
Route::controller(App\Http\Controllers\feController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::get('/shop', 'shop')->name('shop');
    Route::get('/detail', 'detail')->name('detail');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/login', 'login')->name('login');
    Route::get('/register', 'register')->name('register');
    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', 'profile')->name('profile');
        Route::get('/editprofile', 'editprofile')->name('editprofile');
        Route::get('/cart', 'cart')->name('cart');
        Route::get('/checkout', 'checkout')->name('checkout');
        Route::post('/checkout', 'checkout')->name('checkout');
        Route::delete('/delete_cart', 'delete_cart')->name('delete_cart');
        Route::post('logout', 'logout')->name('logout');
    });
});
