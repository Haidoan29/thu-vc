@extends('layouts.app')

@section('content')
<h2 class="text-2xl mb-4">Product List</h2>

<a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">+ Add Product</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Images</th>
            <th>Status</th>
            <th width="180">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $p)
        <tr>
            <td>{{ $p->name }}</td>
            <td>{{ number_format($p->price) }} đ</td>
            <td>
                @if($p->images)
                    @foreach($p->images as $img)
                        <img src="{{ $img }}" width="50">
                    @endforeach
                @else -
                @endif
            </td>
            <td>{{ $p->status }}</td>
            <td>
                <a href="{{ route('admin.products.edit', $p->_id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('admin.products.destroy', $p->_id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Del</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
