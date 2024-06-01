<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class feController extends Controller
{
    public function index()
    {
        return view('pelanggan.index');
    }
    public function showlogin() {
        // return auth()->guard('customer');
        return view('pelanggan.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('customer')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function logout(Request $request) {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function register() {
        return view('pelanggan.register');
    }
    public function shop() {
        $perPage = 9;
        $products = Product::paginate($perPage);
        return view('pelanggan.shop', compact('products'));
    }
    public function detail($id) {
        $product = Product::findOrFail($id);
        return view('pelanggan.detail', compact('product'));
    }

    public function cart() {
        return view('pelanggan.cart');
    }

    public function checkout() {
        return view('pelanggan.checkout');
    }

    public function contact() {
        return view('pelanggan.contact');
    }

    public function profile() {
        return view('pelanggan.profile');
    }

    public function editprofile() {
        return view('pelanggan.editprofile');
    }
}
