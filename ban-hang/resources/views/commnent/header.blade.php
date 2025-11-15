<style>
    /* MINI CART BOX */
    .mini-cart-box {
        position: absolute;
        top: 60px;
        right: 0;
        width: 350px;
        background: #fff;
        border-radius: 12px;
        display: none;
        z-index: 999;
        border: 1px solid #ddd;
        animation: fadeIn 0.2s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .mini-cart-item {
        display: flex;
        align-items: center;
        padding: 12px;
        border-bottom: 1px solid #eee;
    }

    .mini-cart-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 12px;
    }

    .mini-cart-footer {
        padding: 15px;
        text-align: center;
    }

    .mini-cart-footer a {
        display: block;
        background: #ff4d4d;
        color: white;
        padding: 10px;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
    }
</style>

<header class="py-2">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">

            <!-- Logo -->
            <div class="logo">
                <a href="/">
                    <img src="https://cdn3527.cdn-template-4s.com/media/logo/logo.png" class="img-fluid" alt="logo">
                </a>
            </div>

            <nav class="d-none d-lg-block">
                <ul class="d-flex gap-4 m-0 p-0 list-unstyled">

                    <li><a href="/" class="text-decoration-none text-dark">Trang chủ</a></li>
                    <li class="nav-item dropdown position-relative">
                        <a class="nav-link dropdown-toggle" href="/san-pham">Sản phẩm</a>

                        <ul class="dropdown-menu shadow">
                            @foreach($categories as $c)
                            <li>
                                <a class="dropdown-item" href="{{ route('category.products', $c->_id) }}">
                                    {{ $c->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>


                    <li><a href="/tin-tuc" class="text-decoration-none text-dark">Tin tức</a></li>
                    <li><a href="/lien-he" class="text-decoration-none text-dark">Liên hệ</a></li>

                </ul>
            </nav>

            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <a href="#" class="icon-btn dropdown-toggle" id="searchMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-magnifying-glass text-xl cursor-pointer"></i>
                    </a>
                    <ul class="dropdown-menu p-3" aria-labelledby="searchMenu" style="min-width: 280px; ;">
                        <li>
                            <div class="input-group">
                                <input type="text" class="form-control rounded-pill border-radius: 50px" placeholder="Tìm kiếm..." id="search-input">
                                <button class="btn btn-primary rounded-pill ms-2" id="search-btn">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>


                @if(Session::has('user_id'))
                <div class="dropdown">
                    <a href="#" class="icon-btn dropdown-toggle" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        Xin chào, {{ Session::get('user_name') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                        <li>
                            <form action="/admin/logout" method="POST">
                                @csrf
                                <button class="dropdown-item" type="submit">Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a href="/admin/login" class="icon-btn">
                    <i class="fa-regular fa-user"></i>
                </a>
                @endif

                <a href="javascript:void(0)" id="cart-icon" class="icon-btn position-relative">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge bg-danger" id="cart-count">0</span>
                </a>

                <!-- MINI CART POPUP -->
                <div id="mini-cart" class="mini-cart-box shadow-lg">
                    <div id="mini-cart-content" class="p-4 text-center">
                        <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="80">
                        <p class="mt-3">Chưa Có Sản Phẩm</p>
                    </div>
                </div>
                <a href="#" class="icon-btn position-relative">
                    <i class="fa-regular fa-heart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">0</span>
                </a>
                <!-- Mobile Toggle -->
                <i class="fa-solid fa-bars icon-btn d-lg-none" id="btn-open-menu"></i>
            </div>

        </div>
    </div>
</header>

@section('style')
<style>
    /* MINI CART BOX */
    .mini-cart-box {
        position: absolute;
        top: 60px;
        right: 0;
        width: 350px;
        background: #fff;
        border-radius: 12px;
        display: none;
        z-index: 999;
        border: 1px solid #ddd;
        animation: fadeIn 0.2s ease-in-out;
        max-height: 400px;
        overflow-y: auto;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .mini-cart-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .mini-cart-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 12px;
    }

    .mini-cart-item div {
        display: flex;
        flex-direction: column;
    }

    .mini-cart-item p {
        margin: 0;
        font-size: 14px;
    }

    .mini-cart-item .price {
        font-weight: bold;
        color: #ff4d4d;
    }

    .mini-cart-footer {
        padding: 10px;
        text-align: center;
    }

    .mini-cart-footer a {
        display: inline-block;
        background: #ff4d4d;
        color: white;
        padding: 8px 12px;
        text-decoration: none;
        border-radius: 6px;
        font-weight: bold;
    }
</style>
@endsection
@section('script')
<script>
    document.getElementById('search-btn').addEventListener('click', function() {
        let query = document.getElementById('search-input').value.trim();
        if(query !== '') {
            // Chuyển hướng đến trang tìm kiếm, ví dụ /search?q=...
            window.location.href = '/search?q=' + encodeURIComponent(query);
        }
    });
</script>

@endsection