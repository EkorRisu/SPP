<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    // Untuk murid: tampilkan daftar pembayaran milik user yang sedang login
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())->get();
        return view('payments.index', compact('payments'));
    }

    // Untuk murid: tampilkan form tambah pembayaran
    public function create()
    {
        // Jika form ini digunakan oleh guru untuk membuat pembayaran untuk siswa,
        // kita perlu mengambil data user (siswa) untuk dropdown.
        $users = User::all(); // Atau filter jika hanya siswa yang diinginkan, misalnya: User::where('role', 'murid')->get();
        return view('payments.create', compact('users'));
    }

    // Untuk murid: simpan pembayaran baru
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'tanggal_bayar' => 'required|date',
        ]);

        Payment::create([
            'user_id'       => Auth::id(), // Untuk murid, gunakan user yang login
            'amount'        => $request->amount,
            'tanggal_bayar' => $request->tanggal_bayar,
            'status'        => 'pending',
        ]);

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil disimpan.');
    }

    // Untuk guru: tampilkan halaman kelola pembayaran
    public function manage()
    {
        $payments = Payment::with('user')->get();
        return view('payments.manage', compact('payments'));
    }

    // Untuk guru: tampilkan form edit pembayaran
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        // Untuk halaman edit, jika guru dapat mengubah siswa, kirimkan data user
        $users = User::all(); 
        return view('payments.edit', compact('payment', 'users'));
    }

    // Untuk guru: perbarui data pembayaran
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'amount'        => 'required|numeric',
            'status'        => 'required|string',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update([
            'user_id'       => $request->user_id,
            'amount'        => $request->amount,
            'status'        => $request->status,
        ]);

        return redirect()->route('payments.manage')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    // Untuk guru: hapus pembayaran
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('payments.manage')->with('success', 'Pembayaran berhasil dihapus.');
    }
}
