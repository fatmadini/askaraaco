@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', '🏠 Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">🎤</div>
        <div class="stat-label">Total Konser</div>
        <div class="stat-value">{{ $konser->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🎫</div>
        <div class="stat-label">Total Tiket</div>
        <div class="stat-value">{{ $konser->sum(fn($k) => $k->tikets->count()) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💳</div>
        <div class="stat-label">Transaksi</div>
        <div class="stat-value">{{ $transaksi->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value" style="font-size:16px;">Rp {{ number_format($totalPendapatan,0,',','.') }}</div>
    </div>
</div>

<h4 class="fw-bold" style="margin-bottom:16px;">🎤 Konser Tersedia</h4>
@if($konser->isEmpty())
    <div class="empty-state">
        <div style="font-size:48px;margin-bottom:12px;">🎵</div>
        <p>Belum ada konser. <a href="/konser/create" style="color:var(--accent2);">Tambah sekarang</a></p>
    </div>
@else
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
        @foreach($konser as $k)
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;">
            <div style="width:100%;height:140px;overflow:hidden;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:40px;">
                @if($k->foto)
                    <img src="{{ asset('foto/'.$k->foto) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                @else
                    🎤
                @endif
            </div>
            <div style="padding:14px;">
                <h5 class="fw-bold" style="font-size:14px;margin-bottom:6px;">{{ $k->nama_konser }}</h5>
                <p style="font-size:12px;color:var(--muted);">📅 {{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d F Y') }}</p>
                <p style="font-size:12px;color:var(--muted);">📍 {{ $k->lokasi }}</p>
                <div class="price" style="margin-top:8px;">Rp {{ number_format($k->harga,0,',','.') }}</div>
                <div style="font-size:11px;color:var(--muted);margin-top:6px;">Kuota: {{ $k->kuota }} tiket</div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection