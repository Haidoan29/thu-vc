@extends('layouts.app')
@section('title', 'Tạo mã giảm giá')
@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Tạo Mã Giảm Giá</h2>

    @if ($errors->any())
        <div class="bg-red-200 p-2 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block">Mã coupon</label>
            <input type="text" name="code" class="border p-2 w-full" value="{{ old('code') }}" required>
        </div>
        <div>
            <label class="block">Kiểu giảm giá</label>
            <select name="discount_type" class="border p-2 w-full" required>
                <option value="percent" {{ old('discount_type')=='percent'?'selected':'' }}>Phần trăm (%)</option>
                <option value="fixed" {{ old('discount_type')=='fixed'?'selected':'' }}>Tiền cố định</option>
            </select>
        </div>
        <div>
            <label class="block">Giá trị giảm</label>
            <input type="number" name="discount_value" class="border p-2 w-full" value="{{ old('discount_value') }}" required>
        </div>
        <div>
            <label class="block">Đơn tối thiểu</label>
            <input type="number" name="min_order_value" class="border p-2 w-full" value="{{ old('min_order_value') }}" required>
        </div>
        <div>
            <label class="block">Số lượng tối đa sử dụng</label>
            <input type="number" name="usage_limit" class="border p-2 w-full" value="{{ old('usage_limit',1) }}" required>
        </div>
        <div>
            <label class="block">Ngày bắt đầu</label>
            <input type="date" name="start_date" class="border p-2 w-full" value="{{ old('start_date') }}" required>
        </div>
        <div>
            <label class="block">Ngày kết thúc</label>
            <input type="date" name="end_date" class="border p-2 w-full" value="{{ old('end_date') }}" required>
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Tạo Coupon</button>
        </div>
    </form>
</div>
@endsection
