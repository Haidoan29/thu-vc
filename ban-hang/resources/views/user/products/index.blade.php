@extends('user.layouts.app')

@section('title', 'Sản phẩm')

@section('content')
<div class="container my-5">
    <h2 class="text-2xl font-semibold mb-4">Sản phẩm</h2>

    <div class="grid grid-cols-3 gap-6">
        @forelse($products as $p)
            <div class="border p-4 rounded shadow">
                <img src="{{ $p->images[0] ?? 'http://via.placeholder.com/150' }}" class="mb-2" alt="{{ $p->name }}" style="height: 210px;">
                <h3 class="font-semibold">{{ $p->name }}</h3>
                <p class="text-orange-600">{{ number_format($p->price) }} VND</p>
                <a href="{{ route('products.detail', $p->_id) }}" class="btn bg-blue-600 text-white mt-2 inline-block px-4 py-1 rounded">Xem chi tiết</a>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-500 py-10">
                Hiện tại chưa có sản phẩm nào trong danh mục này.
            </div>
        @endforelse
    </div>
</div>
@endsection
