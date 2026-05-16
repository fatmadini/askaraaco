@extends('layouts.app')
@section('title', 'Tiket Saya')
@section('page-title', '🎫 Tiket Saya')

@section('content')

@if($transaksis->isEmpty())
    <div style="text-align:center; padding:60px; background:var(--surface); border-radius:16px; border:1px solid var(--border);">
        <div style="font-size:64px; margin-bottom:16px;">🎫</div>
        <h3 style="margin-bottom:10px;">Belum ada tiket</h3>
        <p style="color:var(--muted); margin-bottom:20px;">Anda belum membeli tiket apapun.</p>
        <a href="{{ route('user.events') }}" class="btn btn-accent" style="padding:12px 24px;">🎟️ Beli Tiket Sekarang</a>
    </div>
@else
    <div style="display:flex; flex-direction:column; gap:20px;">
        @foreach($transaksis as $transaksi)
        <div style="background:var(--surface); border:1px solid var(--border); border-radius:16px; overflow:hidden;">
            {{-- HEADER TIKET --}}
            <div style="padding:16px 20px; background:rgba(124,58,237,0.1); border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                <div>
                    <h4 style="font-size:16px; margin-bottom:4px;">{{ $transaksi->tiket->konser->nama_konser ?? '-' }}</h4>
                    <p style="font-size:11px; color:var(--muted);">Kode Transaksi: {{ $transaksi->kode_unik ?? '-' }}</p>
                </div>
                <div>
                    @if($transaksi->status_pembayaran == 'paid')
                        <span style="background:rgba(16,185,129,0.15); color:#10b981; padding:5px 15px; border-radius:20px; font-size:12px; font-weight:600;">✅ Lunas</span>
                    @elseif($transaksi->status_pembayaran == 'pending')
                        <span style="background:rgba(245,158,11,0.15); color:#f59e0b; padding:5px 15px; border-radius:20px; font-size:12px; font-weight:600;">⏳ Menunggu Pembayaran</span>
                    @else
                        <span style="background:rgba(239,68,68,0.15); color:#ef4444; padding:5px 15px; border-radius:20px; font-size:12px; font-weight:600;">❌ Dibatalkan</span>
                    @endif
                </div>
            </div>
            
            {{-- BODY TIKET --}}
            <div style="padding:20px; display:flex; flex-wrap:wrap; gap:20px;">
                {{-- LEFT: INFORMASI TIKET --}}
                <div style="flex:2; min-width:200px;">
                    <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:12px;">
                        <div>
                            <p style="font-size:11px; color:var(--muted); margin-bottom:4px;">Tanggal Konser</p>
                            <p style="font-size:14px; font-weight:500;">
                                📅 {{ \Carbon\Carbon::parse($transaksi->tiket->konser->tanggal ?? now())->translatedFormat('d F Y, H:i') }}
                            </p>
                        </div>
                        <div>
                            <p style="font-size:11px; color:var(--muted); margin-bottom:4px;">Lokasi</p>
                            <p style="font-size:14px; font-weight:500;">
                                📍 {{ $transaksi->tiket->konser->lokasi ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p style="font-size:11px; color:var(--muted); margin-bottom:4px;">Kategori Tiket</p>
                            <p style="font-size:14px; font-weight:500;">
                                @php $kat = $transaksi->tiket->kategori ?? '-'; @endphp
                                @if($kat == 'VIP')
                                    <span style="background:rgba(245,158,11,0.15); color:#f59e0b; padding:3px 10px; border-radius:20px;">⭐ VIP</span>
                                @elseif($kat == 'Regular')
                                    <span style="background:rgba(124,58,237,0.15); color:#a855f7; padding:3px 10px; border-radius:20px;">Regular</span>
                                @else
                                    <span style="background:rgba(16,185,129,0.15); color:#10b981; padding:3px 10px; border-radius:20px;">Economy</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p style="font-size:11px; color:var(--muted); margin-bottom:4px;">Jumlah Tiket</p>
                            <p style="font-size:14px; font-weight:500;">
                                🎫 {{ $transaksi->jumlah }} tiket
                            </p>
                        </div>
                        <div>
                            <p style="font-size:11px; color:var(--muted); margin-bottom:4px;">Total Harga</p>
                            <p style="font-size:16px; font-weight:700; color:var(--gold);">
                                Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p style="font-size:11px; color:var(--muted); margin-bottom:4px;">Metode Pembayaran</p>
                            <p style="font-size:14px; font-weight:500;">
                                @if($transaksi->metode_pembayaran == 'cash') 💵 Cash
                                @elseif($transaksi->metode_pembayaran == 'bank_bca') 🏦 BCA Transfer
                                @elseif($transaksi->metode_pembayaran == 'bank_bri') 🏦 BRI Transfer
                                @elseif($transaksi->metode_pembayaran == 'bank_mandiri') 🏦 Mandiri Transfer
                                @else 📱 QRIS @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                {{-- RIGHT: QR CODE ATAU TOMBOL BAYAR --}}
                <div style="flex:1; min-width:150px; text-align:center; border-left:1px dashed var(--border); padding-left:20px;">
                    @if($transaksi->status_pembayaran == 'paid')
                        <div style="background:white; padding:10px; border-radius:12px; display:inline-block;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode($transaksi->kode_unik) }}" alt="QR Code" style="width:100px; height:100px;">
                        </div>
                        <div style="margin-top:12px;">
                            <a href="{{ route('transaksi.struk', $transaksi->id) }}" class="btn btn-sm btn-success">
                                📄 Download Tiket
                            </a>
                        </div>
                    @else
                        <div style="padding:20px;">
                            <div style="font-size:48px; margin-bottom:10px;">⏳</div>
                            <p style="font-size:13px; color:var(--gold); margin-bottom:15px;">Menunggu Pembayaran</p>
                            <a href="{{ $transaksi->metode_pembayaran == 'qris' ? route('transaksi.qris', $transaksi->id) : route('transaksi.bank', $transaksi->id) }}" class="btn btn-accent">
                                💳 Selesaikan Pembayaran
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

<style>
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
.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}
.btn-accent {
    background: #7c3aed;
    color: white;
}
.btn-accent:hover {
    background: #a855f7;
}
.btn-success {
    background: rgba(16,185,129,0.15);
    color: #10b981;
    border: 1px solid rgba(16,185,129,0.25);
}
</style>
@endsection