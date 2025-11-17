@extends('layouts.app')
@section('title','Quản lý Users')

@section('content')
<h2 class="text-2xl mb-4">Danh sách Users</h2>

<div class="d-flex justify-content-between mb-3">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Thêm mới</a>

    <!-- Form tìm kiếm + chọn số lượng phân trang -->
    <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex align-items-center">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Tìm kiếm...">
        <button class="btn btn-outline-success" type="submit">Tìm</button>
        <select name="perPage" class="form-select me-2 ml-2" onchange="this.form.submit()">
            @foreach([5, 10, 15, 20] as $size)
            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                {{ $size }}/trang
            </option>
            @endforeach
        </select>

    </form>
</div>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $index => $user)
        <tr>
            <td>{{ $users->firstItem() + $index }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td>{{ $user->phone ?? '-' }}</td>
            <td>{{ $user->address ?? '-' }}</td>
            <td>
                <a href="{{ route('admin.users.edit', $user->_id ?? $user->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                <form action="{{ route('admin.users.destroy', $user->_id ?? $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Chưa có user nào</td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- Phân trang -->
<div class="d-flex justify-content-center">
    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
@endsection