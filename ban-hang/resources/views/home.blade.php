@extends('user.layouts.app')

@section('title', 'Trang chủ')

@section('content')
<div class="container mt-5">
    <section class="hero">
        <div class="hero-overlay">
            <div class="hero-content">
                <h1 class="title">
                    BỘ SƯU TẬP TRANG SỨC <br>
                    SANG TRỌNG
                </h1>

                <p class="desc">
                    Trang sức Jewelry mang phong cách trẻ trung, hiện đại, liên tục cập nhật những xu
                    hướng mới từ Hàn Quốc. Ba dòng sản phẩm chủ lực của Jewelry gồm có: Nhẫn cưới,
                    Trang sức cưới và Trang sức hiện đại cho nữ giới.
                </p>

                <a href="#" class="btn">Tìm hiểu thêm</a>
            </div>
        </div>
    </section>

    <section class="collection-section">
        <h2 class="section-title">Bộ sưu tập trang sức</h2>
        <p class="section-sub">
            Khám phá bộ sưu tập trang sức mới nhất với thiết kế sang trọng và độc nhất của chúng tôi!
        </p>
        <div class="collection-grid">
            @foreach($products as $index => $p)
            <div class="collection-card">
                @if($index == 1) 
                <div class="card-content">
                    <h3>{{ $p->name }}</h3>
                    <p>{{ $p->description }}</p>
                    <a href="{{ route('products.detail', $p->_id) }}" class="btn">Xem thêm</a>
                </div>
                @if(!empty($p->images) && count($p->images) > 0)
                <img src="{{ $p->images[0] }}" alt="{{ $p->name }}">
                @endif
                @else 
                @if(!empty($p->images) && count($p->images) > 0)
                <img src="{{ $p->images[0] }}" alt="{{ $p->name }}">
                @endif
                <div class="card-content">
                    <h3>{{ $p->name }}</h3>
                    <p>{{ $p->description }}</p>
                    <a href="{{ route('products.detail', $p->_id) }}" class="btn">Xem thêm</a>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    <section class="product-section my-8">
        <h2 class="product-title text-3xl font-bold mb-2">Sản phẩm</h2>
        <p class="product-sub text-gray-600 mb-6">Khám phá dịch vụ thiết kế riêng miễn phí của Jewelry nhé!</p>

        <div class="relative flex items-center">
            <button id="prevBtn" class="arrow left bg-gray-200 p-3 rounded-full shadow hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                &#8249;
            </button>
            <div class="overflow-hidden flex-1 mx-4">
                <div id="categoryList" class="flex transition-transform duration-300">
                    @foreach($categories as $category)
                    <a href="{{ route('category.products', $category->_id) }}" class="product-item flex-shrink-0 w-1/5 text-center p-3 cursor-pointer hover:scale-105 transform transition-all duration-200">
                        <div class="circle w-24 h-24 mx-auto mb-3 rounded-full overflow-hidden border border-gray-300 shadow-sm">
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                        </div>
                        <p class="name text-sm font-medium text-gray-700">{{ strtoupper($category->name) }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            <button id="nextBtn" class="arrow right bg-gray-200 p-3 rounded-full shadow hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                &#8250;
            </button>
        </div>
    </section>
    <section class="hottrend">  
        <h2 class="hottrend-title">Hot trend</h2>
        <p class="hottrend-sub">Xu hướng trang sức sẽ lên ngôi trong năm 2025. Cùng Jewelry cập nhật xu hướng hot nhất</p>
        <div class="hottrend-list">
            @foreach($latestProducts as $product)
            <a href="{{ route('products.detail', ['id' => $product->id]) }}" class="hottrend-item block">
                <div class="img-box">
                    @if(!empty($product->images))
                    <img src="{{ $product->images[0] }}" width="150" alt="{{ $product->name }}">
                    @else
                    <img src="{{ asset('images/default.png') }}" width="150" alt="No image">
                    @endif
                </div>

                <h3 class="name">{{ $product->name }}</h3>

                <div class="price">
                    <span class="new">{{ number_format($product->price) }} VND</span>
                    @if(isset($product->old_price) && $product->old_price > 0)
                    <span class="old">{{ number_format($product->old_price) }} VND</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </section>


    <section class="suggest">
        <h2 class="suggest-title">Gợi ý riêng cho bạn</h2>
        <p class="suggest-sub">
            Trang sức không chỉ làm bạn đẹp hơn, tôn vinh những đường nét thanh tú của bạn mà còn thể hiện cá tính, gu
            thời trang riêng của mỗi người
        </p>

        <div class="suggest-list">
            @foreach($getproducts as $p)
            <a href="{{ route('products.detail', ['id' => $p->id]) }}" class="suggest-item block">
                <div class="img-box">
                    <img src="{{ $p->images[0] ?? 'http://via.placeholder.com/150' }}" alt="{{ $p->name }}" style="width:100%;">
                </div>
                <h3 class="name">{{ $p->name }}</h3>
                <div class="price">
                    <span class="new">{{ number_format($p->price) }} VND</span>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    <section class="dealhot mb-5">
        <div class="dealhot-container">

            <h2 class="deal-title">Deal hot trong ngày</h2>
            <p class="deal-sub">
                Trang sức không chỉ làm bạn đẹp hơn, tôn vinh những đường nét thanh tú của bạn mà còn thể hiện cá tính,
                gu thời trang riêng của mỗi người
            </p>

            <div class="deal-content">

                <!-- Nút trái -->
                <!-- <button class="deal-btn left"><span>&lt;</span></button> -->

                <!-- Hình sản phẩm -->
                <div class="deal-image">
                    <img src="https://cdn3527.cdn-template-4s.com/thumbs/product/pro-32_thumb_350.webp" alt="">
                </div>

                <!-- Mô tả -->
                <div class="deal-info">
                    <h3 class="deal-name">Bộ trang sức kim cương</h3>

                    <div class="deal-price">7,000,000 <span>VND</span></div>

                    <div class="deal-line"></div>

                    <p class="deal-desc">
                        Thiết kế mặt dây hình cỏ 3, mỗi cánh gắn đá hình trái tim mang ý nghĩa về tình yêu vĩnh cửu và
                        sự trân trọng<br>
                        Một dây phù hợp với dây rút 3 phân<br><br>
                        Sản phẩm vàng 14K gồm 58.5% vàng nguyên chất và các kim loại quý khác, nên sản phẩm có độ cứng
                        và bền cao
                    </p>

                    <button class="deal-add">Thêm giỏ hàng</button>
                </div>

                <!-- Nút phải -->
                <!-- <button class="deal-btn right"><span>&gt;</span></button> -->

            </div>
        </div>
    </section>
</div>
@endsection
@section('style')
<style>
    .hottrend-list {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        /* 4 cột */
        gap: 20px;
        /* khoảng cách giữa các sản phẩm */
    }

    .hottrend-item {
        background: #fff;
        padding: 10px;
        border: 1px solid #eee;
        text-align: center;
    }

    .suggest-list {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        /* 4 sản phẩm 1 hàng */
        gap: 20px;
        /* khoảng cách giữa các sản phẩm */
    }

    .suggest-item {
        background: #fff;
        padding: 10px;
        border: 1px solid #eee;
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .suggest-list {
            grid-template-columns: repeat(2, 1fr);
            /* 2 sản phẩm 1 hàng trên tablet */
        }
    }

    @media (max-width: 640px) {
        .suggest-list {
            grid-template-columns: 1fr;
            /* 1 sản phẩm 1 hàng trên mobile */
        }
    }
</style>

@endsection