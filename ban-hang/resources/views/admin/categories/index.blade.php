@extends('layouts.app')
@section('title','Danh sách Category')

@section('content')
<h2 class="text-2xl mb-4">Danh sách Category</h2>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Thêm mới</a>
    <form action="{{ route('admin.categories.index') }}" method="GET" class="d-flex align-items-center">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Tìm kiếm...">
        <select name="perPage" class="form-select me-2" onchange="this.form.submit()">
            @foreach([5, 10, 15, 20] as $size)
            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}/trang</option>
            @endforeach
        </select>
        <button class="btn btn-outline-success" type="submit">Tìm</button>
    </form>
</div>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên</th>
            <th>Slug</th>
            <th>Ảnh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $index => $cat)
        <tr>
            <td>{{ $categories->firstItem() + $index }}</td>
            <td>{{ $cat->name }}</td>
            <td>{{ $cat->slug }}</td>
            <td>
                @if($cat->image)
                <img src="{{ $cat->image }}" width="50" alt="Category Image">
                @else
                -
                @endif
            </td>
            <td>
                <a href="{{ route('admin.categories.edit', $cat->_id ?? $cat->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                <form action="{{ route('admin.categories.destroy', $cat->_id ?? $cat->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>

</table>

<!-- Phân trang -->
<div class="d-flex justify-content-center">
    {{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>


@endsection