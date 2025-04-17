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
                <button type="submit" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded transition">Logout</button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-8 py-10 space-y-12">

        <!-- Welcome -->
        <section>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang di Bank Mini</h1>
        </section>

        <!-- Form Transaksi dan Tambah User -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Tambah User -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Tambah User (Siswa)</h2>
                <form action="{{ route('tambah.user') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700">Nama</label>
                            <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            @error('name') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700">Email</label>
                            <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            @error('email') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700">Password</label>
                            <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            @error('password') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500 transition">Tambah Siswa</button>
                </form>
            </div>

            <!-- Transaksi -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Transaksi</h2>
                <form action="{{ route('transaction.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700">User ID</label>
                        <input type="text" name="user_id" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Jenis Transaksi</label>
                        <select name="type" class="w-full px-4 py-2 border rounded-lg" required>
                            <option value="top_up">Top Up</option>
                            <option value="withdraw">Tarik Tunai</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Jumlah</label>
                        <input type="number" name="amount" class="w-full px-4 py-2 border rounded-lg" min="1000" required>
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Kirim</button>
                </form>
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
                                    <td class="border px-4 py-2 text-center">{{ $user->id }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $user->name }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $user->email }}</td>
                                    <td class="border px-4 py-2 text-center">Rp{{ number_format($user->balance ?? 0, 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <form action="{{ route('hapus.user', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">Hapus</button>
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
                <a href="{{ route('admin.cetak.transaksi') }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
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
                                <th class="px-4 py-2 border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $index => $transaction)
                                <tr class="{{ $index % 2 == 1 ? 'bg-green-50' : '' }}">
                                    <td class="border px-4 py-2 text-center">{{ $transaction->user->id ?? '-' }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $transaction->user->name ?? '-' }}</td>
                                    <td class="border px-4 py-2 text-center">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</td>
                                    <td class="border px-4 py-2 text-center">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <form action="{{ route('hapus.transaksi', $transaction->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">Hapus</button>
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
