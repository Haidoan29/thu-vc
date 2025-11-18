@extends('layouts.app')
@section('title','Quản lý sản phẩm')
@section('content')
<h2 class="text-2xl mb-4">Danh sách sản phẩm</h2>

<div class="flex justify-between items-center mb-3">
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Thêm sản phẩm
    </a>

    <!-- Form tìm kiếm -->
    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex align-items-center">
        <input type="text" name="search" value="{{ request('search') }}"
            class="form-control me-2" placeholder="Tìm kiếm sản phẩm...">
        <button class="btn btn-outline-success" type="submit">Tìm</button>
        <select name="perPage" class="form-select me-2  ml-2" onchange="this.form.submit()">
            @foreach([5, 10, 15, 20] as $size)
            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>{{ $size }} / trang</option>
            @endforeach
        </select>
    </form>
</div>

<table class="table table-bordered">
    <thead class="text-center">
        <tr>
            <th>STT</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Hình ảnh</th>
            <th>Trạng thái</th>
            <th>Số lượng</th>
            <th width="150">Thao tác</th>
        </tr>
    </thead>
    <tbody class="text-center">
        @forelse($products as $index => $p)
        <tr>
            <td>{{ $products->firstItem() + $index }}</td>
            <td>{{ $p->name }}</td>
            <td>{{ number_format($p->price) }} đ</td>
            <td>
                @if($p->images)
                @foreach($p->images as $img)
                <img src="{{ $img }}" width="50" class="me-1 mb-1">
                @endforeach
                @else
                -
                @endif
            </td>
            <td>{{ $p->status }}</td>
            <td>{{ $p->stock }}</td>
            <td class="text-center">
                <a href="{{ route('admin.products.edit', $p->_id) }}" class="btn btn-warning btn-sm" title="Sửa">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form action="{{ route('admin.products.destroy', $p->_id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xoá?')" title="Xoá">
                        <i class="bi bi-trash3"></i>
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Không có sản phẩm nào</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
</div>
@endsection