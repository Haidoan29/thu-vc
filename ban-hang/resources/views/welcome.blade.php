@extends('layouts.app')
@section('title', 'Trang chủ')

@section('content')

{{-- Nếu người dùng là admin thì show nội dung admin --}}
@if(session('user_role') === 'admin')
    @include('admin.products.index') {{-- đường dẫn view admin --}}
@else
    <p>Chào mừng người dùng bình thường!</p>
@endif

@endsection
