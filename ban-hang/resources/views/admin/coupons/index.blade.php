@extends('layouts.app')
@section('title', 'Mã giảm giá')
@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Danh sách Mã Giảm Giá</h2>

    @if(session('success'))
        <div class="bg-green-200 p-2 mb-4">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.coupons.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Tạo Coupon</a>

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">Mã</th>
                <th class="p-2 border">Giảm giá</th>
                <th class="p-2 border">Tối thiểu</th>
                <th class="p-2 border">Sử dụng/giới hạn</th>
                <th class="p-2 border">Ngày hiệu lực</th>
                <th class="p-2 border">Trạng thái</th>
                <th class="p-2 border">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coupons as $c)
            <tr>
                <td class="p-2 border">{{ $c->code }}</td>
                <td class="p-2 border">
                    @if($c->discount_type=='percent') 
                        {{ $c->discount_value }} %
                    @else
                        {{ number_format($c->discount_value) }} đ
                    @endif
                </td>
                <td class="p-2 border">{{ number_format($c->min_order_value) }} đ</td>
                <td class="p-2 border">{{ $c->used_count }}/{{ $c->usage_limit }}</td>
                <td class="p-2 border">
                    {{ \Carbon\Carbon::parse($c->start_date)->format('d/m/Y') }} - 
                    {{ \Carbon\Carbon::parse($c->end_date)->format('d/m/Y') }}
                </td>
                <td class="p-2 border">{{ $c->status }}</td>
                <td class="p-2 border flex gap-2">
                    <!-- Edit -->
                    <a href="{{ route('admin.coupons.edit', $c->_id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                    <!-- Delete -->
                    <form action="{{ route('admin.coupons.destroy', $c->_id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa coupon này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
