<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
{
    $loggedInUser = auth()->user();
    $isBankMini = $loggedInUser->role === 'bank_mini';
    $isSiswa = $loggedInUser->role === 'siswa';
    
    // Validasi dinamis
    $rules = [
        'type' => 'required|in:top_up,withdraw,transfer',
        'amount' => 'required|numeric|min:1000',
        'recipient_id' => 'required_if:type,transfer|nullable|exists:users,id',
    ];
    
    if ($isSiswa && in_array($request->type, ['top_up', 'withdraw'])) {
        Transaction::create([
            'user_id' => $loggedInUser->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);
    
        return back()->with('success', 'Pengajuan transaksi dikirim. Menunggu persetujuan Bank Mini.');
    }
    
    // Kalau bankmini melakukan topup/withdraw ke siswa, wajib isi user_id
    if ($isBankMini && in_array($request->type, ['top_up', 'withdraw'])) {
        $rules['user_id'] = 'required|exists:users,id';
    }
    
    $validated = $request->validate($rules);

    DB::beginTransaction();

    try {
        // Tentukan target user
        $targetUser = $isBankMini && $request->has('user_id')
            ? User::findOrFail($request->user_id)
            : $loggedInUser;

        // ===== TOP UP =====
        if ($validated['type'] === 'top_up') {
            $targetUser->balance += $validated['amount'];
        }

        // ===== WITHDRAW =====
        elseif ($validated['type'] === 'withdraw') {
            if ($targetUser->balance < $validated['amount']) {
                return back()->with('error', 'Saldo tidak mencukupi untuk tarik tunai.');
            }
            $targetUser->balance -= $validated['amount'];
        }

        // ===== TRANSFER =====
        elseif ($validated['type'] === 'transfer') {
            $recipient = User::findOrFail($validated['recipient_id']);

            if ($loggedInUser->balance < $validated['amount']) {
                return back()->with('error', 'Saldo tidak mencukupi untuk transfer.');
            }

            $loggedInUser->balance -= $validated['amount'];
            $recipient->balance += $validated['amount'];

            $loggedInUser->save();
            $recipient->save();

            Transaction::create([
                'user_id' => $loggedInUser->id,
                'type' => 'transfer',
                'amount' => $validated['amount'],
                'recipient_id' => $validated['recipient_id'],
                'status' => 'success',
            ]);

            DB::commit();
            return back()->with('success', 'Transfer berhasil.');
        }

        $targetUser->save();

        // Catat transaksi top up / withdraw
        if ($validated['type'] !== 'transfer') {
            Transaction::create([
                'user_id' => $targetUser->id,
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'status' => 'success',
            ]);
        }

        DB::commit();
        return back()->with('success', 'Transaksi berhasil diproses.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Terjadi kesalahan saat memproses transaksi.')->with('debug', $e->getMessage());
    }
}

public function approve($id)
{
    $transaction = Transaction::findOrFail($id);
    $transaction->status = 'approved';
    $transaction->save();

    // Update saldo user
    $user = $transaction->user;
    if ($transaction->type == 'top_up') {
        $user->balance += $transaction->amount;
    } elseif ($transaction->type == 'withdraw') {
        if ($user->balance < $transaction->amount) {
            return back()->with('error', 'Saldo siswa tidak mencukupi.');
        }
        $user->balance -= $transaction->amount;
    }
    $user->save();

    return back()->with('success', 'Transaksi disetujui.');
}

public function reject($id)
{
    $transaction = Transaction::findOrFail($id);
    $transaction->status = 'rejected';
    $transaction->save();

    return back()->with('success', 'Transaksi ditolak.');
}

}