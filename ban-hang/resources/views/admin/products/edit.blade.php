@extends('layouts.app')

@section('content')
<h2 class="text-xl mb-4 font-bold">Chỉnh sửa sản phẩm</h2>

<form action="{{ route('admin.products.update', $product->_id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Tên sản phẩm</label>
    <input type="text" name="name" class="form-control" value="{{ $product->name }}">

    <label>Giá bán</label>
    <input type="number" name="price" class="form-control" value="{{ $product->price }}">

    <label>Danh mục</label>
    <select name="category_id" class="form-control">
        @foreach($categories as $cat)
            <option value="{{ $cat->_id }}" {{ $product->category_id == $cat->_id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>

    <label>Mô tả</label>
    <textarea name="description" class="form-control">{{ $product->description }}</textarea>

    <label>Số lượng tồn</label>
    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}">

    <label>Trạng thái</label>
    <select name="status" class="form-control">
        <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Đang bán</option>
        <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Ngừng bán</option>
    </select>

    <label>Ảnh hiện tại</label><br>
    @if($product->images)
        @foreach($product->images as $img)
            <img src="{{ $img }}" width="60" class="mr-2 mb-2">
        @endforeach
    @endif

    <br><br>
    <label>Tải ảnh mới (có thể chọn nhiều)</label>
    <input type="file" name="images[]" multiple class="form-control">

    <button class="btn btn-primary mt-3">Cập nhật</button>
</form>
@endsection

