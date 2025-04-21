@extends('layouts.app')

@section('title', 'Selamat Datang')

@section('content')
<div class="text-center">
    <h1>Selamat Datang di Sistem Pembayaran SPP</h1>
    @auth
        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">Masuk ke Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="btn btn-success mt-3">Login</a>
    @endauth
</div>
@endsection
