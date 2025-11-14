@extends('layouts.app')

@section('content')
<h2 class="text-xl mb-4 font-bold">Thêm sản phẩm mới</h2>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Tên sản phẩm</label>
    <input type="text" name="name" class="form-control">

    <label>Giá bán</label>
    <input type="number" name="price" class="form-control">

    <label>Danh mục</label>
    <select name="category_id" class="form-control">
        <option value="">-- Chọn danh mục --</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->_id }}">{{ $cat->name }}</option>
        @endforeach
    </select>

    <label>Mô tả</label>
    <textarea name="description" class="form-control"></textarea>

    <label>Số lượng tồn</label>
    <input type="number" name="stock" class="form-control">

    <label>Trạng thái</label>
    <select name="status" class="form-control">
        <option value="active">Đang bán</option>
        <option value="inactive">Ngừng bán</option>
    </select>

    <label>Ảnh sản phẩm (chọn nhiều)</label>
    <input type="file" name="images[]" multiple class="form-control">

    <button class="btn btn-success mt-3">Thêm sản phẩm</button>
</form>
@endsection

