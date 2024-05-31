<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class feController extends Controller
{
    public function index()
    {
        return view('pelanggan.index');
    }

    public function shop() {
        return view('pelanggan.shop');
    }
    public function detail() {
        return view('pelanggan.detail');
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

    public function login() {
        return view('pelanggan.login');
    }

    public function register() {
        return view('pelanggan.register');
    }

    public function profile() {
        return view('pelanggan.profile');
    }

    public function editprofile() {
        return view('pelanggan.editprofile');
    }
}
