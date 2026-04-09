@extends('layouts.app')
@section('title', 'Struk Transaksi')
@section('page-title', '🧾 Struk Transaksi')

@push('styles')
<style>
    @media print {
        .sidebar, .topbar, .no-print { display: none !important; }
        .main { margin-left: 0 !important; }
        .content { padding: 0 !important; }
        body { background: white !important; }
        .struk-wrapper { box-shadow: none !important; border: none !important; }
    }
</style>
@endpush

@section('content')
<div class="no-print" style="margin-bottom:20px;display:flex;gap:10px;align-items:center;">
    <button onclick="window.print()" class="btn btn-accent">🖨️ Cetak Struk</button>
    <a href="{{ route('transaksi.index') }}" class="btn btn-outline">← Kembali ke Transaksi</a>
    <a href="{{ route('transaksi.create') }}" class="btn btn-success">+ Transaksi Baru</a>
</div>

<div class="struk-wrapper" style="max-width:440px;margin:0 auto;">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:16px;overflow:hidden;">

        {{-- Header Struk --}}
        <div style="background:linear-gradient(135deg,#7c3aed,#a855f7);padding:28px 24px;text-align:center;">
            <div style="font-size:36px;margin-bottom:8px;">🎵</div>
            <h2 style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:white;margin-bottom:4px;">TicketWave</h2>
            <p style="font-size:12px;color:rgba(255,255,255,0.75);">Bukti Pembelian Tiket Konser</p>
        </div>

        {{-- Nomor & Status --}}
        <div style="background:rgba(16,185,129,0.1);border-bottom:1px solid var(--border);padding:14px 24px;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <div style="font-size:11px;color:var(--muted);margin-bottom:2px;">Nomor Transaksi</div>
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;">#TXN-{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>
            <span style="background:rgba(16,185,129,0.15);color:#10b981;border:1px solid rgba(16,185,129,0.3);padding:4px 14px;border-radius:20px;font-size:12px;font-weight:600;">✅ Lunas</span>
        </div>

        {{-- Detail --}}
        <div style="padding:20px 24px;">

            {{-- Info Pembeli --}}
            <div style="margin-bottom:18px;">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:10px;">Informasi Pembeli</div>
                <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:8px;">
                    <span style="color:var(--muted);">Nama</span>
                    <span style="font-weight:600;">{{ $data->nama_pembeli }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:var(--muted);">Tanggal Beli</span>
                    <span>{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <div style="height:1px;background:var(--border);margin:0 0 18px;border-style:dashed;"></div>

            {{-- Info Konser --}}
            <div style="margin-bottom:18px;">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:10px;">Detail Tiket</div>
                <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:8px;">
                    <span style="color:var(--muted);">Konser</span>
                    <span style="font-weight:600;text-align:right;max-width:220px;">{{ $data->tiket->konser->nama_konser ?? '-' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:8px;">
                    <span style="color:var(--muted);">Tanggal Konser</span>
                    <span>
                        @if($data->tiket && $data->tiket->konser)
                            {{ \Carbon\Carbon::parse($data->tiket->konser->tanggal)->translatedFormat('d F Y') }}
                        @else - @endif
                    </span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:8px;">
                    <span style="color:var(--muted);">Lokasi</span>
                    <span style="text-align:right;max-width:200px;">{{ $data->tiket->konser->lokasi ?? '-' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:8px;">
                    <span style="color:var(--muted);">Kategori Tiket</span>
                    <span>
                        @php $kat = $data->tiket->kategori ?? '-'; @endphp
                        @if($kat === 'VIP')
                            <span class="badge badge-vip">⭐ VIP</span>
                        @elseif($kat === 'Regular')
                            <span class="badge badge-regular">Regular</span>
                        @elseif($kat === 'Economy')
                            <span class="badge badge-economy">Economy</span>
                        @else {{ $kat }} @endif
                    </span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:8px;">
                    <span style="color:var(--muted);">Jumlah</span>
                    <span>{{ $data->jumlah }} tiket</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:var(--muted);">Harga per tiket</span>
                    <span>Rp {{ number_format($data->tiket->harga ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <div style="height:1px;background:var(--border);margin:0 0 18px;"></div>

            {{-- Total --}}
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700;">Total Bayar</span>
                <span style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:#f59e0b;">
                    Rp {{ number_format($data->total, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- Footer Struk --}}
        <div style="background:var(--surface2);padding:16px 24px;text-align:center;border-top:1px dashed var(--border);">
            <p style="font-size:12px;color:var(--muted);margin-bottom:4px;">Terima kasih telah membeli tiket di TicketWave!</p>
            <p style="font-size:11px;color:var(--muted);">Simpan struk ini sebagai bukti pembelian yang sah.</p>
            <div style="margin-top:12px;font-size:10px;color:var(--muted);font-family:monospace;letter-spacing:2px;">
                ★ ★ ★ ★ ★
            </div>
        </div>

    </div>
</div>
@endsection