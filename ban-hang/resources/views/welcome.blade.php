@extends('layouts.app')
@section('title', 'Trang chủ')

@section('content')

{{-- Nếu người dùng là admin thì show nội dung admin --}}
@if(session('user_role') === 'admin')
  return redirect()->route('admin.products.index');
@else

@endsection
