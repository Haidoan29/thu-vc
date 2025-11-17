<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>@yield('title')</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .small.text-muted{
            display: none
        }
    </style>


</head>

<body class="bg-gray-50">

    <div class="flex">

        <!-- SIDEBAR -->
        <aside class="w-64 h-screen bg-white border-r px-6 py-5 fixed left-0 top-0">
            <h2 class="text-2xl font-bold mb-10 flex items-center">
                Dasher
            </h2>

            <ul>
                <li class="mb-4">
                    <a href="{{ route('admin.products.index') }}"
                        class="flex items-center gap-3 {{ request()->routeIs('admin.products.*') ? 'text-green-600' : 'text-gray-600' }} hover:text-green-600">
                        Sản phẩm
                    </a>
                </li>

                <li class="mb-4">
                    <a href="{{ route('admin.categories.index')}}"
                        class="flex items-center gap-3 {{ request()->routeIs('admin.categories.*') ? 'text-green-600' : 'text-gray-600' }} hover:text-green-600">
                        Danh mục
                    </a>
                </li>

                <li class="mb-4">
                    <a href="{{ route('order.index') }}"
                        class="flex items-center gap-3 {{ request()->routeIs('order.*') ? 'text-green-600' : 'text-gray-600' }} hover:text-green-600">
                        Đơn hàng
                    </a>
                </li>

                <li class="mb-4">
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-3 {{ request()->routeIs('admin.users.*') ? 'text-green-600' : 'text-gray-600' }} hover:text-green-600">
                        User
                    </a>
                </li>
            </ul>

            <div class="absolute bottom-10 left-6 flex items-center gap-3">
                <img src="https://i.pravatar.cc/60" class="w-10 h-10 rounded-full">
                <div>
                    <p class="font-semibold">Jitu Chauhan</p>
                    <p class="text-xs text-gray-500">Free Version - 1 Month</p>
                </div>
            </div>
        </aside>

        <div class="ml-64 flex-1">
            <nav class="w-full flex justify-end items-center px-6 py-4 bg-white border-b sticky top-0 z-20">

                <button class="relative mr-6">
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1 rounded-full">2</span>
                    🔔
                </button>

                <!-- Avatar with dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <img src="https://i.pravatar.cc/50" class="w-10 h-10 rounded-full border cursor-pointer"
                        @click="open = !open">

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-64 bg-white border rounded shadow-lg z-50">
                        <div class="p-4 border-b">
                            <p class="font-semibold">{{ session('user_name') }}</p>
                            <p class="text-sm text-gray-500">{{ session('user_email') }}</p>
                            <p class="text-sm text-gray-500 capitalize">{{ session('user_role') }}</p>
                        </div>
                        <div class="p-4">
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </nav>


            <!-- NỘI DUNG PAGE -->
            <main class="p-8">
                @yield('content')
            </main>

        </div>

    </div>

</body>

</html>