@extends('komponen.index')

@section('content')
    <!-- Page Header Start -->
    {{-- <div class="container-fluid bg-secondary mb-5">
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
        <h1 class="font-weight-semi-bold text-uppercase mb-3">Checkout</h1>
        <div class="d-inline-flex">
            <p class="m-0"><a href="/">Home</a></p>
            <p class="m-0 px-2">-</p>
            <p class="m-0">Checkout</p>
        </div>
    </div>
</div> --}}
    <!-- Page Header End -->


    <!-- Checkout Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <div class="mb-4">
                    <h4 class="font-weight-semi-bold mb-4">Billing Address</h4>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Full Name</label>
                            <input class="form-control" type="text" placeholder="John" value="{{ $customer->name }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>E-mail</label>
                            <input class="form-control" type="text" placeholder="example@email.com"
                                value="{{ $customer->email }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mobile No</label>
                            <input class="form-control" type="text" placeholder="+62 813 123"
                                value="0{{ $customer->phone }}">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Full Address</label>
                            <input class="form-control" type="text" placeholder="123 Street"
                                value="{{ $customer->address }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Country</label>
                            <input class="form-control" type="text" value="Indonesia">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>ZIP Code</label>
                            <input class="form-control" type="text" placeholder="123">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Order Total</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="font-weight-medium mb-3">Products</h5>
                        @foreach ($cartItems as $cartItem)
                            <div class="d-flex justify-content-between">
                                <p>{{ $cartItem->product->name }}</p>
                                <p>{{ 'Rp.' . number_format($cartItem->product->price, 2, ',', '.') }}</p>
                            </div>
                        @endforeach
                        <hr class="mt-0">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Subtotal</h6>
                            <h6 class="font-weight-medium">{{ 'Rp.' . number_format($subtotal, 2, ',', '.') }}</h6>
                        </div>
                        <div class="form-group">
                            <label class="stext-110 cl2">Select Location</label>
                            <select class="form-control" name="location" id="location" required>
                                <!-- Dynamic options should be loaded here -->
                            </select>
                            <button type="button" id="calculate-shipping" class="btn btn-secondary mt-2">Calculate Shipping</button>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Shipping</h6>
                            <h6 class="font-weight-medium" id="shipping-cost">
                                {{ 'Rp.' . number_format($shippingCost, 2, ',', '.') }}</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">PPN 10%</h6>
                            <h6 class="font-weight-medium" id="ppn-amount">{{ 'Rp.' . number_format($ppn, 2, ',', '.') }}</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total</h5>
                            <h5 class="font-weight-bold" id="total-amount">
                                {{ 'Rp.' . number_format($subtotal + $ppn + $shippingCost, 2, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="location" id="hidden-location" value="{{ old('location') }}">
                            <button type="submit" class="btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3">Checkout</button>
                        </form>
                    </div>
                </div>
            </div>

    <!-- Checkout End -->
    <script>
        // Script untuk dropdown lokasi dan perhitungan biaya pengiriman
        const cities = [
    "Bojong Nangka",
    "Kelapa Dua",
    "Pakulonan Barat",
    "Bencongan",
    "Bencongan Indah",
    "Curug Sangereng"
];

const locationSelect = document.getElementById('location');
cities.forEach(city => {
    const option = document.createElement('option');
    option.value = city.toLowerCase().replace(/\s+/g, '_');
    option.textContent = city;
    locationSelect.appendChild(option);
});

document.getElementById('calculate-shipping').addEventListener('click', function() {
    const location = document.getElementById('location').value;

    let shippingCost = 0;

    // Hitung biaya pengiriman berdasarkan lokasi
    if (['kelapa_dua', 'bojong_nangka', 'pakulonan_barat', 'bencongan', 'bencongan_indah', 'curug_sangereng'].includes(location)) {
        shippingCost = 15000; // Sesuaikan tarif JNE
    }

    // Tampilkan biaya pengiriman
    document.getElementById('shipping-cost').textContent = `Biaya pengiriman: Rp ${shippingCost}`;



            // Hitung total amount
            const subtotal = {{ $subtotal }};
            const vat = subtotal * 0.1;
            const total = subtotal + vat + shippingCost;
            document.getElementById('total-amount').textContent = "Rp." + total.toLocaleString("id-ID");

            // Set nilai input tersembunyi untuk checkout
            document.getElementById('hidden-location').value = location;
        });

        document.getElementById('checkout-button').addEventListener('click', function() {
            const form = document.getElementById('payment-form');
            form.submit();
        });
    </script>

    @if (isset($snapToken))
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-iJt5elw9AGa2XL5h"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function(event) {
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        console.log('Transaction succeeded:', result);
                        window.location.href = "{{ route('index') }}";
                    },
                    onPending: function(result) {
                        console.log('Transaction pending:', result);
                    },
                    onError: function(result) {
                        console.log('Transaction failed:', result);
                    }
                });
            });
        </script>


        <style>
            .flex-row {
                display: flex;
                flex-wrap: wrap;
            }

            .d-flex {
                display: flex;
            }

            .spacer {
                height: 200px;
            }

            .flex-start {
                justify-content: flex-start;
            }
        </style>
    @endif
@endsection
