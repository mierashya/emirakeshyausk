<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BankMiniController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TransactionController;
use App\Models\User;
use App\Models\Transaction;

// Menampilkan form login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Proses login
Route::post('/login', [AuthController::class, 'login']);

// Proses logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard siswa
Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');

// Dashboard bank mini
Route::get('/bankmini/dashboard', [BankMiniController::class, 'dashboard'])->name('bankmini.dashboard');

// Halaman tambah user (dari dashboard bank mini)
Route::get('/tambah-user', [BankMiniController::class, 'dashboard'])->name('bank.dashboard');

// Cetak transaksi oleh bank mini
Route::get('/bankmini/cetak-transaksi', [BankMiniController::class, 'cetakTransaksiPDF'])->name('bankmini.cetak.transaksi');

// Cetak transaksi oleh admin
Route::get('/admin/cetak-transaksi', [AdminController::class, 'cetakTransaksiPDF'])->name('admin.cetak.transaksi');

// Cetak transaksi oleh siswa
Route::get('/siswa/cetak-transaksi', [SiswaController::class, 'cetakTransaksiPDF'])->name('siswa.cetak.transaksi');

// Tambah transaksi baru
Route::post('/transactions', [TransactionController::class, 'store'])->name('transaction.store');

// Menyetujui transaksi (khusus bank mini/admin)
Route::post('/transactions/{id}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');

// Menolak transaksi (khusus bank mini/admin)
Route::post('/transactions/{id}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');

// Tambah user dari dashboard bank mini
Route::post('/tambah-user', [BankMiniController::class, 'tambahUser'])->name('tambah.user');

// Tambah user dari dashboard admin
Route::post('/admin/tambah-user', [AdminController::class, 'tambahUser'])->name('admin.tambah.user');

// Hapus user dari admin
Route::delete('/admin/user/{id}', [AdminController::class, 'hapusUser'])->name('admin.hapus.user');

// Hapus transaksi dari admin
Route::delete('/admin/transaksi/{id}', [AdminController::class, 'hapusTransaksi'])->name('admin.hapus.transaksi');

// Hapus user dari dashboard bank mini
Route::delete('/siswa/{id}', [BankMiniController::class, 'hapusUser'])->name('hapus.user');

// Hapus transaksi dari dashboard bank mini
Route::delete('/transaksi/{id}', [BankMiniController::class, 'hapusTransaksi'])->name('hapus.transaksi');

// ROUTE KHUSUS SETELAH LOGIN

Route::middleware('auth')->group(function () {
    
    // Dashboard admin
    Route::get('/admin', function () {
        $users = User::all();
        $transactions = Transaction::with('user')->orderBy('created_at', 'desc')->get();
        return view('dashboard.admin', compact('users', 'transactions'));
    })->name('admin.dashboard');

    // Dashboard bank mini (dalam grup auth, duplikat aman jika hanya salah satu aktif)
    Route::get('/bankmini/dashboard', function () {
        $users = User::where('role', 'siswa')->get(['id', 'name', 'email', 'role', 'balance']);
        $transactions = Transaction::select('id', 'user_id', 'recipient_id', 'type', 'amount', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('dashboard.bankmini', compact('users', 'transactions'));
    })->name('bankmini.dashboard');

    // Dashboard siswa (sudah ada di luar grup, ini bisa dihapus jika tidak perlu duplikat)
    Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
});
