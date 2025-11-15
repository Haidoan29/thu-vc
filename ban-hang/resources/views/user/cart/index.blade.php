@extends('user.layouts.app')

@section('title', 'Chi tiết giỏ hàng')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-4 my-6 border rounded shadow">

    <!-- Header -->
    <div class="grid grid-cols-12 font-semibold py-3 border-b bg-gray-50">
        <div class="col-span-5 flex items-center gap-2">
            <input type="checkbox" id="select-all">
            <span>Sản Phẩm</span>
        </div>
        <div class="col-span-2 text-center">Đơn Giá</div>
        <div class="col-span-2 text-center">Số Lượng</div>
        <div class="col-span-2 text-center">Số Tiền</div>
        <div class="col-span-1 text-center">Thao Tác</div>
    </div>

    @forelse ($items as $item)
    <!-- Product row -->
    <div class="grid grid-cols-12 py-4 border-b items-center product-row">
        <!-- Select + Image + Name -->
        <div class="col-span-5 flex gap-3">
            <input type="checkbox" class="select-item" data-id="{{ $item['id'] }}" data-subtotal="{{ $item['subtotal'] }}">
            <img src="{{ $item['image'] }}" class="w-20 h-20 object-cover border rounded">
            <div>
                <p class="text-sm font-medium">{{ $item['name'] }}</p>
            </div>
        </div>

        <!-- Đơn giá -->
        <div class="col-span-2 text-center text-gray-600">
            {{ number_format($item['price']) }}đ
        </div>

        <!-- Số lượng -->
        <div class="col-span-2 text-center">
            <div class="inline-flex border rounded">
                <button class="px-3 py-1 border-r btn-decrement">-</button>
                <input type="text" value="{{ $item['quantity'] }}" class="w-10 text-center outline-none qty-input">
                <button class="px-3 py-1 border-l btn-increment">+</button>
            </div>
        </div>

        <!-- Thành tiền -->
        <div class="col-span-2 text-center text-orange-600 font-semibold subtotal">
            {{ number_format($item['subtotal']) }}đ
        </div>

        <!-- Xóa -->
        <div class="col-span-1 text-center">
            <button class="text-red-500 text-sm btn-remove">Xóa</button>
        </div>
    </div>
    @empty
    <p class="text-center py-4">Giỏ hàng trống</p>
    @endforelse

    <!-- Footer row -->
    <div class="flex justify-between items-center py-4">
        <div class="flex items-center gap-4">
            <input type="checkbox" id="select-all-footer"> Chọn tất cả (<span id="selected-count">0</span>)
            <button class="text-red-500">Xóa</button>
        </div>

        <div class="flex items-center gap-4">
            <p>
                Tổng cộng (<span id="selected-count-footer">0</span> sản phẩm):
                <span class="text-orange-600 font-bold text-xl" id="selected-total">0đ</span>
            </p>

            <button class="bg-orange-600 text-white px-6 py-2 rounded" id="btn-buy">Mua hàng</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const selectItems = document.querySelectorAll('.select-item');
        const selectAll = document.getElementById('select-all');
        const selectAllFooter = document.getElementById('select-all-footer');
        const selectedTotalEl = document.getElementById('selected-total');
        const selectedCountEl = document.getElementById('selected-count');
        const selectedCountFooterEl = document.getElementById('selected-count-footer');
        const buyBtn = document.getElementById('btn-buy');

        // Hàm tính tổng tiền và số lượng
        function updateTotal() {
            let total = 0;
            let count = 0;
            selectItems.forEach(cb => {
                if (cb.checked) {
                    const subtotal = parseFloat(cb.dataset.subtotal);
                    total += subtotal;
                    count += 1;
                }
            });
            selectedTotalEl.textContent = total.toLocaleString() + 'đ';
            selectedCountEl.textContent = count;
            selectedCountFooterEl.textContent = count;
        }

        // Checkbox từng sản phẩm
        selectItems.forEach(cb => cb.addEventListener('change', updateTotal));

        // Chọn tất cả
        function toggleSelectAll(status) {
            selectItems.forEach(cb => cb.checked = status);
            updateTotal();
        }
        if (selectAll) selectAll.addEventListener('change', () => toggleSelectAll(selectAll.checked));
        if (selectAllFooter) selectAllFooter.addEventListener('change', () => toggleSelectAll(selectAllFooter.checked));

        // Hàm gửi số lượng lên server
        function updateQuantityServer(productId, quantity) {
            fetch('/cart/update-quantity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                }).then(res => res.json())
                .then(data => {
                    if (data.status !== 'success') console.error(data.message);
                });
        }

        // Xử lý tăng/giảm số lượng
        document.querySelectorAll('.product-row').forEach(row => {
            const decrement = row.querySelector('.btn-decrement');
            const increment = row.querySelector('.btn-increment');
            const qtyInput = row.querySelector('.qty-input');
            const subtotalEl = row.querySelector('.subtotal');
            const checkbox = row.querySelector('.select-item');
            const productId = checkbox.dataset.id;

            // Giá đơn vị
            let unitPrice = parseFloat(subtotalEl.textContent.replace(/,/g, '').replace('đ', '')) / parseInt(qtyInput.value);

            function updateRowQty(newQty) {
                qtyInput.value = newQty;
                const newSubtotal = unitPrice * newQty;
                subtotalEl.textContent = newSubtotal.toLocaleString() + 'đ';
                checkbox.dataset.subtotal = newSubtotal;
                updateTotal();
                updateQuantityServer(productId, newQty);
            }

            decrement.addEventListener('click', () => {
                let qty = parseInt(qtyInput.value) || 1;
                if (qty > 1) qty--;
                updateRowQty(qty);
            });

            increment.addEventListener('click', () => {
                let qty = parseInt(qtyInput.value) || 1;
                qty++;
                updateRowQty(qty);
            });

            qtyInput.addEventListener('change', () => {
                let qty = parseInt(qtyInput.value) || 1;
                if (qty < 1) qty = 1;
                updateRowQty(qty);
            });
        });

        buyBtn.addEventListener('click', function() {
            const selectedItems = Array.from(selectItems)
                .filter(cb => cb.checked)
                .map(cb => ({
                    id: cb.dataset.id,
                    quantity: parseInt(cb.closest('.product-row').querySelector('.qty-input').value) || 1
                }));

            if (selectedItems.length === 0) {
                alert('Vui lòng chọn ít nhất 1 sản phẩm!');
                return;
            }

            // Lưu tạm vào session
            fetch('/cart/checkout-session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        items: selectedItems
                    })
                }).then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = '/checkout';
                    } else {
                        alert('Đã có lỗi xảy ra!');
                    }
                });
        });
        buyBtn.addEventListener('click', function() {
            const selectedItems = Array.from(selectItems)
                .filter(cb => cb.checked)
                .map(cb => ({
                    id: cb.dataset.id,
                    quantity: parseInt(cb.closest('.product-row').querySelector('.qty-input').value) || 1
                }));

            if (selectedItems.length === 0) {
                alert('Vui lòng chọn ít nhất 1 sản phẩm!');
                return;
            }

            // Lưu tạm vào session
            fetch('/cart/checkout-session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        items: selectedItems
                    })
                }).then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = '/checkout';
                    } else {
                        alert('Đã có lỗi xảy ra!');
                    }
                });
        });

        // Khởi tạo tổng tiền ban đầu
        updateTotal();

    });
</script>
@endsection