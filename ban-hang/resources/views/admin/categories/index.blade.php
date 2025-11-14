@extends('layouts.app')
@section('title','Danh sách Category')

@section('content')
<h2>Danh sách Category</h2>
<a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th><th>Tên</th><th>Slug</th><th>Parent</th><th>Ảnh</th><th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $index => $cat)
        <tr>
            <td>{{ $index+1 }}</td>
            <td>{{ $cat->name }}</td>
            <td>{{ $cat->slug }}</td>
            <td>{{ $cat->parent_id ? ($categories->firstWhere('_id',$cat->parent_id)->name ?? '-') : '-' }}</td>
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
        @empty
        <tr><td colspan="6">Chưa có category nào</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
