@extends('komponen.index')

@section('content')
<!-- Page Header Start -->
<!-- Page Header End -->

<!-- Cart Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <table class="table table-striped text-center mb-0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Hapus</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @foreach($cartItems as $cartItem)
                    <tr>
                        <td class="align-middle">
                            <img src="{{ $cartItem->product->image }}" alt="" style="width: 50px;">
                            {{ $cartItem->product->name }}
                        </td>
                        <td class="align-middle">
                            {{ "Rp." . number_format($cartItem->product->price, 2, ",", ".") }}
                        </td>
                        <td class="align-middle">
                            <div class="input-group quantity mx-auto" style="width: 100px;">
                                <input type="number" class="form-control form-control-sm bg-white text-center qty-input readonly"
                                    value="{{ $cartItem->qty }}" data-price="{{ $cartItem->product->price }}" data-id="{{ $cartItem->id }}">
                            </div>
                        </td>
                        <td class="align-middle total-price">
                            {{ "Rp." . number_format($cartItem->product->price * $cartItem->qty, 2, ",", ".") }}
                        </td>
                        <td class="align-middle">
                            <form action="{{ route('removecart', ['id' => $cartItem->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-lg-4">
            <div class="card border-primary mb-5">
                <div class="card-header bg-primary border-0">
                    <h4 class="font-weight-semi-bold m-0">Ringkasan Keranjang</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3 pt-1">
                        <h6 class="font-weight-medium">Subtotal</h6>
                        <h6 class="font-weight-medium" id="subtotal">
                            {{"Rp." . number_format($subtotal, 2, ",", ".") }}
                        </h6>
                    </div>
                    
                </div>
                <div class="card-footer border-primary bg-transparent">
                    <div class="d-flex justify-content-between mt-2">
                        <h5 class="font-weight-bold">Total</h5>
                        <h5 class="font-weight-bold" id="total">
                            {{"Rp." . number_format($total, 2, ",", ".") }}
                        </h5>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn btn-block btn-success my-3 py-3">Lanjutkan ke Pembayaran</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart End -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyInputs = document.querySelectorAll('.qty-input');

    qtyInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const qty = parseInt(this.value);
            const price = parseFloat(this.dataset.price);
            const totalElement = this.closest('tr').querySelector('.total-price');
            const newTotal = qty * price;

            // Update total per item
            totalElement.textContent = "Rp." + newTotal.toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // Update subtotal and total
            updateSummary();
        });
    });

    function updateSummary() {
        let subtotal = 0;
        document.querySelectorAll('.total-price').forEach(function(totalElement) {
            const total = parseFloat(totalElement.textContent.replace(/[^0-9,-]+/g, '').replace(',', '.'));
            subtotal += total;
        });

        const shipping = 10000; // Ongkos kirim tetap
        const total = subtotal + shipping;

        document.getElementById('subtotal').textContent = "Rp." + subtotal.toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        document.getElementById('total').textContent = "Rp." + total.toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
});
</script>
@endsection
