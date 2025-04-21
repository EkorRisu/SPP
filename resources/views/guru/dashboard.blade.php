@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h2>Dashboard Guru</h2>
            <p>Selamat datang, {{ auth()->user()->name }}!</p>
            <a href="{{ route('payments.manage') }}" class="btn btn-primary">Kelola Pembayaran</a>
        </div>
    </div>
</div>
@endsection
