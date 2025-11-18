@extends('user.layouts.app')

@section('title', 'Tài khoản của tôi')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-center">Tài khoản của tôi</h2>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Tên</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Mật khẩu mới (nếu muốn đổi)</label>
            <input type="password" name="password" class="w-full border p-2 rounded">
        </div>

        <!-- <div class="mb-4">
            <label class="block font-semibold mb-1">Số điện thoại</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Địa chỉ</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full border p-2 rounded">
        </div> -->

        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Lưu</button>
    </form>
</div>
@endsection
