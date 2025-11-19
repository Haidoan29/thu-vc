@extends('user.layouts.app')

@section('title', 'Danh sách yêu thích')

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

    <div id="wishlist-items">
        <!-- Các sản phẩm wishlist sẽ được JS render ở đây -->
    </div>

    <!-- Footer row -->
    <div class="flex justify-between items-center py-4">
        <div class="flex items-center gap-4">
            <input type="checkbox" id="select-all-footer"> Chọn tất cả (<span id="selected-count">0</span>)
            <button class="text-red-500" id="btn-remove-selected">Xóa</button>
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
        const wishlistContainer = document.getElementById('wishlist-items');
        const selectAll = document.getElementById('select-all');
        const selectAllFooter = document.getElementById('select-all-footer');
        const selectedTotalEl = document.getElementById('selected-total');
        const selectedCountEl = document.getElementById('selected-count');
        const selectedCountFooterEl = document.getElementById('selected-count-footer');
        const buyBtn = document.getElementById('btn-buy');
        const removeSelectedBtn = document.getElementById('btn-remove-selected');

        function getWishlist() {
            return JSON.parse(localStorage.getItem('wishlist')) || [];
        }

        function saveWishlist(wishlist) {
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
        }

        function renderWishlist() {
            const wishlist = getWishlist();
            wishlistContainer.innerHTML = '';

            if (wishlist.length === 0) {
                wishlistContainer.innerHTML = '<p class="text-center py-4">Danh sách yêu thích trống</p>';
                updateTotal();
                return;
            }

            wishlist.forEach(item => {
                const price = Number(item.price) || 0;
                const row = document.createElement('div');
                row.classList.add('grid', 'grid-cols-12', 'py-4', 'border-b', 'items-center', 'product-row');
                row.dataset.unitPrice = price; // lưu giá gốc
                row.dataset.id = item.id;

                row.innerHTML = `
                <div class="col-span-5 flex gap-3">
                    <input type="checkbox" class="select-item" data-id="${item.id}" data-subtotal="${price}">
                    <img src="${item.images?.[0] || item.image || 'http://via.placeholder.com/400'}" class="w-20 h-20 object-cover border rounded">
                    <div>
                        <p class="text-sm font-medium">${item.name}</p>
                    </div>
                </div>
                <div class="col-span-2 text-center text-gray-600">
                    ${price.toLocaleString()}đ
                </div>
                <div class="col-span-2 text-center">
                    <div class="inline-flex border rounded">
                        <button class="px-3 py-1 border-r btn-decrement">-</button>
                        <input type="text" value="1" class="w-10 text-center outline-none qty-input">
                        <button class="px-3 py-1 border-l btn-increment">+</button>
                    </div>
                </div>
                <div class="col-span-2 text-center text-orange-600 font-semibold subtotal">
                    ${price.toLocaleString()}đ
                </div>
                <div class="col-span-1 text-center">
                    <button class="text-red-500 text-sm btn-remove">Xóa</button>
                </div>
            `;
                wishlistContainer.appendChild(row);
            });

            initRowEvents();
            updateTotal();
        }

        function initRowEvents() {
            document.querySelectorAll('.product-row').forEach(row => {
                const decrement = row.querySelector('.btn-decrement');
                const increment = row.querySelector('.btn-increment');
                const qtyInput = row.querySelector('.qty-input');
                const subtotalEl = row.querySelector('.subtotal');
                const checkbox = row.querySelector('.select-item');
                const removeBtn = row.querySelector('.btn-remove');

                const unitPrice = Number(row.dataset.unitPrice);

                function updateRowQty(newQty) {
                    qtyInput.value = newQty;
                    const newSubtotal = unitPrice * newQty;
                    subtotalEl.textContent = newSubtotal.toLocaleString() + 'đ';
                    checkbox.dataset.subtotal = newSubtotal;
                    updateTotal();
                }

                // Tăng giảm số lượng
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

                // Xóa sản phẩm
                removeBtn.addEventListener('click', () => {
                    let wishlist = getWishlist();
                    wishlist = wishlist.filter(item => item.id !== row.dataset.id);
                    saveWishlist(wishlist);
                    renderWishlist();
                });

                checkbox.addEventListener('change', updateTotal);
            });
        }

        function updateTotal() {
            const selectedItems = document.querySelectorAll('.select-item:checked');
            let total = 0;
            selectedItems.forEach(cb => total += Number(cb.dataset.subtotal) || 0);
            const count = selectedItems.length;
            selectedTotalEl.textContent = total.toLocaleString() + 'đ';
            selectedCountEl.textContent = count;
            selectedCountFooterEl.textContent = count;
        }

        function toggleSelectAll(status) {
            document.querySelectorAll('.select-item').forEach(cb => cb.checked = status);
            updateTotal();
        }

        if (selectAll) selectAll.addEventListener('change', () => toggleSelectAll(selectAll.checked));
        if (selectAllFooter) selectAllFooter.addEventListener('change', () => toggleSelectAll(selectAllFooter.checked));

        // Xóa các sản phẩm đã chọn
        removeSelectedBtn.addEventListener('click', () => {
            let wishlist = getWishlist();
            document.querySelectorAll('.select-item:checked').forEach(cb => {
                wishlist = wishlist.filter(item => item.id !== cb.dataset.id);
            });
            saveWishlist(wishlist);
            renderWishlist();
        });

        // Thanh toán
        // buyBtn.addEventListener('click', () => {
        //     const selectedItems = Array.from(document.querySelectorAll('.select-item:checked')).map(cb => ({
        //         id: cb.dataset.id,
        //         quantity: parseInt(cb.closest('.product-row').querySelector('.qty-input').value) || 1
        //     }));

        //     if (selectedItems.length === 0) {
        //         alert('Vui lòng chọn ít nhất 1 sản phẩm!');
        //         return;
        //     }

        //     fetch('/cart/checkout-session', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //         },
        //         body: JSON.stringify({ items: selectedItems })
        //     }).then(res => res.json())
        //     .then(data => {
        //         if (data.status === 'success') {
        //             // Không xóa wishlist
        //             window.location.href = '/checkout';
        //         } else {
        //             alert('Đã có lỗi xảy ra!');
        //         }
        //     });
        // });

        buyBtn.addEventListener('click', () => {
            const selectedItems = Array.from(document.querySelectorAll('.select-item:checked')).map(cb => ({
                id: cb.dataset.id,
                quantity: parseInt(cb.closest('.product-row').querySelector('.qty-input').value) || 1
            }));

            if (selectedItems.length === 0) {
                alert('Vui lòng chọn ít nhất 1 sản phẩm!');
                return;
            }

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
                        // Xóa sản phẩm đã mua khỏi wishlist
                        let wishlist = getWishlist();
                        const boughtIds = selectedItems.map(i => i.id);
                        wishlist = wishlist.filter(item => !boughtIds.includes(item.id));
                        saveWishlist(wishlist);

                        // Render lại wishlist
                        renderWishlist();

                        // Chuyển trang checkout
                        window.location.href = '/checkout';
                    } else {
                        alert('Đã có lỗi xảy ra!');
                    }
                });
        });


        renderWishlist();
    });
</script>
@endsection