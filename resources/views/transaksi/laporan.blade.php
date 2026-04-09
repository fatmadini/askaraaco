@extends('layouts.app')
@section('title', 'Laporan Transaksi')
@section('page-title', '📊 Laporan Transaksi')

@section('content')

{{-- Summary Cards --}}
<div class="stats-grid" style="margin-bottom:24px;">
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
        <div class="stat-value" style="font-size:15px;color:var(--gold);">
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

{{-- Tabel Laporan --}}
<div class="card">
    <div class="card-header">
        <h4>📊 Rincian Laporan Transaksi</h4>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('transaksi.pdf') }}" class="btn btn-pdf">📄 Export PDF</a>
            <a href="{{ route('transaksi.create') }}" class="btn btn-accent">+ Transaksi Baru</a>
        </div>
    </div>
    <table>
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
                    @if($kat === 'VIP') <span class="badge badge-vip">VIP</span>
                    @elseif($kat === 'Regular') <span class="badge badge-regular">Regular</span>
                    @elseif($kat === 'Economy') <span class="badge badge-economy">Economy</span>
                    @else {{ $kat }} @endif
                </td>
                <td>{{ $t->jumlah }} tiket</td>
                <td class="price">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d M Y') }}</td>
                <td>
                    <a href="{{ route('transaksi.struk', $t->id) }}" class="btn btn-sm btn-success">🧾 Struk</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="empty-state">
                    <div style="font-size:36px;margin-bottom:8px;">📊</div>
                    <p>Belum ada data transaksi.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($data->count() > 0)
    {{-- Footer Total --}}
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;align-items:center;gap:24px;background:var(--surface2);">
        <span style="font-size:13px;color:var(--muted);">
            {{ $data->count() }} transaksi &bull; {{ $data->sum('jumlah') }} tiket terjual
        </span>
        <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:var(--gold);">
            Total: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </div>
    </div>
    @endif
</div>

@endsection