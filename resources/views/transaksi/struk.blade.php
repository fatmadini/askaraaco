@extends('layouts.app')
@section('title', 'Struk Transaksi')
@section('page-title', '🧾 Struk & Barcode Tiket')

@push('styles')
<style>
    @media print {
        .sidebar, .topbar, .no-print { display: none !important; }
        .main { margin-left: 0 !important; }
        .content { padding: 0 !important; }
        body { background: white !important; }
        .struk-wrapper { box-shadow: none !important; border: none !important; }
        .qr-code img { print-color-adjust: exact; }
    }
    
    /* Ukuran struk lebih kecil */
    .struk-wrapper {
        max-width: 360px !important;
        margin: 0 auto;
        font-size: 12px;
    }
    
    .struk-wrapper * {
        font-size: inherit;
    }
    
    .barcode-section {
        text-align: center;
        padding: 12px 16px;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 10px;
        margin: 12px;
        border: 1px dashed #dee2e6;
    }
    
    .qr-code {
        background: white;
        padding: 8px;
        border-radius: 12px;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .qr-code img {
        width: 120px !important;
        height: 120px !important;
    }
    
    .barcode-number {
        font-family: 'Courier New', monospace;
        font-size: 10px !important;
        letter-spacing: 1px;
        font-weight: bold;
        background: #e9ecef;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        margin-top: 8px;
    }
    
    .btn-download {
        background: #7c3aed;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        margin: 3px;
        font-size: 11px;
    }
    
    .badge-vip, .badge-regular, .badge-economy {
        padding: 2px 8px;
        border-radius: 16px;
        font-size: 10px;
    }
    
    /* Kompakkan spacing */
    .struk-wrapper .p-20 {
        padding: 12px 16px !important;
    }
    
    .struk-wrapper h2 {
        font-size: 18px !important;
        margin-bottom: 2px !important;
    }
    
    .struk-wrapper .header-struk {
        padding: 16px 20px !important;
    }
    
    .struk-wrapper .info-row {
        margin-bottom: 4px !important;
    }
    
    .struk-wrapper .section-title {
        font-size: 9px !important;
        margin-bottom: 6px !important;
    }
    
    .struk-wrapper .total-amount {
        font-size: 18px !important;
    }
</style>
@endpush

@section('content')
<div class="no-print" style="margin-bottom:16px;display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
    <button onclick="window.print()" class="btn btn-sm btn-accent">🖨️ Cetak Struk</button>
    <button onclick="downloadQRCode()" class="btn-download">📥 Download Barcode</button>
    <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-outline">← Kembali</a>
    <a href="{{ route('transaksi.create') }}" class="btn btn-sm btn-success">+ Baru</a>
</div>

<div class="struk-wrapper">
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:14px;overflow:hidden;">

        {{-- Header Struk Compact --}}
        <div style="background:linear-gradient(135deg,#7c3aed,#a855f7);padding:14px 20px;text-align:center;">
            <div style="font-size:28px;margin-bottom:4px;">🎵</div>
            <h2 style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:white;margin:0;">TicketWave</h2>
            <p style="font-size:9px;color:rgba(255,255,255,0.75);margin:2px 0 0;">Bukti Pembelian Tiket</p>
        </div>

        {{-- Nomor & Status --}}
        <div style="background:rgba(16,185,129,0.08);border-bottom:1px solid var(--border);padding:8px 16px;display:flex;justify-content:space-between;align-items:center;gap:8px;">
            <div>
                <div style="font-size:8px;color:var(--muted);">No. Transaksi</div>
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:11px;">#TXN-{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div>
                <div style="font-size:8px;color:var(--muted);">Kode Unik</div>
                <div style="font-family:monospace;font-size:9px;">{{ substr($data->kode_unik, -8) }}</div>
            </div>
            <span style="background:rgba(16,185,129,0.15);color:#10b981;border:1px solid rgba(16,185,129,0.3);padding:2px 10px;border-radius:16px;font-size:9px;font-weight:600;">✅ Lunas</span>
        </div>

        {{-- Detail Compact - 2 kolom --}}
        <div style="padding:12px 16px;">
            
            {{-- Info Ringkas --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px 12px;margin-bottom:12px;">
                <div>
                    <div style="font-size:8px;color:var(--muted);">Pembeli</div>
                    <div style="font-weight:600;font-size:11px;">{{ $data->nama_pembeli }}</div>
                </div>
                <div>
                    <div style="font-size:8px;color:var(--muted);">Tanggal Beli</div>
                    <div style="font-size:10px;">
                        @php $tanggalBeli = $data->tanggal ?: $data->created_at; @endphp
                        {{ $tanggalBeli ? $tanggalBeli->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' }}
                    </div>
                </div>
                <div>
                    <div style="font-size:8px;color:var(--muted);">Konser</div>
                    <div style="font-weight:500;font-size:10px;">{{ $data->tiket->konser->nama_konser ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size:8px;color:var(--muted);">Tanggal Konser</div>
                    <div style="font-size:10px;">
                        @if($data->tiket && $data->tiket->konser)
                            {{ \Carbon\Carbon::parse($data->tiket->konser->tanggal)->format('d/m/Y') }}
                        @endif
                    </div>
                </div>
            </div>

            <div style="height:1px;background:var(--border);margin:0 0 10px;border-style:dashed;"></div>

            {{-- Info Tiket --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px 12px;margin-bottom:10px;">
                <div>
                    <div style="font-size:8px;color:var(--muted);">Jam / Lokasi</div>
                    <div style="font-size:10px;">
                        @if($data->tiket && $data->tiket->konser && $data->tiket->konser->waktu_mulai)
                            {{ \Carbon\Carbon::parse($data->tiket->konser->waktu_mulai)->format('H:i') }} WIB
                        @else
                            19:00 WIB
                        @endif
                        <br>
                        <span style="font-size:9px;color:var(--muted);">{{ substr($data->tiket->konser->lokasi ?? '-', 0, 25) }}</span>
                    </div>
                </div>
                <div>
                    <div style="font-size:8px;color:var(--muted);">Kategori / Jumlah</div>
                    <div style="font-size:10px;">
                        @php $kat = $data->tiket->kategori ?? '-'; @endphp
                        @if($kat === 'VIP')⭐ VIP
                        @elseif($kat === 'Regular') Regular
                        @else Economy @endif
                        <br>
                        <strong>{{ $data->jumlah }}</strong> tiket
                    </div>
                </div>
                <div>
                    <div style="font-size:8px;color:var(--muted);">Pembayaran</div>
                    <div style="font-size:10px;">
                        @if($data->metode_pembayaran == 'cash') 💵 Cash
                        @elseif($data->metode_pembayaran == 'qris') 📱 QRIS
                        @else 🏦 Transfer @endif
                    </div>
                </div>
                <div>
                    <div style="font-size:8px;color:var(--muted);">Harga / Tiket</div>
                    <div style="font-size:10px;">Rp {{ number_format($data->tiket->harga ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>

            <div style="height:1px;background:var(--border);margin:0 0 10px;"></div>

            {{-- Total --}}
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="font-family:'Syne',sans-serif;font-size:13px;font-weight:700;">Total Bayar</span>
                <span style="font-family:'Syne',sans-serif;font-size:18px;font-weight:800;color:#f59e0b;">
                    Rp {{ number_format($data->total, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- QR CODE SECTION Compact --}}
        <div class="barcode-section">
            <div style="font-size:9px; color:var(--muted); margin-bottom:8px;">
                📱 Scan QR Code Check-in
            </div>
            
            @if($data->status_pembayaran == 'paid')
                @if($data->barcode_path && file_exists(public_path($data->barcode_path)))
                    <div class="qr-code">
                        <img src="{{ asset($data->barcode_path) }}" 
                             alt="QR Code Tiket" 
                             id="qrImage"
                             style="width:100px; height:100px;">
                    </div>
                    <div class="barcode-number">
                        {{ $data->barcode }}
                    </div>
                @else
                    <div class="qr-code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($data->kode_unik) }}" 
                             alt="QR Code" 
                             id="qrImage"
                             style="width:100px; height:100px;">
                    </div>
                    <div class="barcode-number">
                        {{ $data->kode_unik }}
                    </div>
                @endif
                
                <div style="font-size:8px; color:var(--muted); margin-top:10px;">
                    ⚠️ Tunjukkan barcode saat masuk area
                </div>
                <div style="font-size:7px; color:var(--muted); margin-top:3px;">
                    Barcode berlaku 1x scan
                </div>
            @else
                <div style="padding:12px; text-align:center;">
                    <div style="font-size:36px; margin-bottom:6px;">⏳</div>
                    <p style="font-size:10px; color:#f59e0b; margin-bottom:6px;">Menunggu Pembayaran</p>
                    <p style="font-size:9px; color:var(--muted);">Barcode muncul setelah bayar</p>
                    @if($data->metode_pembayaran == 'qris')
                        <a href="{{ route('transaksi.qris', $data->id) }}" class="btn btn-xs btn-accent" style="margin-top:8px;font-size:10px;">
                            📱 Bayar QRIS
                        </a>
                    @elseif($data->metode_pembayaran != 'cash')
                        <a href="{{ route('transaksi.bank', $data->id) }}" class="btn btn-xs btn-accent" style="margin-top:8px;font-size:10px;">
                            🏦 Bayar Transfer
                        </a>
                    @endif
                </div>
            @endif
        </div>

        {{-- Footer Compact --}}
        <div style="background:var(--surface2);padding:8px 16px;text-align:center;border-top:1px dashed var(--border);">
            <p style="font-size:9px;color:var(--muted);margin:0;">Terima kasih!</p>
            <p style="font-size:8px;color:var(--muted);margin:2px 0 0;">Simpan struk sebagai bukti sah</p>
            <div style="margin-top:6px;font-size:8px;letter-spacing:1px;">
                ★ ★ ★ ★ ★
            </div>
        </div>

    </div>
</div>

<script>
function downloadQRCode() {
    const qrImage = document.getElementById('qrImage');
    if (qrImage && qrImage.src) {
        const link = document.createElement('a');
        link.download = 'barcode_tiket_{{ $data->id }}.png';
        link.href = qrImage.src;
        link.click();
    } else {
        alert('Barcode belum tersedia');
    }
}

@if($data->status_pembayaran == 'pending')
setInterval(function() {
    fetch('{{ route("transaksi.check-payment", $data->id) }}')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'paid') {
                location.reload();
            }
        });
}, 5000);
@endif
</script>
@endsection