<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bank Mini</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100 font-sans">

    <!-- Header -->
    <header class="bg-green-800 text-white py-4 shadow-md">
        <div class="max-w-7xl mx-auto px-8 flex justify-between items-center">
            <h1 class="text-xl font-bold">Bank Mini</h1>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded transition">
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="max-w-7xl mx-auto px-8 py-10 space-y-12">

        <!-- Welcome Message -->
        <section>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang di Bank Mini</h1>
        </section>

        <!-- Form Tambah User & Transaksi -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Form Tambah Siswa -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Tambah User (Siswa)</h2>
                <form action="{{ route('tambah.user') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Input Nama -->
                        <div class="mb-4">
                            <label class="block text-gray-700">Nama</label>
                            <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            @error('name') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Input Email -->
                        <div class="mb-4">
                            <label class="block text-gray-700">Email</label>
                            <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            @error('email') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Input Password -->
                        <div class="mb-4">
                            <label class="block text-gray-700">Password</label>
                            <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            @error('password') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-4">
                            <label class="block text-gray-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                    </div>

                    <!-- Tombol Tambah -->
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500 transition">
                        Tambah Siswa
                    </button>
                </form>
            </div>

            <!-- Form Transaksi -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Transaksi</h2>
                <form action="{{ route('transaction.store') }}" method="POST">
                    @csrf

                    <!-- User ID -->
                    <div class="mb-4">
                        <label class="block text-gray-700">User ID</label>
                        <input type="text" name="user_id" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>

                    <!-- Jenis Transaksi -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Jenis Transaksi</label>
                        <select name="type" class="w-full px-4 py-2 border rounded-lg" required>
                            <option value="top_up">Top Up</option>
                            <option value="withdraw">Tarik Tunai</option>
                        </select>
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Jumlah</label>
                        <input type="number" name="amount" class="w-full px-4 py-2 border rounded-lg" min="1000" required>
                    </div>

                    <!-- Tombol Kirim -->
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        Kirim
                    </button>
                </form>
            </div>
        </section>

        <!-- Daftar Transaksi Pending -->
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Transaksi Pending</h2>
            <div class="bg-white p-6 rounded shadow overflow-x-auto">
                @php
                    $pendingTransactions = $transactions->where('status', 'pending');
                @endphp

                @if($pendingTransactions->isNotEmpty())
                    <table class="min-w-full border border-gray-200 text-center">
                        <thead class="bg-green-200">
                            <tr>
                                <th class="border px-4 py-2">Nama</th>
                                <th class="border px-4 py-2">Jenis</th>
                                <th class="border px-4 py-2">Jumlah</th>
                                <th class="border px-4 py-2">Waktu</th>
                                <th class="border px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingTransactions as $index => $trx)
                            <tr class="{{ $index % 2 == 1 ? 'bg-green-50' : '' }}">
                                <td class="border px-4 py-2">{{ $trx->user->name }}</td>
                                <td class="border px-4 py-2">{{ ucfirst($trx->type) }}</td>
                                <td class="border px-4 py-2">Rp{{ number_format($trx->amount, 0, ',', '.') }}</td>
                                <td class="border px-4 py-2">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                <td class="border px-4 py-2">
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('transactions.approve', $trx->id) }}" method="POST">
                                            @csrf
                                            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">ACC</button>
                                        </form>
                                        <form action="{{ route('transactions.reject', $trx->id) }}" method="POST">
                                            @csrf
                                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center text-gray-500 mt-4 italic">Tidak ada transaksi pending.</p>
                @endif
            </div>
        </section>

        <!-- Data Siswa -->
        <section>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Data Siswa</h2>
            <div class="bg-white p-6 rounded shadow overflow-x-auto">
                @if($users->isNotEmpty())
                    <table class="min-w-full border border-gray-200 text-center">
                        <thead class="bg-green-200">
                            <tr>
                                <th class="border px-4 py-2">User ID</th>
                                <th class="border px-4 py-2">Nama</th>
                                <th class="border px-4 py-2">Email</th>
                                <th class="border px-4 py-2">Saldo</th>
                                <th class="border px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                            <tr class="{{ $index % 2 == 1 ? 'bg-green-50' : '' }}">
                                <td class="border px-4 py-2">{{ $user->id }}</td>
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">Rp{{ number_format($user->balance ?? 0, 0, ',', '.') }}</td>
                                <td class="border px-4 py-2">
                                    <form action="{{ route('hapus.user', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 text-sm font-semibold text-green-900 bg-green-200 rounded hover:bg-green-300 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center text-gray-500 mt-4 italic">Tidak ada data user.</p>
                @endif
            </div>
        </section>

        <!-- Riwayat Transaksi -->
<section>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h2>
        <a href="{{ route('admin.cetak.transaksi') }}" class="bg-yellow-400 text-black px-4 py-2 rounded hover:bg-yellow-500">
            Cetak PDF
        </a>
    </div>
    <div class="bg-white p-6 rounded shadow overflow-x-auto">
        @if($transactions->isNotEmpty())
            <table class="min-w-full border border-gray-200 text-center">
                <thead class="bg-green-200">
                    <tr>
                        <th class="px-4 py-2 border">User ID</th>
                        <th class="px-4 py-2 border">Nama</th>
                        <th class="px-4 py-2 border">Jenis Transaksi</th>
                        <th class="px-4 py-2 border">Jumlah</th>
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $index => $transaction)
                    <tr class="{{ $index % 2 == 1 ? 'bg-green-50' : '' }}">
                        <td class="border px-4 py-2">{{ $transaction->user->id ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $transaction->user->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</td>
                        <td class="border px-4 py-2">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                        <td class="border px-4 py-2">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                        <td class="border px-4 py-2">
                            @php
                                $status = $transaction->status;
                                $badgeColor = match($status) {
                                    'approved' => 'bg-green-200 text-green-800',
                                    'pending' => 'bg-yellow-200 text-yellow-800',
                                    'rejected' => 'bg-red-200 text-red-800',
                                    default => 'bg-gray-200 text-gray-800',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $badgeColor }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="border px-4 py-2">
                            <form action="{{ route('hapus.transaksi', $transaction->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1 text-sm font-semibold text-green-900 bg-green-200 rounded hover:bg-green-300 transition">
                                    Hapus
                                </button>
                            </form>
                        </td>                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-gray-500 mt-4 italic">Tidak ada transaksi ditemukan.</p>
        @endif
    </div>
</section>


    </main>

    <!-- Footer -->
    <footer class="bg-green-800 text-white text-center py-4 mt-12">
        <p>&copy; {{ date('Y') }} Bank Mini Sekolah. All rights reserved.</p>
    </footer>

</body>
</html>
