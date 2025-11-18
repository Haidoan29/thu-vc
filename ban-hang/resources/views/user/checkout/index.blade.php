@extends('user.layouts.app')
@section('title', 'Thanh toán')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 my-6 border rounded shadow">
    <h2 class="text-xl font-bold mb-4">Thông tin đặt hàng</h2>
    <form action="{{ route('checkout.store') }}" method="POST">
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
            <input type="text" id="street" name="street" class="w-full border px-2 py-1 rounded" placeholder="Ví dụ: 12 Nguyễn Lân" required>
        </div>

        <input type="hidden" name="address" id="fullAddress">

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('province');
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        const streetInput = document.getElementById('street');
        const fullAddressInput = document.getElementById('fullAddress');

        // ❗ SỬA LỖI QUAN TRỌNG: form selector sai
        const form = document.querySelector('form[action="{{ route('checkout.store') }}"]');

        // Hàm cập nhật địa chỉ đầy đủ
        function updateFullAddress() {
            const street = streetInput.value.trim();
            const ward = wardSelect.options[wardSelect.selectedIndex]?.text || '';
            const district = districtSelect.options[districtSelect.selectedIndex]?.text || '';
            const province = provinceSelect.options[provinceSelect.selectedIndex]?.text || '';

            const full = [street, ward, district, province, "Việt Nam"]
                .filter(Boolean)
                .join(', ');

            fullAddressInput.value = full;
            console.log("Updated full address:", full);
        }

        // Load provinces
        fetch('https://provinces.open-api.vn/api/p/')
            .then(res => res.json())
            .then(provinces => {
                provinceSelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
                provinces.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.code;
                    opt.textContent = p.name;
                    provinceSelect.appendChild(opt);
                });
            });

        // Load districts
        provinceSelect.addEventListener('change', function() {
            const provinceCode = this.value;
            districtSelect.innerHTML = '<option>Đang tải...</option>';
            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

            fetch(`/api/districts/${provinceCode}`)
                .then(res => res.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                    data.districts?.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.code;
                        opt.textContent = d.name;
                        districtSelect.appendChild(opt);
                    });
                });
        });

        // Load wards
        districtSelect.addEventListener('change', function() {
            const districtCode = this.value;
            wardSelect.innerHTML = '<option>Đang tải...</option>';

            fetch(`/api/wards/${districtCode}`)
                .then(res => res.json())
                .then(data => {
                    wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    data.wards?.forEach(w => {
                        const opt = document.createElement('option');
                        opt.value = w.name;    // ward value = name
                        opt.textContent = w.name;
                        wardSelect.appendChild(opt);
                    });
                });
        });

        // ❗ Tự động cập nhật address khi thay đổi phần nào
        streetInput.addEventListener("input", updateFullAddress);
        provinceSelect.addEventListener("change", updateFullAddress);
        districtSelect.addEventListener("change", updateFullAddress);
        wardSelect.addEventListener("change", updateFullAddress);

        // Cập nhật trước khi submit
        form.addEventListener('submit', function() {
            console.log("SUBMIT → generating address...");
            updateFullAddress();
        });
    });
</script>


@endsection