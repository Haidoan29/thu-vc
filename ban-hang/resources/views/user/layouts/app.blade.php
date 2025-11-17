<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Website')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <script src="{{ asset('js/cart.js') }}"></script>
    <style>
        /* Hover dropdown */
        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-submenu:hover .dropdown-menu {
            display: block;
        }

        .dropdown-submenu .dropdown-menu {
            margin-left: 0.8rem;
            margin-top: -0.4rem;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const decrementBtn = document.getElementById('decrement');
            const incrementBtn = document.getElementById('increment');
            const quantityInput = document.getElementById('quantity');

            decrementBtn.addEventListener('click', function() {
                let current = parseInt(quantityInput.value) || 1;
                if (current > 1) quantityInput.value = current - 1;
            });

            incrementBtn.addEventListener('click', function() {
                let current = parseInt(quantityInput.value) || 1;
                quantityInput.value = current + 1;
            });
        });
    </script>
</head>

<body>

    @include('commnent.header')

    <main>
        <div class="main-content">
            @yield(section: 'content')
        </div>
    </main>

    @include('commnent.footer')
    @yield('scripts')
    @yield('style')
</body>

</html>