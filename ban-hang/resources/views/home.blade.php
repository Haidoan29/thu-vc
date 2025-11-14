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
                @if($index == 1) {{-- Card 2: ảnh dưới --}}
                <div class="card-content">
                    <h3>{{ $p->name }}</h3>
                    <p>{{ $p->description }}</p>
                   <a href="{{ route('products.detail', $p->_id) }}" class="btn">Xem thêm</a>
                </div>
                @if(!empty($p->images) && count($p->images) > 0)
                <img src="{{ $p->images[0] }}" alt="{{ $p->name }}">
                @endif
                @else {{-- Card 1 & 3: ảnh trên --}}
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
    <section class="product-section">
        <h2 class="product-title">Sản phẩm</h2>
        <p class="product-sub">Khám phá dịch vụ thiết kế riêng miễn phí của Jewelry nhé!</p>

        <div class="product-slider">

            <!-- Nút trái -->
            <button class="arrow left">&#8249;</button>

            <div class="product-list">

                @foreach($categories as $category)
                <div class="product-item">
                    <div class="circle">
                        <img src="{{ $category->image }}" alt="{{ $category->name }}">
                    </div>
                    <p class="name">{{ strtoupper($category->name) }}</p>
                </div>
                @endforeach

            </div>

            <!-- Nút phải -->
            <button class="arrow right">&#8250;</button>

        </div>

    </section>
    <section class="hottrend">
        <h2 class="hottrend-title">Hot trend</h2>
        <p class="hottrend-sub">Xu hướng trang sức sẽ lên ngôi trong năm 2025. Cùng Jewelry cập nhật xu hướng hot nhất
        </p>

        <div class="hottrend-list">
            @foreach($latestProducts as $product)
            <div class="hottrend-list">
                @foreach($latestProducts as $product)
                <div class="hottrend-item">
                    <div class="img-box">
                        @if($p->images)
                        @foreach($p->images as $img)
                        <img src="{{ $img }}" width="150">
                        @endforeach
                        @endif

                    </div>
                    <h3 class="name">{{ $product->name }}</h3>
                    <div class="price">
                        <span class="new">{{ number_format($product->price) }} VND</span>
                        @if(isset($product->old_price) && $product->old_price > 0)
                        <span class="old">{{ number_format($product->old_price) }} VND</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

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
            <div class="suggest-item">
                <div class="img-box">
                    <img src="{{ $p->images[0] ?? 'http://via.placeholder.com/150' }}" alt="{{ $p->name }}" style="width:100%;">
                </div>
                <h3 class="name">{{ $p->name }}</h3>
                <div class="price">
                    <span class="new">{{ number_format($p->price) }} VND</span>
                </div>
            </div>
            @endforeach
        </div>

    </section>
    <section class="dealhot">
        <div class="dealhot-container">

            <h2 class="deal-title">Deal hot trong ngày</h2>
            <p class="deal-sub">
                Trang sức không chỉ làm bạn đẹp hơn, tôn vinh những đường nét thanh tú của bạn mà còn thể hiện cá tính,
                gu thời trang riêng của mỗi người
            </p>

            <div class="deal-content">

                <!-- Nút trái -->
                <button class="deal-btn left"><span>&lt;</span></button>

                <!-- Hình sản phẩm -->
                <div class="deal-image">
                    <img src="img/deal-product.png" alt="">
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
                <button class="deal-btn right"><span>&gt;</span></button>

            </div>
        </div>
    </section>
    <section class="news-section">
        <h2 class="news-title">Tin mới nhất</h2>
        <p class="news-subtitle">
            Thêm chút ngọt ngào cho vẻ ngoài kiêu sa với mẫu bông tai yêu thích.
        </p>

        <div class="news-wrapper">

            <!-- ITEM 1 -->
            <div class="news-item">
                <div class="news-img">
                    <img src="https://cdn3527.cdn-template-4s.com/thumbs/news/blog-7_thumb_720.jpg" alt="">
                    <button class="arrow-btn left">&#10094;</button>
                </div>

                <h3 class="news-heading">
                    Tô điểm cho bản thân với bộ trang sức tuyệt đẹp
                </h3>
                <p class="news-desc">
                    Thời trang vốn dĩ không chỉ là váy áo, mà còn là sự khéo léo kết hợp
                    của những món phụ kiện khẳng định phong cách của [...]
                </p>
                <a href="#" class="news-btn">Xem thêm</a>
            </div>
        </div>
    </section>
</div>
@endsection