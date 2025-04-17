<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BankMiniController;
use App\Http\Controllers\SiswaController;
use App\Models\User;
use App\Models\Transaction; 
use App\Http\Controllers\TransactionController;
use Barryvdh\DomPDF\Facade\Pdf;


// Route untuk menambah user dari dashboard bank mini
Route::post('/tambah-user', [BankMiniController::class, 'tambahUser'])->name('tambah.user');

// Route GET ke halaman tambah user / dashboard bank mini
Route::get('/tambah-user', [BankMiniController::class, 'dashboard'])->name('bank.dashboard');

// Route dashboard bank mini
Route::get('/bankmini/dashboard', [BankMiniController::class, 'dashboard'])->name('bankmini.dashboard');

// Route ke halaman login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Route untuk proses login
Route::post('/login', [AuthController::class, 'login']);

// Route untuk logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk menambah user dari dashboard admin
Route::post('/admin/tambah-user', [AdminController::class, 'tambahUser'])->name('admin.tambah.user');

// Route transaksi (duplikat, sama seperti baris pertama)
Route::post('/transactions', [TransactionController::class, 'store'])->name('transaction.store');

Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');

Route::get('/bankmini/cetak-transaksi', [BankMiniController::class, 'cetakTransaksiPDF'])->name('bankmini.cetak.transaksi');
Route::get('/admin/cetak-transaksi', [AdminController::class, 'cetakTransaksiPDF'])->name('admin.cetak.transaksi');
Route::get('/siswa/cetak-transaksi', [SiswaController::class, 'cetakTransaksiPDF'])->name('siswa.cetak.transaksi');
// Hapus user
Route::delete('/admin/user/{id}', [AdminController::class, 'hapusUser'])->name('admin.hapus.user');

// Hapus transaksi
Route::delete('/admin/transaksi/{id}', [AdminController::class, 'hapusTransaksi'])->name('admin.hapus.transaksi');
// Hapus siswa
Route::delete('/siswa/{id}', [BankMiniController::class, 'hapusUser'])->name('hapus.user');

// Hapus transaksi
Route::delete('/transaksi/{id}', [BankMiniController::class, 'hapusTransaksi'])->name('hapus.transaksi');





// Route yang hanya bisa diakses oleh user yang sudah login
Route::middleware('auth')->group(function () {
    
    // Route dashboard admin
    Route::get('/admin', function () {
        $users = User::all();
        $transactions = Transaction::with('user')->orderBy('created_at', 'desc')->get();
        return view('dashboard.admin', compact('users', 'transactions'));
    })->name('admin.dashboard');

    // Route dashboard bank mini (duplikat, tapi dalam middleware group)
    Route::get('/bankmini/dashboard', function () {
        $users = User::where('role', 'siswa')->get(['id', 'name', 'email', 'role', 'balance']);
        $transactions = Transaction::select('id', 'user_id', 'recipient_id', 'type', 'amount', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('dashboard.bankmini', compact('users', 'transactions'));
    })->name('bankmini.dashboard');

    Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->middleware('auth')->name('siswa.dashboard');

    
});
