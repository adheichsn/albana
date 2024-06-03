@extends('komponen.index')

@section('content')
 <!-- Page Header Start -->
<div class="container-fluid bg-secondary mb-5">
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
        <h1 class="font-weight-semi-bold text-uppercase mb-3">Checkout</h1>
        <div class="d-inline-flex">
            <p class="m-0"><a href="/">Home</a></p>
            <p class="m-0 px-2">-</p>
            <p class="m-0">Checkout</p>
        </div>
    </div>
</div>
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
                        <input class="form-control" type="text" placeholder="example@email.com" value="{{ $customer->email }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Mobile No</label>
                        <input class="form-control" type="text" placeholder="+62 813 123" value="0{{ $customer->phone }}">
                    </div>
                    <div class="col-md-12 form-group">
                        <label>Full Address</label>
                        <input class="form-control" type="text" placeholder="123 Street" value="{{ $customer->address }}">
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
                    @foreach($cartItems as $cartItem)
                    <div class="d-flex justify-content-between">
                        <p>{{ $cartItem->product->name }}</p>
                        <p>{{ "Rp." .number_format($cartItem->product->price, 2, ",", ".") }}</p>
                    </div>
                    @endforeach
                    <hr class="mt-0">
                    <div class="d-flex justify-content-between mb-3 pt-1">
                        <h6 class="font-weight-medium">Subtotal</h6>
                        <h6 class="font-weight-medium">{{ "Rp." .number_format($subtotal, 2, ",", ".") }}</h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6 class="font-weight-medium">Shipping</h6>
                        <h6 class="font-weight-medium">Rp.10.000</h6>
                    </div>
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <div class="d-flex justify-content-between mt-2">
                        <h5 class="font-weight-bold">Total</h5>
                        <h5 class="font-weight-bold">{{ "Rp." .number_format($total, 2, ",", ".") }}</h5>
                    </div>
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3">Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Checkout End -->

<!-- Script untuk memicu pembayaran menggunakan Snap Midtrans -->
@if(isset($snapToken))
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-iJt5elw9AGa2XL5h"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log('Transaction succeeded:', result);
                    // Redirect to success page or do something else
                    window.location.href = "{{ route('index') }}";
                },
                onPending: function(result) {
                    console.log('Transaction pending:', result);
                    // Redirect to pending page or do something else
                },
                onError: function(result) {
                    console.log('Transaction failed:', result);
                    // Redirect to error page or do something else
                }
            });
        });
    </script>

        // function updateOrderStatus(orderId, status) {
        //     $.ajax({
        //         url: '/update-order-status',
        //         type: 'POST',
        //         data: {
        //             '_token': '{{ csrf_token() }}',
        //             'orderId': orderId,
        //             'status': status
        //         },
        //         success: function(response) {
        //             console.log(response);
        //             // Redirect to success page or do something else
        //         },
        //         error: function(xhr, status, error) {
        //             console.error(xhr.responseText);
        //             // Redirect to error page or do something else
        //         }
        //     });
        // }
@endif

@endsection
