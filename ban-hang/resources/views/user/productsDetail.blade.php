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

            <div class="text-yellow-500 text-xl mb-2">
                ★★★★★
            </div>

            <div class="text-3xl font-bold mb-5">{{ number_format($product->price) }} <span class="text-lg font-normal">VND</span></div>

            <!-- <p class="mb-1"><strong>Mã sản phẩm:</strong> {{ $product->sku ?? 'Chưa có' }}</p> -->
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
    <div class="flex gap-4 mt-10 border-t pt-6">
        <button class="px-6 py-3 bg-[#c9a66c] text-white rounded">Thông tin sản phẩm</button>
        <button class="px-6 py-3 bg-gray-200 rounded">Đánh giá</button>
        <button class="px-6 py-3 bg-gray-200 rounded">Bình luận</button>
    </div>

</section>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const addBtn = document.getElementById("btn-add-to-cart");

        addBtn.addEventListener("click", function() {

            let productId = this.getAttribute("data-id");
            let quantity = document.getElementById("quantity").value;

            fetch("{{ route('cart.add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: Number(quantity)
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