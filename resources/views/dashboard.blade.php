@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', '🏠 Dashboard')

@section('content')

@if(auth()->user()->isAdmin())
{{-- STATISTIK UNTUK ADMIN --}}
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
        <div class="stat-label">Transaksi Sukses</div>
        <div class="stat-value">{{ $transaksi->where('status_pembayaran', 'paid')->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value" style="font-size:16px;">Rp {{ number_format($totalPendapatan,0,',','.') }}</div>
    </div>
</div>
@endif

<h4 class="fw-bold" style="margin-bottom:16px;">🎤 Konser Tersedia</h4>

@if($konser->isEmpty())
    <div class="empty-state">
        <div style="font-size:48px;margin-bottom:12px;">🎵</div>
        <p>Belum ada konser.</p>
        @if(auth()->user()->isAdmin())
            <a href="{{ url('/konser/create') }}" style="color:var(--accent2);">Tambah konser sekarang</a>
        @endif
    </div>
@else
    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(300px,1fr));gap:20px;">
        @foreach($konser as $k)
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;">
            <div style="width:100%;height:160px;overflow:hidden;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:40px;">
                @if($k->foto)
                    <img src="{{ asset('foto/'.$k->foto) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                @else
                    🎤
                @endif
            </div>
            <div style="padding:16px;">
                <h5 style="font-size:16px;margin-bottom:8px;">{{ $k->nama_konser }}</h5>
                
                {{-- TANGGAL --}}
                <p style="font-size:12px;color:var(--muted);margin-bottom:4px;">
                    <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d F Y') }}
                </p>
                
                {{-- 🆕 TAMBAHKAN JAM KONSER --}}
                <p style="font-size:12px;color:var(--muted);margin-bottom:4px;">
                    <i class="fas fa-clock"></i> 🕐 
                    {{ $k->waktu_mulai ? \Carbon\Carbon::parse($k->waktu_mulai)->format('H:i') : '19:00' }} WIB
                </p>
                
                {{-- LOKASI --}}
                <p style="font-size:12px;color:var(--muted);margin-bottom:4px;">
                    <i class="fas fa-map-marker-alt"></i> {{ $k->lokasi }}
                </p>
                
                {{-- KOTA --}}
                @if($k->kota)
                <p style="font-size:12px;color:var(--muted);margin-bottom:4px;">
                    <i class="fas fa-city"></i> {{ $k->kota }}
                </p>
                @endif
                
                {{-- KUOTA --}}
                <p style="font-size:12px;color:var(--muted);margin-bottom:8px;">
                    <i class="fas fa-ticket-alt"></i> Kuota: {{ $k->kuota }} tiket
                </p>
                
                {{-- HARGA --}}
                <div class="price" style="font-size:18px;margin-top:8px;">
                    Rp {{ number_format($k->harga,0,',','.') }}
                </div>
                
                {{-- TOMBOL BELI (hanya untuk user non-admin) --}}
                @if(!auth()->user()->isAdmin())
                <a href="{{ url('/transaksi/create?konser_id='.$k->id) }}" class="btn btn-accent" style="width:100%;margin-top:12px;">
                    🎫 Beli Tiket
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection

@push('styles')
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
}
.stat-icon {
    font-size: 28px;
    margin-bottom: 10px;
}
.stat-label {
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 4px;
}
.stat-value {
    font-family: 'Syne', sans-serif;
    font-size: 22px;
    font-weight: 800;
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
.fw-bold {
    font-weight: 700;
    font-family: 'Syne', sans-serif;
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
.btn-accent {
    background: #7c3aed;
    color: white;
}
.btn-accent:hover {
    background: #a855f7;
}
</style>
@endpush