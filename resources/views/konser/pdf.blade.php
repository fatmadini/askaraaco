<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Konser</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #7c3aed;
            padding-bottom: 14px;
        }

        .header h1 {
            font-size: 22px;
            color: #7c3aed;
            margin: 0;
        }

        .header p {
            color: #666;
            margin: 4px 0 0;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th {
            background: #7c3aed;
            color: white;
            padding: 9px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:nth-child(even) td {
            background: #f5f3ff;
        }

        .price {
            color: #7c3aed;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        .summary {
            margin-top: 10px;
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            color: #7c3aed;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>🎵 TicketWave</h1>
    <p>Laporan Data Konser</p>
    <p>{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Konser</th>
            <th>Tanggal</th>
            <th>Lokasi</th>
            <th>Harga</th>
            <th>Kuota</th>
            <th>Jenis Tiket</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $k)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $k->nama_konser }}</strong></td>
            <td>{{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d M Y') }}</td>
            <td>{{ $k->lokasi }}</td>
            <td class="price">Rp {{ number_format($k->harga, 0, ',', '.') }}</td>
            <td>{{ number_format($k->kuota, 0, ',', '.') }}</td>
            <td>
                @php
                    $tikets = $k->tikets->pluck('kategori')->join(', ');
                @endphp
                {{ $tikets ?: '-' }}
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" style="text-align:right; font-weight:bold;">Total Konser:</td>
            <td style="font-weight:bold;">{{ $data->count() }} konser</td>
        </tr>
    </tfoot>
</table>

<div class="summary">
    Total Pendapatan (Dasar): Rp {{ number_format($data->sum('harga'), 0, ',', '.') }}
</div>

<div class="footer">
    Total Data: {{ $data->count() }} konser &nbsp;|&nbsp; TicketWave &copy; {{ date('Y') }}
</div>

</body>
</html>