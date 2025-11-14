@extends('layouts.app')
@section('title','Thêm User mới')

@section('content')
<h2>Thêm User mới</h2>

<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Tên</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
   <div class="mb-3">
        <label>Vai trò</label>
        <select name="role" class="form-control" required>
            <option value="">-- Chọn vai trò --</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
    </div>
    <div class="mb-3">
        <label>Address</label>
        <input type="text" name="address" class="form-control" value="{{ old('address') }}">
    </div>
    <button type="submit" class="btn btn-success">Lưu</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection
 