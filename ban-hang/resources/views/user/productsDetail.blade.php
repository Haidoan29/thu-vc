@extends('user.layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<section class="max-w-[1400px] mx-auto px-4 py-10">

    <div class="flex gap-12 flex-col lg:flex-row">
        <div class="w-full lg:w-1/2 bg-gray-100 p-10 relative">
            <img src="{{ $product->images[0] ?? 'http://via.placeholder.com/400' }}" class="w-full">

            <button class="absolute bottom-4 right-4 p-2 border bg-white rounded shadow">
                <i class="fas fa-expand"></i>
            </button>
        </div>

        <div class="w-full lg:w-1/2">
            <h2 class="text-3xl font-semibold mb-3">
                {{ $product->name }}
            </h2>

            @php
            $fullStars = floor($averageRating); // số sao đầy
            $halfStar = ($averageRating - $fullStars) >= 0.5 ? 1 : 0; // nếu >= 0.5 thì có nửa sao
            $emptyStars = 5 - $fullStars - $halfStar; // phần còn lại là sao rỗng
            @endphp

            <div class="text-yellow-500 text-xl mb-2">
                {{-- full stars --}}
                @for ($i = 0; $i < $fullStars; $i++)
                    <i class="fas fa-star"></i>
                    @endfor

                    {{-- half star --}}
                    @if($halfStar)
                    <i class="fas fa-star-half-alt"></i>
                    @endif

                    {{-- empty stars --}}
                    @for ($i = 0; $i < $emptyStars; $i++)
                        <i class="far fa-star"></i>
                        @endfor

                        <span class="text-gray-700 ml-2">({{ number_format($averageRating, 1) }}<span class="text-yellow-500">★</span>)</span>
            </div>



            <div class="text-3xl font-bold mb-5">{{ number_format($product->price) }} <span class="text-lg font-normal">VND</span></div>
            <p class="mb-4">
                <strong>Danh mục:</strong>
                <a class="text-green-700" href="#">
                    {{ $product->category->name ?? 'Chưa có' }}
                </a>
            </p>


            <p class="mb-3 leading-relaxed">
                {{ $product->description }}
            </p>

            <div class="flex items-center gap-4 mb-6">

                <div class="flex border rounded">
                    <button id="decrement" class="px-3 py-2 border-r">-</button>
                    <input type="text" id="quantity" value="1" class="w-12 text-center outline-none">
                    <button id="increment" class="px-3 py-2 border-l">+</button>
                </div>

                <button class="bg-[#6e7145] text-white px-6 py-3 rounded" id="btn-add-to-cart"
                    data-id="{{ $product->id }}">
                    THÊM GIỎ HÀNG
                </button>


                <button class="w-10 h-10 border rounded flex items-center justify-center">
                    <i class="far fa-heart"></i>
                </button>
            </div>

            <div class="flex items-center gap-3">
                <span>Chia sẻ:</span>
                <a href="#" class="text-gray-600 hover:text-gray-900"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-gray-600 hover:text-gray-900"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-gray-600 hover:text-gray-900"><i class="fab fa-google-plus-g"></i></a>
                <a href="#" class="text-gray-600 hover:text-gray-900"><i class="fab fa-pinterest"></i></a>
                <a href="#" class="text-gray-600 hover:text-gray-900"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
    <div class="mt-10 border-t pt-6">
        <!-- Form đánh giá luôn hiển thị -->
        <div id="review-form-container" class="mt-4 border p-6 rounded bg-gray-50">
            <h3 class="text-xl font-semibold mb-4">Đánh giá sản phẩm</h3>

            <form id="review-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="mb-4">
                    <label class="block mb-1">Điểm đánh giá</label>
                    <select name="rating" class="border rounded w-full py-2 px-3 text-yellow-500">
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★☆</option>
                        <option value="3">★★★☆☆</option>
                        <option value="2">★★☆☆☆</option>
                        <option value="1">★☆☆☆☆</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Bình luận</label>
                    <textarea name="comment" rows="4" class="border rounded w-full py-2 px-3" placeholder="Viết bình luận của bạn..."></textarea>
                </div>

                <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded">Gửi đánh giá</button>
            </form>
        </div>

        <!-- Danh sách review hiện tại -->
        <div id="reviews-list" class="mt-6 space-y-4">
            @foreach($reviews as $index => $review)
            <div class="border p-3 rounded bg-white review-item {{ $index >= 3 ? 'hidden' : '' }}">
                <div class="font-semibold">{{ $review->user->name ?? 'Người dùng' }}</div>
                <div class="text-yellow-500">{{ str_repeat('★', $review->rating) }}</div>
                <div class="mt-2">{{ $review->comment }}</div>
            </div>
            @endforeach

            @if($reviews->count() > 3)
            <button id="toggle-reviews" class="mt-4 px-4 py-2 bg-gray-200 rounded">Xem thêm</button>
            @endif
        </div>

    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById("toggle-reviews");
        const reviews = document.querySelectorAll('.review-item');
        let showCount = 3; // số review hiện ban đầu
        const step = 3; // mỗi lần hiện thêm 3 review

        if (toggleBtn) {
            toggleBtn.addEventListener("click", function() {
                showCount += step;
                let hiddenReviews = 0;

                reviews.forEach((item, index) => {
                    if (index < showCount) {
                        item.classList.remove('hidden');
                    } else {
                        hiddenReviews++;
                    }
                });

                if (hiddenReviews > 0) {
                    toggleBtn.innerText = 'Xem thêm';
                } else {
                    toggleBtn.innerText = 'Ẩn bớt';
                }

                // nếu đã hiện hết và người dùng ấn "Ẩn bớt"
                if (showCount >= reviews.length && toggleBtn.innerText === 'Ẩn bớt') {
                    toggleBtn.addEventListener('click', function reset() {
                        reviews.forEach((item, index) => {
                            if (index >= 3) item.classList.add('hidden');
                        });
                        showCount = 3;
                        toggleBtn.innerText = 'Xem thêm';
                        toggleBtn.removeEventListener('click', reset);
                    }, {
                        once: true
                    });
                }
            });
        }
    });

   document.addEventListener("DOMContentLoaded", function() {
    const addBtn = document.getElementById("btn-add-to-cart");
    if (!addBtn) return; // tránh lỗi nếu nút không tồn tại

    addBtn.addEventListener("click", async function() {
        let productId = this.getAttribute("data-id");
        let quantity = Number(document.getElementById("quantity").value);

        // Kiểm tra tồn kho
        const checkRes = await fetch(`/products/check/${productId}`);
        const checkData = await checkRes.json();

        if (!checkData.exists) {
            alert("Sản phẩm này hiện không còn trong kho!");
            return;
        }

        // Thêm vào giỏ hàng
        const res = await fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        });

        const data = await res.json();
        if (data.success) {
            document.getElementById("cart-count").innerText = data.cart_count;
        } else if (data.error === "not_logged_in") {
            window.location.href = "/login";
        }
    });
});





    entListener("DOMContentLoaded", function() {

        const addBtn = document.getElementById("btn-add-to-cart");

        addBtn.addEventListener("click", async function() {

            let productId = this.getAttribute("data-id");
            let quantity = Number(document.getElementById("quantity").value);

            const checkRes = await fetch(`/products/check/${productId}`);
            const checkData = await checkRes.json();

            if (!checkData.exists) {
                alert("Sản phẩm này hiện không còn trong kho!");
                return;
            }

            fetch("{{ route('cart.add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById("cart-count").innerText = data.cart_count;
                    }
                    if (data.error === "not_logged_in") {
                        window.location.href = "/admin/login";
                    }
                });
        });

    });
</script>
@endsection