@extends('user.layouts.app')
@section('title','Coupon khả dụng')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Các mã giảm giá có thể nhận</h2>

    @if(session('success'))
    <div class="bg-green-200 p-2 mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-200 p-2 mb-4">{{ session('error') }}</div>
    @endif

    @forelse($coupons as $coupon)
    <div class="flex items-center justify-between p-4 border rounded mb-3">
        <div>
            <div class="text-lg font-semibold">{{ $coupon->code }}</div>
            <div class="text-sm text-gray-600">
                @if($coupon->discount_type=='percent')
                Giảm {{ $coupon->discount_value }}%
                @else
                Giảm {{ number_format($coupon->discount_value) }}đ
                @endif
                • Tối thiểu {{ number_format($coupon->min_order_value ?? 0) }}đ
            </div>
            <div class="text-xs text-gray-500 mt-1">
                Hiệu lực: {{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}
                - {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}
            </div>
        </div>

        <form action="{{ route('user.coupons.claim', $coupon->_id) }}" method="POST">
            @csrf
            @if(in_array($coupon->_id, $claimed))
            <button class="bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed">
                Đã nhận
            </button>
            @else
            <form action="{{ route('user.coupons.claim', $coupon->_id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">
                    Nhận mã
                </button>
            </form>
            @endif

        </form>
    </div>
    @empty
    <p>Hiện không có mã giảm giá khả dụng.</p>
    @endforelse
</div>
@endsection