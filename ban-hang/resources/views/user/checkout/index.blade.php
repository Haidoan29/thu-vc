@extends('user.layouts.app')
@section('title', 'Thanh toán')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 my-6 border rounded shadow">
    {{-- Hiển thị thông báo --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif
    <h2 class="text-xl font-bold mb-4">Thông tin đặt hàng</h2>

    <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Tên</label>
            <input type="text" name="name" class="w-full border px-2 py-1 rounded" required>
        </div>

        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="phone" class="w-full border px-2 py-1 rounded" required>
        </div>

        {{-- Địa chỉ --}}
        <div class="mb-3">
            <label>Tỉnh/Thành phố</label>
            <select id="province" class="w-full border px-2 py-1 rounded" name="province" required></select>
        </div>

        <div class="mb-3">
            <label>Quận/Huyện</label>
            <select id="district" class="w-full border px-2 py-1 rounded" name="district" required></select>
        </div>

        <div class="mb-3">
            <label>Phường/Xã</label>
            <select id="ward" class="w-full border px-2 py-1 rounded" name="ward" required></select>
        </div>

        <div class="mb-3">
            <label>Số nhà, tên đường</label>
            <input type="text" id="street" name="street" class="w-full border px-2 py-1 rounded" required>
        </div>

        <input type="hidden" name="address" id="fullAddress">

        {{-- Mã giảm giá --}}
        <div class="mb-3">
            <label class="font-semibold">Áp dụng mã giảm giá</label>
            <select name="coupon_id" id="couponSelect" class="w-full border px-2 py-1 rounded">
                <option value="">-- Không dùng mã --</option>
                @foreach($userCoupons as $uc)
                <option
                    value="{{ $uc->_id }}"
                    data-type="{{ $uc->coupon->discount_type }}"
                    data-value="{{ $uc->coupon->discount_value }}"
                    data-min="{{ $uc->coupon->min_order_value }}">
                    {{ $uc->coupon->code }} -
                    @if($uc->coupon->discount_type === 'percent')
                    Giảm {{ $uc->coupon->discount_value }}%
                    @else
                    Giảm {{ number_format($uc->coupon->discount_value) }}đ
                    @endif
                    (ĐH tối thiểu {{ number_format($uc->coupon->min_order_value) }}đ)
                </option>
                @endforeach
            </select>

            {{-- Link xem thêm mã giảm giá --}}
            <p class="mt-2 text-sm text-blue-600 hover:underline cursor-pointer">
                <a href="{{ route('user.coupons.available') }}" target="_blank">
                    Xem thêm mã giảm giá khả dụng
                </a>
            </p>
        </div>


        {{-- Sản phẩm --}}
        <h3 class="font-semibold mt-4 mb-2">Sản phẩm đã chọn</h3>
        <div class="mb-3">
            @foreach($items as $item)
            <div class="flex justify-between border-b py-1">
                <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                <span>{{ number_format($item['price']) }}đ</span>
            </div>
            @endforeach
        </div>

        {{-- Tổng tiền --}}
        <p class="font-bold text-right mb-1">
            Tổng tiền:
            <span id="originalTotal" data-total="{{ $totalAmount }}">
                {{ number_format($totalAmount) }}đ
            </span>
        </p>

        {{-- Dòng giảm giá --}}
        <p class="text-right text-green-600 hidden" id="discountRow">
            Giảm giá:
            <span id="discountAmount">0đ</span>
        </p>

        {{-- Thành tiền cuối --}}
        <p class="font-bold text-right text-xl">
            Thành tiền:
            <span id="finalTotal">{{ number_format($totalAmount) }}đ</span>
        </p>

        {{-- Hidden inputs gửi lên server --}}
        <input type="hidden" name="discount_amount" id="discountAmountInput" value="0">
        <input type="hidden" name="final_total" id="finalTotalInput" value="{{ $totalAmount }}">

        {{-- Thanh toán --}}
        <div class="mb-3 mt-4">
            <label>Phương thức thanh toán</label>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input type="radio" name="payment_method" value="COD" checked class="mr-2">
                    Thanh toán khi nhận hàng (COD)
                </label>

                <label class="flex items-center">
                    <input type="radio" name="payment_method" value="MOMO" class="mr-2">
                    Thanh toán qua Momo
                </label>
            </div>
        </div>

        <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded w-full">
            Đặt hàng
        </button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // -----------------------
        // 🟦 BIẾN TÍNH GIÁ
        // -----------------------
        const couponSelect = document.getElementById("couponSelect");
        const originalTotalEl = document.getElementById("originalTotal");
        const discountRow = document.getElementById("discountRow");
        const discountAmountEl = document.getElementById("discountAmount");
        const finalTotalEl = document.getElementById("finalTotal");
        const discountAmountInput = document.getElementById("discountAmountInput");
        const finalTotalInput = document.getElementById("finalTotalInput");

        const originalTotal = parseInt(originalTotalEl.dataset.total);

        couponSelect.addEventListener("change", () => {
            const option = couponSelect.selectedOptions[0];
            let discount = 0;

            if (couponSelect.value) {
                const type = option.dataset.type;
                const value = parseInt(option.dataset.value);
                const min = parseInt(option.dataset.min);

                if (originalTotal >= min) {
                    discount = type === "percent" ?
                        Math.floor(originalTotal * value / 100) :
                        value;
                }
                discountRow.classList.remove("hidden");
                discountAmountEl.textContent = discount.toLocaleString() + "đ";
            } else {
                discountRow.classList.add("hidden");
                discountAmountEl.textContent = "0đ";
            }

            const final = Math.max(0, originalTotal - discount);
            finalTotalEl.textContent = final.toLocaleString() + "đ";

            // ❗ Cập nhật hidden input
            discountAmountInput.value = discount;
            finalTotalInput.value = final;
        });

        // -----------------------
        // 🟩 GHÉP ĐỊA CHỈ
        // -----------------------
        const street = document.getElementById("street");
        const province = document.getElementById("province");
        const district = document.getElementById("district");
        const ward = document.getElementById("ward");
        const fullAddressInput = document.getElementById("fullAddress");

        function updateAddress() {
            const full = [
                street.value.trim(),
                ward.options[ward.selectedIndex]?.text ?? "",
                district.options[district.selectedIndex]?.text ?? "",
                province.options[province.selectedIndex]?.text ?? "",
                "Việt Nam"
            ].filter(Boolean).join(", ");
            fullAddressInput.value = full;
        }

        street.addEventListener("input", updateAddress);
        province.addEventListener("change", updateAddress);
        district.addEventListener("change", updateAddress);
        ward.addEventListener("change", updateAddress);

        // ✅ Update address trước khi submit
        const checkoutForm = document.getElementById("checkoutForm");
        checkoutForm.addEventListener("submit", () => {
            updateAddress();
        });

        // -----------------------
        // 🟧 LOAD API tỉnh/huyện/xã
        // -----------------------
        fetch("https://provinces.open-api.vn/api/p/")
            .then(res => res.json())
            .then(data => {
                province.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
                data.forEach(p => {
                    const opt = document.createElement("option");
                    opt.value = p.code;
                    opt.textContent = p.name;
                    province.appendChild(opt);
                });
            });

        province.addEventListener("change", () => {
            fetch(`/api/districts/${province.value}`)
                .then(res => res.json())
                .then(data => {
                    district.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                    ward.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    data.districts?.forEach(d => {
                        const opt = document.createElement("option");
                        opt.value = d.code;
                        opt.textContent = d.name;
                        district.appendChild(opt);
                    });
                });
        });

        district.addEventListener("change", () => {
            fetch(`/api/wards/${district.value}`)
                .then(res => res.json())
                .then(data => {
                    ward.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    data.wards?.forEach(w => {
                        const opt = document.createElement("option");
                        opt.value = w.name;
                        opt.textContent = w.name;
                        ward.appendChild(opt);
                    });
                });
        });
    });
</script>

@endsection