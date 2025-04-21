@extends('layouts.app')

@section('title', 'Dashboard Murid')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h2>Dashboard Murid</h2>
            <p>Selamat datang, {{ auth()->user()->name }}!</p>
            <a href="{{ route('payments.index') }}" class="btn btn-success">Bayar SPP</a>
        </div>
    </div>
</div>
@endsection
