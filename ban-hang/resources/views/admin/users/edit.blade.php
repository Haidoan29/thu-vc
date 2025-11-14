@extends('layouts.app')
@section('title','Sửa User')

@section('content')
<h2>Sửa User</h2>

<form action="{{ route('admin.users.update', $user->_id ?? $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label>Tên</label>
        <input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}" required>
    </div>
    <div class="mb-3">
        <label>Vai trò</label>
        <select name="role" class="form-control" required>
            <option value="">-- Chọn vai trò --</option>
            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone',$user->phone) }}">
    </div>
    <div class="mb-3">
        <label>Địa chỉ</label>
        <input type="text" name="address" class="form-control" value="{{ old('address',$user->address) }}">
    </div>
    <button type="submit" class="btn btn-success">Cập nhật</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection
