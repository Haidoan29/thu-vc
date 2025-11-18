@extends('user.layouts.app')

@section('title', 'Liên hệ')

@section('content')

<section class="max-w-7xl mx-auto py-12 px-4">

    {{-- TIÊU ĐỀ --}}
    <h2 class="text-3xl font-semibold text-[#687244] mb-10">
        Liên hệ với chúng tôi
    </h2>

    {{-- 3 BOX THÔNG TIN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">

        {{-- Hotline --}}
        <div class="bg-[#f5f2e9] p-10 rounded shadow-sm text-center">
            <div class="bg-[#687244] text-white w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-4">
                <i class="fa-solid fa-phone text-xl"></i>
            </div>
            <h3 class="text-lg font-medium mb-1">Hotline</h3>
            <p class="text-gray-600">1900 6680 - 1900 6680</p>
        </div>

        {{-- Email --}}
        <div class="bg-[#f5f2e9] p-10 rounded shadow-sm text-center">
            <div class="bg-[#687244] text-white w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-4">
                <i class="fa-solid fa-envelope text-xl"></i>
            </div>
            <h3 class="text-lg font-medium mb-1">Email</h3>
            <p class="text-gray-600">contact@sm4s.vn</p>
        </div>

        {{-- Địa Chỉ --}}
        <div class="bg-[#f5f2e9] p-10 rounded shadow-sm text-center">
            <div class="bg-[#687244] text-white w-12 h-12 flex items-center justify-center rounded-full mx-auto mb-4">
                <i class="fa-solid fa-location-dot text-xl"></i>
            </div>
            <h3 class="text-lg font-medium mb-1">Địa chỉ</h3>
            <p class="text-gray-600">
                Tầng 4, Tòa nhà số 97 - 99 Láng Hạ, Đống Đa, Hà Nội (Tòa nhà Petrowaco)
            </p>
        </div>

    </div>

    {{-- FORM + MAP --}}
    <h2 class="text-3xl font-semibold text-[#687244] mb-6">
        Gửi yêu cầu
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        {{-- FORM --}}
        <form action="{{ route('contact_requests.store') }}" method="POST" class="space-y-5">
            @csrf

            <input type="text" name="full_name" placeholder="Họ và tên"
                class="w-full border border-gray-300 px-4 py-3 focus:outline-none"
                value="{{ old('full_name') }}">
            @error('full_name')
            <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <input type="text" name="phone" placeholder="Số điện thoại"
                class="w-full border border-gray-300 px-4 py-3 focus:outline-none"
                value="{{ old('phone') }}">
            @error('phone')
            <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <input type="email" name="email" placeholder="Email"
                class="w-full border border-gray-300 px-4 py-3 focus:outline-none"
                value="{{ old('email') }}">
            @error('email')
            <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <textarea name="message" rows="6" placeholder="Nội dung"
                class="w-full border border-gray-300 px-4 py-3 focus:outline-none">{{ old('message') }}</textarea>
            @error('message')
            <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <button type="submit"
                class="px-8 py-3 bg-[#687244] text-white hover:bg-[#566036] transition">
                Gửi yêu cầu
            </button>
        </form>


        {{-- GOOGLE MAP --}}
        <iframe class="w-full h-[400px] rounded"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.541820304725!2d105.810544!3d21.011208!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab733af59e37%3A0xf06cecf6e19c919!2zQ2h1bmcgY3VyIDk3LTk5IEzDoW5nIEjDoCwgTmfhu41jIELDoCwgSMOgIE7hu5lpIDE1MTMz!5e0!3m2!1svi!2s!4v1700000000000"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
        </iframe>

    </div>

</section>

@endsection

