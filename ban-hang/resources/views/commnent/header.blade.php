<header class="py-2">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">

            <!-- Logo -->
            <div class="logo">
                <a href="/">
                    <img src="https://cdn3527.cdn-template-4s.com/media/logo/logo.png" class="img-fluid" alt="logo">
                </a>
            </div>

            <!-- Desktop Menu -->
            <nav class="d-none d-lg-block">
                <ul class="d-flex gap-4 m-0 p-0 list-unstyled">

                    <li><a href="/" class="text-decoration-none text-dark">Trang chủ</a></li>
                    <li><a href="/ve-chung-toi" class="text-decoration-none text-dark">Giới thiệu</a></li>

                    <!-- Sản phẩm (Dropdown) -->
                    <li class="nav-item dropdown position-relative">
                        <a class="nav-link dropdown-toggle" href="/san-pham">Sản phẩm</a>

                        <ul class="dropdown-menu shadow">

                            <li><a class="dropdown-item" href="/vong-tay">Vòng tay</a></li>
                            <li><a class="dropdown-item" href="/hoa-tai">Hoa tai</a></li>
                            <li><a class="dropdown-item" href="/day-chuyen">Dây chuyền</a></li>

                            <!-- Submenu -->
                            <li class="dropdown-submenu position-relative">
                                <a class="dropdown-item dropdown-toggle" href="/nhan">Nhẫn</a>

                                <ul class="dropdown-menu submenu shadow">
                                    <li><a class="dropdown-item" href="/nhan-cau-hon">Nhẫn cầu hôn</a></li>
                                    <li><a class="dropdown-item" href="/nhan-cuoi">Nhẫn cưới</a></li>
                                    <li><a class="dropdown-item" href="/nhan-khac">Nhẫn khác</a></li>
                                </ul>
                            </li>

                            <li><a class="dropdown-item" href="/lac-chan">Lắc chân</a></li>
                            <li><a class="dropdown-item" href="/kim-cuong">Kim cương</a></li>
                            <li><a class="dropdown-item" href="/bo-trang-suc">Bộ trang sức</a></li>
                        </ul>
                    </li>

                    <li><a href="/gia-vang-hom-nay" class="text-decoration-none text-dark">Giá vàng</a></li>
                    <li><a href="/tin-tuc" class="text-decoration-none text-dark">Tin tức</a></li>
                    <li><a href="/lien-he" class="text-decoration-none text-dark">Liên hệ</a></li>

                </ul>
            </nav>

            <!-- Icons -->
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-magnifying-glass icon-btn"></i>

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

                <a href="#" class="icon-btn position-relative">
                    <i class="fa-regular fa-heart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">0</span>
                </a>

                <a href="#" class="icon-btn position-relative">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">0</span>
                </a>

                <!-- Mobile Toggle -->
                <i class="fa-solid fa-bars icon-btn d-lg-none" id="btn-open-menu"></i>
            </div>

        </div>
    </div>
</header>