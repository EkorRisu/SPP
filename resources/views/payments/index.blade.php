@extends('layouts.app')

@section('title', 'Pembayaran SPP')

@section('content')
<div class="container">
    <h2 class="my-4">Pembayaran SPP</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Jumlah</th>
                <th>Tanggal Bayar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $index => $payment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $payment->user->name }}</td>
                <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                <td>{{ $payment->tanggal_bayar }}</td>
                <td>{{ ucfirst($payment->status) }}</td>
                <td>
                    @if ($payment->status === 'pending')
                        <form action="{{ route('payments.verify', $payment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">Verifikasi</button>
                        </form>
                    @endif

                    @if ($payment->status === 'verified')
                        <a href="{{ route('payments.receipt', $payment->id) }}" class="btn btn-primary">Unduh PDF</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
