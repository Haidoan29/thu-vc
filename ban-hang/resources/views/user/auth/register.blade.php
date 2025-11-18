@extends('user.layouts.app')

@section('title', 'Đăng ký')

@section('content')
<div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-96 relative">
        <h2 class="text-2xl font-bold mb-6 text-center">Đăng ký</h2>

        <!-- Thông báo lỗi -->
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Thông báo thành công -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('user.register.post') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Tên</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border p-2 rounded" required>
                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded" required>
                @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold">Mật khẩu</label>
                <input type="password" name="password" class="w-full border p-2 rounded" required>
                @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 mb-3">Đăng ký</button>
        </form>

        <!-- Nút quay về login -->
        <div class="text-center mt-2">
            <span>Đã có tài khoản? </span>
            <a href="{{ route('user.login') }}" class="text-blue-500 hover:underline font-semibold">Đăng nhập</a>
        </div>
    </div>
</div>
@endsection
