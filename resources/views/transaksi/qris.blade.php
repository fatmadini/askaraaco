@extends('layouts.app')
@section('title', 'QRIS Payment')

@section('content')
<div class="card" style="max-width:550px; margin:20px auto;">
    <div class="card-header" style="text-align:center;">
        <h4>📱 Scan QRIS untuk Membayar</h4>
    </div>
    <div class="card-body" style="text-align:center;">
        
        {{-- Info Transaksi --}}
        <div style="background:#1c1c2b; border-radius:12px; padding:15px; margin-bottom:20px;">
            <div style="font-size:12px; color:#6b6b8a;">Nomor Transaksi</div>
            <div style="font-weight:bold; font-size:18px;">{{ $transaksi->kode_unik }}</div>
            <hr style="margin:10px 0; border-color:#2a2a3d;">
            <div style="font-size:12px; color:#6b6b8a;">Total Pembayaran</div>
            <div style="font-size:28px; font-weight:bold; color:#f59e0b;">Rp {{ number_format($transaksi->total,0,',','.') }}</div>
            <div style="font-size:12px; color:#6b6b8a; margin-top:5px;">Pembeli: {{ $transaksi->nama_pembeli }}</div>
        </div>
        
        {{-- QR CODE --}}
        <div style="background:white; padding:15px; border-radius:16px; display:inline-block; margin-bottom:20px;">
            <img src="{{ $qrCodePath }}" alt="QRIS" style="width:250px; height:250px; object-fit:contain;">
        </div>
        
        {{-- Cara Bayar --}}
        <div style="background:#1c1c2b; border-radius:12px; padding:15px; margin-bottom:20px; text-align:left;">
            <p style="font-weight:bold; margin-bottom:10px;">📝 Cara Membayar:</p>
            <ol style="margin-left:20px; font-size:13px; color:#6b6b8a; line-height:1.8;">
                <li>Buka mobile banking (BCA/Mandiri/BRI/BNI) atau e-wallet (OVO/GoPay/DANA)</li>
                <li>Pilih menu <strong>Scan QRIS</strong></li>
                <li>Scan QR code di atas</li>
                <li>Pastikan nominal <strong>Rp {{ number_format($transaksi->total,0,',','.') }}</strong></li>
                <li>Konfirmasi pembayaran</li>
                <li>Klik tombol "Konfirmasi Pembayaran" di bawah</li>
            </ol>
        </div>
        
        {{-- Tombol --}}
        <div style="display:flex; gap:10px; justify-content:center;">
            <a href="{{ route('transaksi.confirm-payment', $transaksi->id) }}" 
               class="btn btn-accent" 
               onclick="return confirm('Apakah sudah melakukan pembayaran?')">
                ✅ Konfirmasi Pembayaran
            </a>
            <a href="{{ route('transaksi.index') }}" class="btn btn-outline">← Kembali</a>
        </div>
        
        {{-- Status Checker --}}
        <div id="paymentStatus" style="margin-top:15px;"></div>
    </div>
</div>

<script>
// Cek status pembayaran setiap 5 detik
setInterval(function() {
    fetch('{{ route("transaksi.check-payment", $transaksi->id) }}')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'paid') {
                document.getElementById('paymentStatus').innerHTML = `
                    <div style="background:rgba(16,185,129,0.15); color:#10b981; padding:10px; border-radius:10px;">
                        ✅ Pembayaran berhasil! <a href="{{ route('transaksi.struk', $transaksi->id) }}">Lihat Struk</a>
                    </div>
                `;
            }
        });
}, 5000);
</script>
@endsection