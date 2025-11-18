@extends('user.layouts.app')

@section('title', 'Kết quả tìm kiếm')

@section('content')
<div class="max-w-6xl mx-auto py-6 ">
    <form action="{{ route('products.search') }}" method="GET" class="d-flex align-items-center">
        <input type="text" name="q" class="form-control rounded-pill me-2" placeholder="Tìm kiếm..." value="{{ request('q') }}">
        <button type="submit" class="btn btn-primary rounded-pill">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>

    <!-- <h2 class="text-2xl font-semibold mb-4">Kết quả tìm kiếm cho: "{{ $query }}"</h2> -->

    @if($products->isEmpty())
        <p>Không tìm thấy sản phẩm nào.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
            @foreach($products as $product)
            <div class="border p-4 rounded shadow">
                <img src="{{ $product->images[0] ?? asset('images/default.png') }}"
                    alt="{{ $product->name }}"
                    class="w-full h-48 object-cover mb-2">
                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                <p class="text-orange-600 font-bold">{{ number_format($product->price) }}đ</p>
                <a href="{{ route('products.detail', $product->id) }}" class="text-blue-500 mt-2 inline-block">Xem chi tiết</a>
            </div>
            @endforeach
        </div>

        {{-- Phân trang --}}
        <div class="mt-6">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
