@extends('layouts.app')
@section('title', 'Laporan Transaksi')
@section('page-title', '📊 Laporan Transaksi')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">💳</div>
        <div class="stat-label">Total Transaksi</div>
        <div class="stat-value">{{ $data->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🎫</div>
        <div class="stat-label">Tiket Terjual</div>
        <div class="stat-value">{{ $data->sum('jumlah') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value" style="font-size:16px;color:var(--gold);">
            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📈</div>
        <div class="stat-label">Rata-rata per Transaksi</div>
        <div class="stat-value" style="font-size:15px;">
            Rp {{ $data->count() > 0 ? number_format($totalPendapatan / $data->count(), 0, ',', '.') : '0' }}
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4>📊 Rincian Laporan Transaksi</h4>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('transaksi.pdf') }}" class="btn btn-pdf">📄 Export PDF</a>
            <a href="{{ route('transaksi.excel') }}" class="btn btn-success">📊 Export Excel</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pembeli</th>
                    <th>Konser</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Total Bayar</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $t->nama_pembeli }}</strong></td>
                    <td>{{ $t->tiket->konser->nama_konser ?? '-' }}</td>
                    <td>
                        @php $kat = $t->tiket->kategori ?? '-'; @endphp
                        @if($kat === 'VIP') 
                            <span class="badge badge-vip">VIP</span>
                        @elseif($kat === 'Regular') 
                            <span class="badge badge-regular">Regular</span>
                        @elseif($kat === 'Economy') 
                            <span class="badge badge-economy">Economy</span>
                        @else 
                            {{ $kat }} 
                        @endif
                    </td>
                    <td>{{ $t->jumlah }} tiket<\/td>
                    <td class="price">Rp {{ number_format($t->total, 0, ',', '.') }}<\/td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d M Y') }}<\/td>
                    <td>
                        <a href="{{ route('transaksi.struk', $t->id) }}" class="btn btn-sm btn-success">🧾 Struk</a>
                    <\/td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty-state">
                        <div style="font-size:36px;margin-bottom:8px;">📊</div>
                        <p>Belum ada data transaksi.</p>
                    <\/td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($data->count() > 0)
<div style="padding:16px 20px;background:var(--surface2);border-radius:14px;display:flex;justify-content:flex-end;align-items:center;gap:24px;margin-top:16px;">
    <span style="font-size:13px;color:var(--muted);">
        {{ $data->count() }} transaksi &bull; {{ $data->sum('jumlah') }} tiket terjual
    </span>
    <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:var(--gold);">
        Total: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
    </div>
</div>
@endif

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 28px;
}
.stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 20px;
    text-align: center;
}
.stat-icon {
    font-size: 32px;
    margin-bottom: 10px;
}
.stat-label {
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 4px;
}
.stat-value {
    font-family: 'Syne', sans-serif;
    font-size: 24px;
    font-weight: 800;
}
.table-responsive {
    overflow-x: auto;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid var(--border);
}
th {
    background: var(--surface2);
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--muted);
}
.price {
    color: var(--gold);
    font-weight: 600;
}
.empty-state {
    text-align: center;
    padding: 48px;
    color: var(--muted);
}
.btn-pdf {
    background: rgba(239,68,68,0.15);
    color: #ef4444;
    border: 1px solid rgba(239,68,68,0.3);
}
.btn-pdf:hover {
    background: rgba(239,68,68,0.3);
}
.btn-success {
    background: rgba(16,185,129,0.15);
    color: #10b981;
    border: 1px solid rgba(16,185,129,0.3);
}
.btn-success:hover {
    background: rgba(16,185,129,0.3);
}
.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}
.btn {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    cursor: pointer;
    border: none;
    font-weight: 500;
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}
.badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
}
.badge-vip {
    background: rgba(245,158,11,0.15);
    color: #f59e0b;
}
.badge-regular {
    background: rgba(124,58,237,0.15);
    color: #a855f7;
}
.badge-economy {
    background: rgba(16,185,129,0.15);
    color: #10b981;
}
</style>
@endsection