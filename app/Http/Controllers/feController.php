<?php

namespace App\Http\Controllers;

use App\Models\Cart;
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
        $customer = Auth::guard('customer')->user();
        $cartItems = $customer->cartItems()->with('product')->get();

        // Hitung ulang subtotal
        $subtotal = 0;
        foreach ($cartItems as $cartItem) {
            $subtotal += $cartItem->product->price * $cartItem->qty;
        }

        // Set total sama dengan subtotal sementara karena tidak ada biaya pengiriman
        $total = $subtotal;

        return view('pelanggan.cart', compact('cartItems', 'subtotal', 'total'));
    }

    public function addcart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        $customer = Auth::guard('customer')->user();

        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity'); // Ambil nilai quantity dari request

        // Cek apakah produk sudah ada di keranjang belanja customer
        $cartItem = $customer->cartItems()->where('product_id', $product_id)->first();

        if ($cartItem) {
            // Jika produk sudah ada, tambahkan jumlahnya
            $cartItem->update([
                'qty' => $cartItem->qty + $quantity, // Perbarui kolom 'qty' sesuai nilai quantity baru
                'total_price' => $cartItem->product->price * ($cartItem->qty + $quantity)
            ]);
        } else {
            // Jika produk belum ada, tambahkan produk ke keranjang belanja
            $product = Product::findOrFail($product_id);
            $total_price = $product->price * $quantity;
            $customer->cartItems()->create([
                'product_id' => $product_id,
                'qty' => $quantity,
                'total_price' => $total_price
            ]);
        }

        return redirect()->route('cart')->with('success', 'Product added to cart successfully!');
    }
    public function removecart(Request $request, $id)
    {
        $customer = Auth::guard('customer')->user();

        // Cari item keranjang berdasarkan ID
        $cartItem = Cart::findOrFail($id);

        // Pastikan bahwa item keranjang dimiliki oleh customer yang sedang login
        if ($cartItem->customer_id === $customer->id) {
            // Hapus item keranjang
            $cartItem->delete();

            return redirect()->route('cart')->with('success', 'Item removed from cart successfully!');
        } else {
            // Jika item keranjang tidak dimiliki oleh customer yang sedang login, beri respons yang sesuai
            return redirect()->route('cart')->with('error', 'You are not authorized to remove this item from cart.');
        }
    }
    public function checkout(Request $request) {
        $customer = Auth::guard('customer')->user();
        $cartItems = $customer->cartItems()->with('product')->get();

        // Hitung ulang subtotal
        $subtotal = 0;
        foreach ($cartItems as $cartItem) {
            $subtotal += $cartItem->product->price * $cartItem->qty;
        }

        // Set total sama dengan subtotal sementara karena tidak ada biaya pengiriman
        $total = $subtotal+10000;

        return view('pelanggan.checkout', compact('customer', 'cartItems', 'subtotal', 'total'));
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
