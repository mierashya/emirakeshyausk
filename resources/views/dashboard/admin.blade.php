<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Tambah User</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100 font-sans">

  <!-- Header -->
  <header class="bg-green-800 text-white py-4 shadow-md">
    <div class="max-w-7xl mx-auto w-full px-8 flex justify-between items-center">
      <h1 class="text-xl font-bold">Admin Panel</h1>
      <form action="{{ url('/logout') }}" method="POST">
        @csrf
        <button type="submit" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded transition">Logout</button>
      </form>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-7xl mx-auto w-full px-8 py-10 space-y-12">

    <!-- Tambah User -->
    <section>
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Tambah User</h2>

      @if (session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded-lg mb-4">
          {{ session('success') }}
        </div>
      @endif

      <div class="bg-white p-6 rounded shadow">
        <form action="{{ route('admin.tambah.user') }}" method="POST" class="grid lg:grid-cols-2 gap-6">
          @csrf
          <div>
            <label class="block text-gray-700 mb-1">Nama</label>
            <input type="text" name="name" class="w-full px-4 py-2 border rounded" required>
          </div>
          <div>
            <label class="block text-gray-700 mb-1">Email</label>
            <input type="email" name="email" class="w-full px-4 py-2 border rounded" required>
          </div>
          <div>
            <label class="block text-gray-700 mb-1">Password</label>
            <input type="password" name="password" class="w-full px-4 py-2 border rounded" required>
          </div>
          <div>
            <label class="block text-gray-700 mb-1">Role</label>
            <select name="role" class="w-full px-4 py-2 border rounded">
              <option value="admin">Admin</option>
              <option value="bankmini">Bank Mini</option>
              <option value="siswa">Siswa</option>
            </select>
          </div>
          <div class="col-span-2">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">Tambah</button>
          </div>
        </form>
      </div>
    </section>

    <!-- Daftar Pengguna -->
<section>
  <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Pengguna</h2>
  <div class="bg-white p-6 rounded shadow overflow-x-auto">
    <table class="min-w-full border border-gray-200">
      <thead class="bg-green-200 text-left">
        <tr>
          <th class="px-4 py-2 border text-center">User ID</th> {{-- Ubah label kolom jika mau --}}
          <th class="px-4 py-2 border text-center">Nama</th>
          <th class="px-4 py-2 border text-center">Email</th>
          <th class="px-4 py-2 border text-center">Role</th>
          <th class="px-4 py-2 border text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $index => $user)
          <tr class="{{ $index % 2 == 0 ? 'bg-green-50' : '' }}">
            <td class="px-4 py-2 border text-center">{{ $user->id }}</td>
            <td class="px-4 py-2 border text-center">{{ $user->name }}</td>
            <td class="px-4 py-2 border text-center">{{ $user->email }}</td>
            <td class="px-4 py-2 border text-center capitalize">{{ $user->role }}</td>
            <td class="px-4 py-2 border text-center">
              <form action="{{ route('admin.hapus.user', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
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
    <table class="min-w-full border border-gray-200">
      <thead class="bg-green-200 text-left">
        <tr>
          <th class="px-4 py-2 border text-center">User ID</th>
          <th class="px-4 py-2 border text-center">Nama</th>
          <th class="px-4 py-2 border text-center">Jenis Transaksi</th>
          <th class="px-4 py-2 border text-center">Jumlah</th>
          <th class="px-4 py-2 border text-center">Tanggal</th>
          <th class="px-4 py-2 border text-center">Status</th>
          <th class="px-4 py-2 border text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($transactions as $index => $transaction)
          <tr class="text-center {{ $index % 2 == 1 ? 'bg-green-50' : '' }}">
            <td class="border px-4 py-2 text-center">{{ $transaction->user->id ?? '-' }}</td>
            <td class="border px-4 py-2 text-center">{{ $transaction->user->name ?? '-' }}</td>
            <td class="border px-4 py-2 text-center">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</td>
            <td class="border px-4 py-2 text-center">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
            <td class="border px-4 py-2 text-center">{{ $transaction->created_at->format('Y-m-d') }}</td>
            <td class="border px-4 py-2 text-center">
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
            <td class="border px-4 py-2 text-center">
              <form action="{{ route('admin.hapus.transaksi', $transaction->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
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
  </div>
</section>

</main>

  <!-- Footer -->
  <footer class="bg-green-800 text-white text-center py-4 mt-12">
    <p>&copy; {{ date('Y') }} Bank Mini Sekolah. All rights reserved.</p>
  </footer>

</body>
</html>
