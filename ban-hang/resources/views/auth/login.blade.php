<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white shadow-md rounded-lg p-8 w-96">
    <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

    @if(session('error'))
    <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{  route('admin.login') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full border p-2 rounded" required>
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded" required>
            @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Login</button>
    </form>
</div>

</body>
</html>
