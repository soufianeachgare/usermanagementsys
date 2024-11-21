@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Order Details</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Order #{{ $order->id }}</h5>
                <p class="card-text"><strong>Customer:</strong> {{ $order->user->name }}</p>
                <p class="card-text"><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                <p class="card-text"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                <p class="card-text"><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
            </div>
        </div>

        <h2>Order Items</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                        <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
    </div>
@endsection
