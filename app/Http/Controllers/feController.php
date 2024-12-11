<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PostOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class feController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false; // Set false untuk sandbox mode, true untuk production mode
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
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
            return view('pelanggan.index');
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
    public function showRegister() {
        return view('pelanggan.register');
    }
    public function contact() {
        return view('pelanggan.contact');
    }

    public function profile() {
        // Ambil data customer yang sedang login
        $customer = Auth::guard('customer')->user();

        // Kirim data customer ke view
        return view('pelanggan.profile', compact('customer'));
    }

    public function orderHistory()
    {
        $customer = Auth::guard('customer')->user();
        $history = PostOrder::join('orders', 'postOrders.order_id', '=', 'orders.id')
            ->where('orders.customer_id', $customer->id)
            ->select('postOrders.*')
            ->get();
        return view('pelanggan.riwayat', compact('history'));
    }
    public function editprofile()
    {
        $customer = Auth::guard('customer')->user();
        return view('pelanggan.editprofile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        // Validasi data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
        ]);

        // Update data customer
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->phone = $request->input('phone');
        $customer->address = $request->input('address');

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile_pictures'), $filename);

            // Delete old profile picture if exists
            if ($customer->img && file_exists(public_path('uploads/profile_pictures/' . $customer->img))) {
                unlink(public_path('uploads/profile_pictures/' . $customer->img));
            }

            $customer->img = $filename;
        }

        $customer->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
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
    public function checkout(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $cartItems = $customer->cartItems()->with('product')->get();

        // Hitung ulang subtotal
        $subtotal = 0;
        foreach ($cartItems as $cartItem) {
            $subtotal += $cartItem->product->price * $cartItem->qty;
        }
        // Hitung biaya pengiriman berdasarkan lokasi dan berat
        $shippingCost = 0;
        $location = $request->input('location');

        if (in_array($location, [
    'kelapa_dua', 'bojong_nangka', 'pakulonan_barat', 'bencongan', 'bencongan_indah', 'curug_sangereng'
])) {
    // Zona 1: Jabodetabek
    $shippingCost = 15000; // Sesuaikan tarif JNE
}

        // Hitung PPN 10%
        $ppn = $subtotal * 0.1;
        // Hitung total amount
        $totalAmount = $subtotal + $shippingCost + $ppn;

        // Kembalikan view dengan data yang diperlukan
        return view('pelanggan.checkout', compact('customer', 'cartItems', 'subtotal', 'totalAmount', 'shippingCost', 'ppn'));
    }


    public function paymentProcess(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $cartItems = $customer->cartItems()->with('product')->get();

        // Calculate subtotal
        $subtotal = 0;
        foreach ($cartItems as $cartItem) {
            $subtotal += $cartItem->product->price * $cartItem->qty;
        }

        // Calculate shipping cost based on location from the request
        $location = $request->input('location');
        $shippingCost = 0;

        if (in_array($location, [
            'kelapa_dua', 'bojong_nangka', 'pakulonan_barat', 'bencongan', 'bencongan_indah', 'curug_sangereng'
        ])) {
            $shippingCost = 15000;

        }

        // Hitung PPN 10%
        $ppn = $subtotal * 0.1;

        // Total amount to be charged (subtotal + shipping cost)
        $total = $subtotal + $shippingCost + $ppn;

        // Prepare item details array
        $itemDetails = [];

        foreach ($cartItems as $cartItem) {
            $itemDetails[] = [
                'id' => $cartItem->product->id,
                'price' => $cartItem->product->price,
                'quantity' => $cartItem->qty,
                'name' => $cartItem->product->name,
            ];
        }

        // Include shipping cost in the item details
        if ($shippingCost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => $shippingCost,
                'quantity' => 1,
                'name' => 'Shipping Cost',
            ];
        }
        if ($ppn > 0) {
            $itemDetails[] = [
                'id' => 'ppn',
                'price' => $ppn,
                'quantity' => 1,
                'name' => 'ppn',
            ];
        }

        // Transaction parameters for Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => 'TRX-' . uniqid(),
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $customer->address,
            ],
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan data order ke database
            $order = Order::create([
                'customer_id' => $customer->id,
                'code_order' => $params['transaction_details']['order_id'],
                'total_price' => $subtotal,
                'shippingcost' => $shippingCost,
                'total' => $total,
            ]);

            // Simpan setiap item dari keranjang belanja ke dalam tabel order_items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'price' => $item->product->price,
                ]);
            }

            // Update stok produk setelah order
            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);
                $product->stok -= $item->qty; // Pastikan 'stock' adalah nama kolom yang benar
                $product->save();
            }

            // Simpan data statusorder
            PostOrder::create([
                'order_id' => $order->id,
                'date' => now(),
                'status' => 'Unpaid',
            ]);

            // Hapus semua item di cart
            $customer->cartItems()->delete();

            // Update status postorder menjadi Paid
            PostOrder::where('order_id', $order->id)->update(['status' => 'Paid']);

            // Kirim snap token dan customer ke view checkout
            return view('pelanggan.checkout', compact('snapToken', 'customer', 'shippingCost', 'cartItems', 'subtotal', 'total', 'ppn'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function callback(Request $request)
    {
        $notification = new Notification();
        $orderId = $notification->order_id;
        $status = $notification->transaction_status;

        // Handle callback Midtrans
        if ($status == 'capture') {
            // Update status postorder menjadi sudah dibayar
            PostOrder::where('order_id', $orderId)->update(['status' => 'Paid']);
        } elseif ($status == 'cancel' || $status == 'deny' || $status == 'expire') {
            // Update status postorder menjadi dibatalkan
            PostOrder::where('order_id', $orderId)->update(['status' => 'Cancelled']);
        }

        // Redirect user back to the index page
        return redirect()->route('index')->with('success', 'Pembayaran berhasil!');
    }
}
