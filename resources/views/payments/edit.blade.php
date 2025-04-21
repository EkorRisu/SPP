@extends('layouts.app')

@section('title', 'Edit Pembayaran')

@section('content')
<div class="container">
    <h2>Edit Pembayaran</h2>
    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
        @csrf
        <!-- Jika menggunakan method POST, Anda bisa menambahkan @method('PUT') jika route menggunakan PUT/PATCH -->
        <div class="mb-3">
            <label class="form-label">Nama Siswa</label>
            <select name="user_id" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $payment->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Jumlah</label>
            <input type="number" name="amount" class="form-control" value="{{ $payment->amount }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="pending" {{ $payment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ $payment->status === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
