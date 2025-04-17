<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-green-800 text-white py-4 shadow-md">
        <div class="max-w-screen-xl mx-auto px-6 flex items-center justify-between">
            <a href="#" class="text-2xl font-bold tracking-wide">Dashboard Siswa</a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-screen-xl mx-auto px-6 py-12">
        <h1 class="text-3xl font-semibold text-gray-800 mb-2">Selamat Datang, {{ Auth::user()->name }}</h1>
        <p class="text-lg text-gray-600">Halo, <strong>{{ session('user')['email'] ?? 'Siswa' }}</strong>!</p>

        <!-- Saldo dan Transaksi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
            <!-- Saldo Tabungan -->
            <div class="bg-white p-6 shadow-lg rounded-xl flex flex-col justify-between transition hover:shadow-2xl">
                <h5 class="text-xl font-semibold text-gray-700 mb-3">Saldo Tabungan</h5>
                <p class="text-4xl text-green-600 font-bold">
                    Rp{{ number_format($siswa->balance, 0, ',', '.') }}
                </p>
            </div>

            <!-- Form Transaksi -->
            <div class="bg-white p-6 shadow-lg rounded-xl transition hover:shadow-2xl">
                <h5 class="text-xl font-semibold text-gray-700 mb-4">Lakukan Transaksi</h5>
                <form action="{{ route('transaction.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="transaction-type" class="block text-gray-700 font-semibold mb-1">Jenis Transaksi</label>
                        <select id="transaction-type" name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 transition" required>
                            <option value="top_up">Top-up</option>
                            <option value="withdraw">Tarik Tunai</option>
                            <option value="transfer">Transfer Antar Siswa</option>
                        </select>
                    </div>
                    <div>
                        <label for="amount" class="block text-gray-700 font-semibold mb-1">Jumlah Nominal</label>
                        <input type="number" id="amount" name="amount" min="1000" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 transition">
                    </div>

                    <!-- ID Penerima (untuk Transfer) -->
                    <div id="recipient-field" class="hidden">
                        <label for="recipient_id" class="block text-gray-700 font-semibold mb-1">ID Penerima</label>
                        <input type="text" id="recipient_id" name="recipient_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 transition">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition">
                            Proses Transaksi
                        </button>
                    </div>
                </form>

                <script>
                    document.getElementById('transaction-type').addEventListener('change', function () {
                        const recipientField = document.getElementById('recipient-field');
                        recipientField.classList.toggle('hidden', this.value !== 'transfer');
                    });
                </script>
            </div>
        </div>

        <!-- Riwayat Transaksi -->
        <section id="transaction-history" class="mt-12 bg-white p-6 rounded-xl shadow">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h2>
                <a href="{{ route('siswa.cetak.transaksi') }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                    Cetak PDF
                </a>
            </div>

            @if($transactions->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-300 text-sm text-gray-700">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700 font-semibold">
                                <th class="border px-4 py-2">No</th>
                                <th class="border px-4 py-2">Tipe</th>
                                <th class="border px-4 py-2">Jumlah</th>
                                <th class="border px-4 py-2">Tanggal</th>
                                <th class="border px-4 py-2">Penerima</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="{{ $loop->odd ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="border px-4 py-2">{{ ucfirst($transaction->type) }}</td>
                                    <td class="border px-4 py-2">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                    <td class="border px-4 py-2">
                                        @if($transaction->type == 'transfer' && $transaction->recipient)
                                            <span class="inline-block bg-blue-500 text-white px-3 py-1 rounded text-xs font-medium">
                                                {{ $transaction->recipient->name }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-500 mt-4">Tidak ada transaksi.</p>
            @endif
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-green-800 text-white text-center py-4 mt-12 shadow-inner">
        <p>&copy; {{ date('Y') }} Bank Mini Sekolah. All rights reserved.</p>
    </footer>

</body>
</html>
