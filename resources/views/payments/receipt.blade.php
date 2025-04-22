<!DOCTYPE html>
<html>
<head>
    <title>Receipt Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; }
        .content { margin: 20px; }
    </style>
</head>
<body>
    <h1>Receipt Pembayaran</h1>
    <div class="content">
        <p><strong>Nama Siswa:</strong> {{ $payment->user->name }}</p>
        <p><strong>Jumlah Bayar:</strong> Rp{{ number_format($payment->amount, 0, ',', '.') }}</p>
        <p><strong>Tanggal Bayar:</strong> {{ $payment->tanggal_bayar }}</p>
        <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
    </div>
</body>
</html>