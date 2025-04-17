<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BankMiniController extends Controller
{
    // Fungsi untuk menambah user (siswa)
    public function tambahUser(Request $request)
{
    $validated = $request->validate([   
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'siswa',
        'balance' => 0,
        'is_admin' => false
    ]);

    return redirect()->route('bank.dashboard')->with('success', 'Siswa berhasil ditambahkan!');
}
public function dashboard()
{
    $users = User::where('role', 'siswa')->get(['id', 'name', 'email', 'role', 'balance']);

    $transactions = Transaction::select('id', 'user_id', 'recipient_id', 'type', 'amount', 'status', 'created_at')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('dashboard.bankmini', compact('users', 'transactions'));
}
public function cetakTransaksiPDF()
{
    $transactions = Transaction::with('user')->orderBy('created_at', 'desc')->get();

    $pdf = Pdf::loadView('pdf.transaksi', compact('transactions'));
    return $pdf->download('laporan_transaksi_bankmini.pdf');
}
// Hapus user (siswa)
public function hapusUser($id)
{
    $user = User::findOrFail($id);

    // Pastikan hanya siswa yang bisa dihapus
    if ($user->role === 'siswa') {
        $user->delete();
        return redirect()->back()->with('success', 'Siswa berhasil dihapus!');
    }

    return redirect()->back()->with('error', 'Hanya siswa yang dapat dihapus!');
}
// Hapus transaksi
public function hapusTransaksi($id)
{
    $transaction = Transaction::findOrFail($id);
    $transaction->delete();

    return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');
}
}
