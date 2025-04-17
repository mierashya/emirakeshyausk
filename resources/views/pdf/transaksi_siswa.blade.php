<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi - {{ $siswa->name }}</title>
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
        p {
            margin: 2px 0;
        }
        .info {
            margin-bottom: 15px;
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
            padding: 8px;
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
    <h2>Laporan Transaksi - {{ $siswa->name }}</h2>

    <div class="info">
        <p><strong>Nama:</strong> {{ $siswa->name }}</p>
        <p><strong>Email:</strong> {{ $siswa->email }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Penerima</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ ucfirst($transaction->type) }}</td>
                    <td>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        @if($transaction->type == 'transfer' && $transaction->recipient)
                            {{ $transaction->recipient->name }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh sistem pada {{ now()->format('d M Y, H:i') }}
    </div>
</body>
</html>
