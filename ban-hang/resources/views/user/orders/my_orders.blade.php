@extends('user.layouts.app')
@section('title','Đơn hàng của tôi')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Đơn hàng của tôi</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead >
                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th>Địa chỉ giao hàng</th>
                    <th>Số điện thoại</th>
                    <th>Tổng tiền</th>
                    <th>Phương thức thanh toán</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $index => $order)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @foreach($order->items as $item)
                                <strong>{{ $item['name'] ?? 'N/A' }}</strong> x {{ $item['quantity'] ?? 1 }} <br>
                            @endforeach
                        </td>
                        <td>{{ $order->shipping_address }}</td>
                        <td>{{ $order->phone }}</td>
                        <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                        <td>{{ ucfirst($order->payment_method) }}</td>
                        <td>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning text-dark">Chờ xử lý</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-success">Xác nhận</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger">Đã hủy</span>
                            @else
                                <span class="badge bg-secondary">{{ $order->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Chưa có đơn hàng nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
