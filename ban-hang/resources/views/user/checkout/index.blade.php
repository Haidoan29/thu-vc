@extends('user.layouts.app')
@section('title', 'Thanh toán')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 my-6 border rounded shadow">
    <h2 class="text-xl font-bold mb-4">Thông tin đặt hàng</h2>
    <form action="/checkout" method="POST">
        @csrf
        <div class="mb-3">
            <label>Tên</label>
            <input type="text" name="name" class="w-full border px-2 py-1 rounded" required>
        </div>
        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="phone" class="w-full border px-2 py-1 rounded" required>
        </div>
        <div class="mb-3">
            <label>Địa chỉ</label>
            <input type="text" name="address" class="w-full border px-2 py-1 rounded" required>
        </div>

        <h3 class="font-semibold mt-4 mb-2">Sản phẩm đã chọn</h3>
        <div class="mb-3">
            @foreach($items as $item)
            <div class="flex justify-between border-b py-1">
                <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                <span>{{ number_format($item['price']) }}đ</span>
            </div>
            @endforeach
        </div>
        <p class="font-bold text-right mb-4">Tổng tiền: {{ number_format($totalAmount) }}đ</p>
        <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded w-full">Đặt hàng</button>
    </form>
</div>
@endsection