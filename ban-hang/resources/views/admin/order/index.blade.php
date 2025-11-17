@extends('layouts.app')
@section('title','Danh sách đơn hàng')

@section('content')
<div class="container mt-4">
    <h2 class="text-2xl mb-4">Danh sách đơn hàng</h2>

    <div class="d-flex justify-content-between mb-3">
        <!-- Form tìm kiếm + chọn số lượng phân trang -->
        <form action="{{ route('order.index') }}" method="GET" class="d-flex align-items-center">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Tìm kiếm người đặt...">
             <button class="btn btn-outline-success" type="submit">Tìm</button>
            <select name="perPage" class="form-select ms-2" onchange="this.form.submit()">
                @foreach([5, 10, 15, 20] as $size)
                <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                    {{ $size }}/trang
                </option>
                @endforeach
            </select>
           
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Người đặt</th>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Địa chỉ giao hàng</th>
                    <th>Số điện thoại</th>
                    <th>Tổng tiền</th>
                    <th>Phương thức thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $orderIndex => $order)
                @foreach($order->items as $itemIndex => $item)
                <tr>
                    <td>{{ $orders->firstItem() + $orderIndex }}</td>
                    @if($loop->first)
                    <td rowspan="{{ count($order->items) }}">{{ $order->user->name ?? 'N/A' }}</td>
                    @endif
                    <td>{{ $item['name'] ?? 'N/A' }}</td>
                    <td>{{ $item['quantity'] ?? 1 }}</td>
                    @if($loop->first)
                    <td rowspan="{{ count($order->items) }}">{{ $order->shipping_address }}</td>
                    <td rowspan="{{ count($order->items) }}">{{ $order->phone }}</td>
                    <td rowspan="{{ count($order->items) }}">{{ number_format($order->total_amount,0,',','.') }} đ</td>
                    <td rowspan="{{ count($order->items) }}">{{ ucfirst($order->payment_method) }}</td>
                    <td rowspan="{{ count($order->items) }}">
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
                    <td rowspan="{{ count($order->items) }}">
                        @if($order->status == 'pending')
                        <form action="{{ route('orders.updateStatus', $order->_id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button class="btn btn-sm btn-success" onclick="return confirm('Xác nhận đơn hàng này?')">Xác nhận</button>
                        </form>
                        <form action="{{ route('orders.updateStatus', $order->_id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hủy đơn hàng này?')">Hủy</button>
                        </form>
                        @else
                        <span class="text-muted">Không khả dụng</span>
                        @endif
                    </td>
                    @endif
                </tr>
                @endforeach
                @empty
                <tr>
                    <td colspan="10" class="text-center">Chưa có đơn hàng nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @endsection