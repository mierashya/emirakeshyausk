<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $siswa = Auth::user();

        $transactions = Transaction::with('recipient')
    ->where('user_id', Auth::id())
    ->orWhere('recipient_id', Auth::id()) // agar transaksi masuk dan keluar terlihat
    ->latest()
    ->take(10)
    ->get();


        return view('dashboard.siswa', compact('siswa', 'transactions'));
    }
    public function cetakTransaksiPDF()
{
    $siswa = Auth::user();

    $transactions = Transaction::with('recipient')
        ->where('user_id', $siswa->id)
        ->latest()
        ->get();

    $pdf = Pdf::loadView('pdf.transaksi_siswa', compact('siswa', 'transactions'));
    return $pdf->download('riwayat_transaksi_' . $siswa->name . '.pdf');
}
}
