<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
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
    </style>
</head>
<body>

<div class="header">
    <h1>🎵 TicketWave</h1>
    <p>Laporan Data Transaksi Konser</p>
    {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB
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
            <th>Jml Tiket</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $k)
        @php
            $tiket = $k->tiket;
            $konser = $tiket ? $tiket->konser : null;
            $harga = $tiket->harga ?? 0;
            $jumlah = $k->jumlah ?? 1;
        @endphp

        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $konser->nama_konser ?? '-' }}</strong></td>
            <td>
                {{ $konser && $konser->tanggal 
                    ? \Carbon\Carbon::parse($konser->tanggal)->translatedFormat('d M Y') 
                    : '-' 
                }}
            </td>
            <td>{{ $konser->lokasi ?? '-' }}</td>
            <td class="price">Rp {{ number_format($harga,0,',','.') }}</td>
            <td>{{ $tiket->kuota ?? '-' }}</td>
            <td>{{ $jumlah }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Total Data: {{ $data->count() }} transaksi &nbsp;|&nbsp; TicketWave &copy; {{ date('Y') }}
</div>

</body>
</html>