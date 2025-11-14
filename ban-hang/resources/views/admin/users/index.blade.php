@extends('layouts.app')
@section('title','Quản lý Users')

@section('content')
<h2>Danh sách Users</h2>
<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th><th>Tên</th><th>Email</th><th>Vai trò</th><th>Phone</th><th>Address</th><th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $index => $user)
        <tr>
            <td>{{ $index+1 }}</td>
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
        <tr><td colspan="7">Chưa có user nào</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
