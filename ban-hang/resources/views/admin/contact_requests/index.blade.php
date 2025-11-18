@extends('layouts.app')
@section('title','Danh sách liên hệ')

@section('content')
<div class="w-full mx-auto py-12 px-4">
    <h2 class="text-2xl font-semibold mb-6">Danh sách yêu cầu liên hệ</h2>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if($contacts->count() > 0)
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">#</th>
                <th class="border px-4 py-2">Họ và tên</th>
                <th class="border px-4 py-2">Số điện thoại</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Nội dung</th>
                <th class="border px-4 py-2">Ngày gửi</th>
                <th class="border px-4 py-2">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
            <tr>
                <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                <td class="border px-4 py-2">{{ $contact->full_name }}</td>
                <td class="border px-4 py-2">{{ $contact->phone }}</td>
                <td class="border px-4 py-2">{{ $contact->email }}</td>
                <td class="border px-4 py-2">{{ $contact->message }}</td>
                <td class="border px-4 py-2">{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                <td class="border px-4 py-2">
                    @if($contact->status !== 'đã liên hệ')
                    <form action="{{ route('contact_requests.contacted', $contact->_id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Liên hệ
                        </button>
                    </form>
                    @else
                    <span class="text-green-600 font-semibold">Đã liên hệ</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $contacts->links() }}
    </div>
    @else
    <p>Chưa có yêu cầu liên hệ nào.</p>
    @endif
</div>
@endsection