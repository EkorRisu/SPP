@extends('layouts.app')

@section('title', 'Pembayaran SPP')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h2>Pembayaran SPP</h2>
        <a href="{{ route('payments.create') }}" class="btn btn-primary">Bayar SPP</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>
                        @if ($payment->status === 'pending')
                            <span class="badge bg-warning">Menunggu Konfirmasi</span>
                        @else
                            <span class="badge bg-success">Dikonfirmasi</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
