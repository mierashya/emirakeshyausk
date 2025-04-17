<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;


class AdminController extends Controller
{
    public function tambahUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,bank_mini,siswa',
        ]);

        User::create([
            'name' => $request->name,  // gunakan 'name' bukan 'nama'
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    public function tampilUser()
{
    // Ambil semua data user dari database
    $users = User::all();

    // Kirim data users ke view
    return view('users.index', compact('users'));
}
    public function cetakTransaksiPDF()
{
    $transactions = Transaction::with('user')->orderBy('created_at', 'desc')->get();

    $pdf = Pdf::loadView('pdf.transaksi', compact('transactions'));
    return $pdf->download('laporan_transaksi_admin.pdf');
}

// Hapus user
public function hapusUser($id)
{
    $user = User::findOrFail($id);

    // Opsional: Jika ingin mencegah admin menghapus dirinya sendiri
    if (auth()->id() == $user->id) {
        return redirect()->back()->with('error', 'Tidak bisa menghapus diri sendiri!');
    }

    $user->delete();
    return redirect()->back()->with('success', 'User berhasil dihapus!');
}

// Hapus transaksi
public function hapusTransaksi($id)
{
    $transaksi = Transaction::findOrFail($id);
    $transaksi->delete();
    return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');
}
}