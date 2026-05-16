@extends('layouts.app')
@section('title', 'Bank Transfer')
@section('page-title', '🏦 Transfer Bank')

@section('content')
<div class="card" style="max-width:550px; margin:0 auto;">
    <div class="card-header" style="text-align:center;">
        <h4>🏦 {{ $bankInfo['nama'] ?? 'Bank Transfer' }}</h4>
        <p>Transfer ke rekening di bawah ini</p>
    </div>
    <div class="card-body">
        
        <div style="background:var(--surface2); border-radius:12px; padding:15px; margin-bottom:20px;">
            <div style="margin-bottom:10px;">
                <div style="font-size:12px; color:var(--muted);">Nomor Transaksi</div>
                <div style="font-weight:bold; font-size:18px;">{{ $transaksi->kode_unik }}</div>
            </div>
            <div style="margin-bottom:5px;">
                <div style="font-size:12px; color:var(--muted);">Total Pembayaran</div>
                <div style="font-size:28px; font-weight:bold; color:var(--gold);">Rp {{ number_format($transaksi->total,0,',','.') }}</div>
            </div>
        </div>
        
        <div style="background:linear-gradient(135deg, rgba(124,58,237,0.1), rgba(168,85,247,0.1)); border-radius:12px; padding:20px; margin-bottom:20px; text-align:center;">
            <span style="font-size:40px;">🏦</span>
            <div style="margin-top:10px;">
                <div style="font-size:12px; color:var(--muted);">Nomor Rekening</div>
                <div style="font-size:24px; font-weight:bold; letter-spacing:2px;">{{ $bankInfo['no_rekening'] ?? '-' }}</div>
                <div style="margin-top:10px;">
                    <div>a.n <strong>{{ $bankInfo['atas_nama'] ?? 'TicketWave' }}</strong></div>
                    <div style="font-size:12px; color:var(--muted);">{{ $bankInfo['nama'] ?? '-' }} ({{ $bankInfo['kode_bank'] ?? '-' }})</div>
                </div>
            </div>
        </div>
        
        <div style="background:var(--surface2); border-radius:12px; padding:15px; margin-bottom:20px;">
            <p style="font-weight:bold; margin-bottom:10px;">📝 Cara Membayar:</p>
            <ol style="margin-left:20px; font-size:13px; color:var(--muted); line-height:1.8;">
                <li>Buka aplikasi {{ $bankInfo['nama'] ?? 'Bank' }} mobile banking / ATM</li>
                <li>Pilih menu Transfer</li>
                <li>Masukkan nomor rekening: <strong>{{ $bankInfo['no_rekening'] ?? '-' }}</strong></li>
                <li>Masukkan nominal: <strong>Rp {{ number_format($transaksi->total,0,',','.') }}</strong></li>
                <li>Klik tombol "Konfirmasi Pembayaran" setelah transfer berhasil</li>
            </ol>
        </div>
        
        <div style="display:flex; gap:10px; justify-content:center;">
            <a href="{{ route('transaksi.confirm-payment', $transaksi->id) }}" 
               class="btn btn-accent" 
               onclick="return confirm('Apakah sudah melakukan transfer?')">
                ✅ Konfirmasi Pembayaran
            </a>
            <button onclick="window.print()" class="btn btn-outline">🖨️ Cetak</button>
            <a href="{{ route('transaksi.index') }}" class="btn btn-outline">← Kembali</a>
        </div>
        
        <div id="paymentStatus" style="margin-top:15px;"></div>
    </div>
</div>

<script>
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