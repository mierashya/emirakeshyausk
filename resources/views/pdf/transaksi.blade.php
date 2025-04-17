<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #065f46;
        }
        h2 {
            text-align: center;
            margin-bottom: 10px;
            font-size: 20px;
            color: #047857;
        }
        .info {
            margin-bottom: 15px;
        }
        .info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #d1fae5; /* hijau soft */
            color: #065f46;
            font-weight: bold;
        }
        th, td {
            border: 1px solid #a7f3d0;
            padding: 6px;
            text-align: center;
        }
        tbody tr:nth-child(even) {
            background-color: #ecfdf5; /* hijau sangat lembut */
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-style: italic;
            color: #047857;
        }
    </style>
</head>
<body>
    <h2>Laporan Transaksi</h2>

    <div class="info">
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis Transaksi</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $transaction->user->name ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</td>
                <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh sistem pada {{ now()->format('d M Y, H:i') }}
    </div>
</body>
</html>
