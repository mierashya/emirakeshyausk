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
    $validated = $request->validate([
        'type' => 'required|in:top_up,withdraw,transfer',
        'amount' => 'required|numeric|min:1000',
        'recipient_id' => 'required_if:type,transfer|nullable|exists:users,id',
    ]);

    DB::beginTransaction();

    try {
        $user = auth()->user(); // Ambil dari login, bukan dari request

        if ($validated['type'] === 'top_up') {
            $user->balance += $validated['amount'];
        } elseif ($validated['type'] === 'withdraw') {
            if ($user->balance < $validated['amount']) {
                return back()->with('error', 'Saldo tidak mencukupi untuk tarik tunai.');
            }
            $user->balance -= $validated['amount'];
        } elseif ($validated['type'] === 'transfer') {
            $recipient = User::findOrFail($validated['recipient_id']);

            if ($user->balance < $validated['amount']) {
                return back()->with('error', 'Saldo tidak mencukupi untuk transfer.');
            }

            $user->balance -= $validated['amount'];
            $user->save();

            $recipient->balance += $validated['amount'];
            $recipient->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => 'transfer',
                'amount' => $validated['amount'],
                'recipient_id' => $validated['recipient_id'],
                'status' => 'success',
            ]);

            DB::commit();
            return back()->with('success', 'Transfer berhasil.');
        }

        $user->save();

        if ($validated['type'] !== 'transfer') {
            Transaction::create([
                'user_id' => $user->id,
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'status' => 'success',
            ]);
        }

        DB::commit();
        return back()->with('success', 'Transaksi berhasil diproses.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Terjadi kesalahan saat memproses transaksi.');
    }
}


}