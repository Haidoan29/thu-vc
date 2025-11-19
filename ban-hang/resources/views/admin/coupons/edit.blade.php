@extends('layouts.app')
@section('title', 'Chỉnh sửa mã giảm giá')
@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h2 class="text-xl font-bold mb-4">Chỉnh sửa Mã Giảm Giá</h2>

    @if ($errors->any())
        <div class="bg-red-200 p-2 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.coupons.update', $coupon->_id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block mb-1">Mã Coupon</label>
            <input type="text" name="code" class="w-full border p-2 rounded" value="{{ old('code', $coupon->code) }}">
        </div>
        <div>
            <label class="block mb-1">Loại giảm giá</label>
            <select name="discount_type" class="w-full border p-2 rounded">
                <option value="percent" {{ old('discount_type', $coupon->discount_type)=='percent' ? 'selected' : '' }}>Phần trăm</option>
                <option value="fixed" {{ old('discount_type', $coupon->discount_type)=='fixed' ? 'selected' : '' }}>Số tiền cố định</option>
            </select>
        </div>
        <div>
            <label class="block mb-1">Giá trị giảm</label>
            <input type="number" name="discount_value" class="w-full border p-2 rounded" value="{{ old('discount_value', $coupon->discount_value) }}">
        </div>
        <div>
            <label class="block mb-1">Giá trị tối thiểu đơn hàng</label>
            <input type="number" name="min_order_value" class="w-full border p-2 rounded" value="{{ old('min_order_value', $coupon->min_order_value) }}">
        </div>
        <div>
            <label class="block mb-1">Giới hạn số lần sử dụng</label>
            <input type="number" name="usage_limit" class="w-full border p-2 rounded" value="{{ old('usage_limit', $coupon->usage_limit) }}">
        </div>
        <div>
            <label class="block mb-1">Ngày bắt đầu</label>
            <input type="date" name="start_date" class="w-full border p-2 rounded" value="{{ old('start_date', \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d')) }}">
        </div>
        <div>
            <label class="block mb-1">Ngày kết thúc</label>
            <input type="date" name="end_date" class="w-full border p-2 rounded" value="{{ old('end_date', \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d')) }}">
        </div>
        <div>
            <label class="block mb-1">Trạng thái</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="active" {{ old('status', $coupon->status)=='active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $coupon->status)=='inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Cập nhật Coupon</button>
    </form>
</div>
@endsection
