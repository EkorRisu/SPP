@extends('layouts.app')

@section('title', 'Bayar SPP')

@section('content')
<div class="container">
    <h2>Tambah Pembayaran</h2>
    <form action="{{ route('payments.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Siswa</label>
            <select name="user_id" class="form-control" required>
                <option value="">-- Pilih Siswa --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Jumlah Bayar</label>
            <input type="number" name="amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Bayar</label>
            <input type="date" name="tanggal_bayar" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Buat Pembayaran</button>
    </form>
</div>
@endsection
