@extends('layouts.app')
@section('title', 'Laporan Per Kota')
@section('page-title', '📊 Laporan Keuangan Per Kota')

@section('content')

{{-- RINGKASAN TOTAL SEMUA KOTA --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:28px;">
    <div style="background:var(--surface); border:1px solid var(--border); border-radius:14px; padding:20px; text-align:center;">
        <div style="font-size:32px; margin-bottom:10px;">💳</div>
        <div style="font-size:12px; color:var(--muted);">Total Transaksi</div>
        <div style="font-size:28px; font-weight:800;">{{ $totalSemua['total_transaksi'] }}</div>
    </div>
    <div style="background:var(--surface); border:1px solid var(--border); border-radius:14px; padding:20px; text-align:center;">
        <div style="font-size:32px; margin-bottom:10px;">🎫</div>
        <div style="font-size:12px; color:var(--muted);">Total Tiket Terjual</div>
        <div style="font-size:28px; font-weight:800;">{{ $totalSemua['total_tiket'] }}</div>
    </div>
    <div style="background:var(--surface); border:1px solid var(--border); border-radius:14px; padding:20px; text-align:center;">
        <div style="font-size:32px; margin-bottom:10px;">💰</div>
        <div style="font-size:12px; color:var(--muted);">Total Pendapatan</div>
        <div style="font-size:20px; font-weight:800; color:var(--gold);">Rp {{ number_format($totalSemua['total_pendapatan'],0,',','.') }}</div>
    </div>
</div>

{{-- LAPORAN PER KOTA --}}
@forelse($laporanPerKota as $kota => $data)
<div style="background:var(--surface); border:1px solid var(--border); border-radius:14px; margin-bottom:24px; overflow:hidden;">
    <div style="padding:16px 20px; background:rgba(124,58,237,0.1); border-bottom:1px solid var(--border);">
        <h3 style="font-size:18px; margin:0;">
            @if(str_contains($kota, 'Perlu Update'))
                ⚠️ {{ $kota }}
            @else
                📍 {{ $kota }}
            @endif
        </h3>
        <div style="display:flex; gap:20px; margin-top:8px; flex-wrap:wrap;">
            <span>💳 {{ $data['total_transaksi'] }} transaksi</span>
            <span>🎫 {{ $data['total_tiket'] }} tiket</span>
            <span>💰 Rp {{ number_format($data['total_pendapatan'],0,',','.') }}</span>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:12px 16px; text-align:left; border-bottom:1px solid var(--border);">No</th>
                    <th style="padding:12px 16px; text-align:left; border-bottom:1px solid var(--border);">Kode Transaksi</th>
                    <th style="padding:12px 16px; text-align:left; border-bottom:1px solid var(--border);">Pembeli</th>
                    <th style="padding:12px 16px; text-align:left; border-bottom:1px solid var(--border);">Konser</th>
                    <th style="padding:12px 16px; text-align:left; border-bottom:1px solid var(--border);">Lokasi Konser</th>
                    <th style="padding:12px 16px; text-align:left; border-bottom:1px solid var(--border);">Jumlah</th>
                    <th style="padding:12px 16px; text-align:left; border-bottom:1px solid var(--border);">Total</th>
                    <th style="padding:12px 16px; text-align:left; border-bottom:1px solid var(--border);">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['transaksis'] as $i => $t)
                <tr>
                    <td style="padding:12px 16px; border-bottom:1px solid var(--border);">{{ $i+1 }}<\/td>
                    <td style="padding:12px 16px; border-bottom:1px solid var(--border);"><small>{{ $t->kode_unik }}</small><\/td>
                    <td style="padding:12px 16px; border-bottom:1px solid var(--border);">{{ $t->nama_pembeli }}<\/td>
                    <td style="padding:12px 16px; border-bottom:1px solid var(--border);">{{ $t->tiket->konser->nama_konser ?? '-' }}<\/td>
                    <td style="padding:12px 16px; border-bottom:1px solid var(--border);">{{ $t->tiket->konser->lokasi ?? '-' }}<\/td>
                    <td style="padding:12px 16px; border-bottom:1px solid var(--border);">{{ $t->jumlah }}<\/td>
                    <td style="padding:12px 16px; border-bottom:1px solid var(--border);" class="price">Rp {{ number_format($t->total,0,',','.') }}<\/td>
                    <td style="padding:12px 16px; border-bottom:1px solid var(--border);">{{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d M Y') }}<\/td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@empty
<div style="text-align:center; padding:60px; background:var(--surface); border-radius:14px;">
    <div style="font-size:48px; margin-bottom:12px;">📊</div>
    <p>Belum ada data transaksi</p>
</div>
@endforelse

<style>
.price {
    color: var(--gold);
    font-weight: 600;
}
</style>
@endsection