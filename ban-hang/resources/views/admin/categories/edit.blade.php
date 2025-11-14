@extends('layouts.app')
@section('title','Sửa Category')

@section('content')
<h2>Sửa Category</h2>

<form action="{{ route('admin.categories.update', $category->_id ?? $category->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Tên</label>
        <input type="text" name="name" class="form-control" value="{{ old('name',$category->name) }}" required>
    </div>

    <div class="mb-3">
        <label>Miêu tả</label>
        <textarea name="description" class="form-control">{{ old('description',$category->description) }}</textarea>
    </div>

    <div class="mb-3">
        <label>Parent</label>
        <select name="parent_id" class="form-control">
            <option value="">-- Không có --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->_id ?? $cat->id }}" {{ old('parent_id',$category->parent_id) == ($cat->_id ?? $cat->id) ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Ảnh</label>
        @if($category->image)
            <div class="mb-2">
                <img src="{{ asset('storage/'.$category->image) }}" width="100">
            </div>
        @endif
        <input type="file" name="image" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Cập nhật</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection
