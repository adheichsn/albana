@extends('komponen.index')

@section('content')

<body>
    <div class="mytabs">
        <div class="tab">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>No Resi</th>
                        <th>Total Price</th>
                        <th>Status Paket</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $order)
                    <tr>
                        <td>{{ $order->order->code_order }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>
                            @if(!$order->resi)
                                Resi Belum Muncul
                            @else
                            {{ $order->resi }}
                            @endif
                        </td>
                        <td>{{ "Rp." .number_format($order->order->total_price, 2, ",", ".") }}</td>
                        <td>
                            @if(!$order->status_paket)
                                <span class="badge badge-danger">Belum Dikirim</span>
                            @else
                                <span class="badge badge-success">{{ $order->status_paket }}</span>
                            @endif
                        </td>                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>

<style>
    h2 {
        padding: 10px;
    }

    .mytabs {
        display: flex;
        flex-wrap: wrap;
        max-width: 1400px;
        margin: 50px auto;
        padding: 10px;
    }

    .mytabs label {
        padding: 9px;
        color: #fff;
        background: #ff007f;
        font-weight: bold;
    }

    .mytabs .tab {
        width: 100%;
        padding: 4px;
        background: #fff;
        order: 1;
    }

</style>
@endsection
