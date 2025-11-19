@extends('user.layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-xl font-bold mb-4">Mã giảm giá của bạn</h2>

    @if($userCoupons->isEmpty())
        <p>Hiện tại bạn chưa có mã giảm giá nào.</p>
    @else
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Mã</th>
                    <th class="p-2 border">Giảm giá</th>
                    <th class="p-2 border">Tối thiểu</th>
                    <th class="p-2 border">Ngày hiệu lực</th>
                    <th class="p-2 border">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userCoupons as $uc)
                <tr>
                    <td class="p-2 border">{{ $uc->coupon->code }}</td>
                    <td class="p-2 border">
                        @if($uc->coupon->discount_type=='percent') 
                            {{ $uc->coupon->discount_value }} %
                        @else
                            {{ number_format($uc->coupon->discount_value) }} đ
                        @endif
                    </td>
                    <td class="p-2 border">{{ number_format($uc->coupon->min_order_value) }} đ</td>
                    <td class="p-2 border">
                        {{ \Carbon\Carbon::parse($uc->coupon->start_date)->format('d/m/Y') }} -
                        {{ \Carbon\Carbon::parse($uc->coupon->end_date)->format('d/m/Y') }}
                    </td>
                    <td class="p-2 border">{{ $uc->coupon->isValid() ? 'Hiệu lực' : 'Hết hạn' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
