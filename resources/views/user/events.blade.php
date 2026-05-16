@extends('layouts.app')
@section('title', 'Event')
@section('page-title', '🎟️ Temukan Event Favoritmu')

@section('content')

{{-- FILTER SECTION --}}
<div style="background:var(--surface); border:1px solid var(--border); border-radius:14px; padding:20px; margin-bottom:24px;">
    <form method="GET" action="{{ route('user.events') }}" style="display:flex; flex-wrap:wrap; gap:15px; align-items:flex-end;">
        <div style="flex:2; min-width:180px;">
            <label style="font-size:12px; color:var(--muted); display:block; margin-bottom:5px;">🔍 Cari Konser</label>
            <input type="text" name="search" class="form-control" placeholder="Nama konser..." value="{{ request('search') }}" style="width:100%; padding:10px; border-radius:10px; background:var(--surface2); border:1px solid var(--border); color:var(--text);">
        </div>
        <div style="flex:1; min-width:150px;">
            <label style="font-size:12px; color:var(--muted); display:block; margin-bottom:5px;">📍 Filter Kota</label>
            <select name="kota" class="form-control" style="width:100%; padding:10px; border-radius:10px; background:var(--surface2); border:1px solid var(--border); color:var(--text);">
                <option value="">Semua Kota</option>
                @foreach($kotaList as $kota)
                    <option value="{{ $kota }}" {{ request('kota') == $kota ? 'selected' : '' }}>{{ $kota }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex:1; min-width:140px;">
            <label style="font-size:12px; color:var(--muted); display:block; margin-bottom:5px;">📅 Dari Tanggal</label>
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}" style="width:100%; padding:10px; border-radius:10px; background:var(--surface2); border:1px solid var(--border); color:var(--text);">
        </div>
        <div style="flex:1; min-width:140px;">
            <label style="font-size:12px; color:var(--muted); display:block; margin-bottom:5px;">📅 Sampai Tanggal</label>
            <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}" style="width:100%; padding:10px; border-radius:10px; background:var(--surface2); border:1px solid var(--border); color:var(--text);">
        </div>
        <div>
            <button type="submit" style="padding:10px 24px; background:var(--accent); border:none; border-radius:10px; color:white; cursor:pointer;">🔍 Filter</button>
            <a href="{{ route('user.events') }}" style="padding:10px 20px; background:var(--surface2); border:1px solid var(--border); border-radius:10px; color:var(--text); text-decoration:none; display:inline-block; margin-left:8px;">Reset</a>
        </div>
    </form>
</div>

{{-- GROUP BY KOTA --}}
@if($events->isEmpty())
    <div style="text-align:center; padding:60px; background:var(--surface); border-radius:16px;">
        <div style="font-size:64px; margin-bottom:16px;">🎟️</div>
        <h3>Tidak ada konser ditemukan</h3>
        <p style="color:var(--muted);">Coba ubah filter pencarian Anda</p>
    </div>
@else
    @php
        $eventsByCity = $events->groupBy('kota');
    @endphp
    
    @foreach($eventsByCity as $kota => $konserList)
        <div style="margin-bottom:32px;">
            <h3 style="margin-bottom:16px; padding-bottom:8px; border-bottom:2px solid var(--accent); display:inline-block;">
                📍 {{ $kota ?: 'Lainnya' }}
            </h3>
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px,1fr)); gap:24px; margin-top:16px;">
                @foreach($konserList as $event)
                <div style="background:var(--surface); border:1px solid var(--border); border-radius:16px; overflow:hidden; transition:transform 0.2s;">
                    <div style="width:100%; height:180px; overflow:hidden; background:var(--surface2); display:flex; align-items:center; justify-content:center; font-size:48px;">
                        @if($event->foto)
                            <img src="{{ asset('foto/'.$event->foto) }}" style="width:100%; height:100%; object-fit:cover;" alt="">
                        @else
                            🎤
                        @endif
                    </div>
                    <div style="padding:20px;">
                        <h3 style="font-size:18px; margin-bottom:10px;">{{ $event->nama_konser }}</h3>
                        <p style="font-size:13px; color:var(--muted); margin-bottom:5px;">
                            <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y') }}
                        </p>
                        <p style="font-size:13px; color:var(--muted); margin-bottom:5px;">
                            <i class="fas fa-map-marker-alt"></i> {{ $event->lokasi }}
                        </p>
                        <p style="font-size:13px; color:var(--muted); margin-bottom:15px;">
                            <i class="fas fa-city"></i> {{ $event->kota }}
                        </p>
                        
                        <div style="margin-bottom:15px;">
                            @foreach($event->tikets as $tiket)
                                <span style="display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; margin-right:5px; margin-bottom:5px; 
                                    @if($tiket->kategori == 'VIP') background:rgba(245,158,11,0.15); color:#f59e0b;
                                    @elseif($tiket->kategori == 'Regular') background:rgba(124,58,237,0.15); color:#a855f7;
                                    @else background:rgba(16,185,129,0.15); color:#10b981;
                                    @endif">
                                    {{ $tiket->kategori }} (Stok: {{ $tiket->stok }})
                                </span>
                            @endforeach
                        </div>
                        
                        <div style="font-size:20px; font-weight:700; color:var(--gold); margin-bottom:15px;">
                            Rp {{ number_format($event->harga,0,',','.') }}
                        </div>
                        
                        <a href="{{ route('transaksi.create', ['konser_id' => $event->id]) }}" style="display:block; width:100%; padding:12px; background:var(--accent); color:white; text-align:center; border-radius:10px; text-decoration:none; font-weight:600;">
                            🎫 Beli Tiket
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endforeach
    
    <div style="margin-top:30px;">
        {{ $events->links() }}
    </div>
@endif

<style>
    .form-control:focus {
        outline: none;
        border-color: var(--accent);
    }
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        list-style: none;
    }
    .pagination li a, .pagination li span {
        padding: 8px 12px;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        text-decoration: none;
    }
    .pagination li.active span {
        background: var(--accent);
        border-color: var(--accent);
    }
</style>
@endsection