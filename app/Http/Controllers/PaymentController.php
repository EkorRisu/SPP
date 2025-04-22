<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf; // Pastikan library DomPDF sudah diinstal
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Tampilkan daftar pembayaran milik user yang sedang login (untuk murid).
     */
    public function index()
    {
        $payments = Payment::with('user')->where('user_id', Auth::id())->get();
        return view('payments.index', compact('payments'));
    }

    /**
     * Tampilkan form tambah pembayaran (untuk murid atau guru).
     */
    public function create()
    {
        $users = [];
        if (Auth::user()->role === 'guru') {
            $users = User::where('role', 'murid')->get(); // Guru dapat memilih murid
        }

        return view('payments.create', compact('users'));
    }

    /**
     * Simpan pembayaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'       => Auth::user()->role === 'guru' ? 'required|exists:users,id' : '', // Validasi hanya untuk guru
            'amount'        => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
        ]);

        Payment::create([
            'user_id'       => Auth::user()->role === 'guru' ? $request->user_id : Auth::id(),
            'amount'        => $request->amount,
            'tanggal_bayar' => $request->tanggal_bayar,
            'status'        => 'pending', // Status default adalah pending
        ]);

        $redirectRoute = Auth::user()->role === 'guru' ? 'payments.manage' : 'payments.index';
        return redirect()->route($redirectRoute)->with('success', 'Pembayaran berhasil disimpan.');
    }

    /**
     * Tampilkan halaman kelola pembayaran (untuk guru).
     */
    public function manage()
    {
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Unauthorized');
        }

        $payments = Payment::with('user')->get();
        return view('payments.manage', compact('payments'));
    }

    /**
     * Tampilkan form edit pembayaran (untuk guru).
     */
    public function edit($id)
    {
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Unauthorized');
        }

        $payment = Payment::findOrFail($id);
        $users = User::where('role', 'murid')->get(); // Hanya murid yang bisa dipilih
        return view('payments.edit', compact('payment', 'users'));
    }

    /**
     * Perbarui data pembayaran (untuk guru).
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'amount'        => 'required|numeric|min:0',
            'status'        => 'required|string|in:pending,verified', // Validasi status
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update([
            'user_id'       => $request->user_id,
            'amount'        => $request->amount,
            'status'        => $request->status,
        ]);

        return redirect()->route('payments.manage')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    /**
     * Hapus pembayaran (untuk guru).
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Unauthorized');
        }

        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('payments.manage')->with('success', 'Pembayaran berhasil dihapus.');
    }

    /**
     * Verifikasi pembayaran (untuk guru).
     */
    public function verify($id)
    {
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Unauthorized');
        }

        $payment = Payment::findOrFail($id);

        // Ubah status menjadi verified
        $payment->update([
            'status' => 'verified',
        ]);

        return redirect()->route('payments.manage')->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Unduh receipt pembayaran dalam format PDF (untuk murid atau guru).
     */
    public function downloadReceipt($id)
    {
        $payment = Payment::with('user')->findOrFail($id);

        // Pastikan hanya pembayaran yang sudah diverifikasi yang bisa diunduh
        if ($payment->status !== 'verified') {
            return redirect()->back()->with('error', 'Receipt hanya tersedia untuk pembayaran yang sudah diverifikasi.');
        }

        // Pastikan hanya murid yang memiliki pembayaran atau guru yang bisa mengakses
        if (Auth::user()->role === 'murid' && $payment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Generate PDF
        $pdf = Pdf::loadView('payments.receipt', compact('payment'));

        // Unduh PDF
        return $pdf->download('receipt-pembayaran.pdf');
    }
}
