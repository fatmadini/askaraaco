@extends('layouts.app')
@section('title', 'Beli Tiket')
@section('page-title', '💳 Beli Tiket')

@section('content')
<div class="card" style="max-width:550px;">
    <div class="card-header">
        <h4>🎫 Form Pembelian Tiket</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('transaksi.store') }}" method="POST" id="formBeli">
            @csrf
            
            <div class="form-group">
                <label>Nama Pembeli</label>
                <input type="text" name="nama_pembeli" class="form-control" value="{{ auth()->user()->name ?? old('nama_pembeli') }}" required>
                <small class="text-muted">Masukkan nama sesuai identitas</small>
            </div>
            
            <div class="form-group">
                <label>Pilih Tiket</label>
                <select name="tiket_id" id="tiketSelect" class="form-control" required onchange="updatePreview()">
                    <option value="">-- Pilih Tiket --</option>
                    @foreach($tikets as $t)
                        <option value="{{ $t->id }}"
                            data-harga="{{ $t->harga }}"
                            data-stok="{{ $t->stok }}"
                            {{ old('tiket_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->konser->nama_konser }} — {{ $t->kategori }} 
                            (Stok: {{ $t->stok }} | Rp {{ number_format($t->harga,0,',','.') }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>Jumlah Tiket</label>
                <input type="number" name="jumlah" id="jumlahInput" class="form-control" value="{{ old('jumlah', 1) }}" min="1" required oninput="updatePreview()">
                <small id="stokInfo" class="text-muted"></small>
            </div>

            {{-- Metode Pembayaran --}}
            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metodeSelect" class="form-control" required onchange="updatePaymentInfo()">
                    <option value="cash">💵 Cash (Tunai)</option>
                    <option value="bank_bca">🏦 Bank BCA</option>
                    <option value="bank_bri">🏦 Bank BRI</option>
                    <option value="bank_mandiri">🏦 Bank Mandiri</option>
                    <option value="qris">📱 QRIS (Scan QR Code)</option>
                </select>
            </div>

            {{-- Informasi Pembayaran --}}
            <div id="paymentInfo" style="background:var(--surface2); border-radius:12px; padding:15px; margin:15px 0;">
                <!-- Informasi akan berubah sesuai pilihan -->
            </div>

            {{-- Preview Harga --}}
            <div style="background:var(--surface2); border-radius:12px; padding:15px; margin:15px 0;">
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <span>Harga per tiket</span>
                    <span id="previewSatuan">Rp 0</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:18px; font-weight:bold;">
                    <span>Total Bayar</span>
                    <span id="previewTotal" style="color:#f59e0b;">Rp 0</span>
                </div>
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" class="btn btn-accent" id="submitBtn">✅ Beli Sekarang</button>
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline">← Kembali</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function updatePreview() {
    const sel = document.getElementById('tiketSelect');
    const opt = sel.options[sel.selectedIndex];
    const harga = opt ? parseInt(opt.getAttribute('data-harga') || 0) : 0;
    const jumlah = parseInt(document.getElementById('jumlahInput').value) || 1;
    const stok = opt ? parseInt(opt.getAttribute('data-stok') || 0) : 0;
    
    const fmt = n => 'Rp ' + n.toLocaleString('id-ID');
    document.getElementById('previewSatuan').textContent = fmt(harga);
    document.getElementById('previewTotal').textContent = fmt(harga * jumlah);
    
    const stokInfo = document.getElementById('stokInfo');
    const submitBtn = document.getElementById('submitBtn');
    
    if (opt && jumlah > stok) {
        stokInfo.innerHTML = '<span style="color:#ef4444;">⚠️ Stok hanya ' + stok + ' tiket!</span>';
        submitBtn.disabled = true;
    } else {
        stokInfo.innerHTML = '✅ Stok tersedia: ' + (stok || 0) + ' tiket';
        submitBtn.disabled = false;
    }
}

function updatePaymentInfo() {
    const metode = document.getElementById('metodeSelect').value;
    const total = document.getElementById('previewTotal').innerHTML;
    const paymentInfoDiv = document.getElementById('paymentInfo');
    
    let infoHtml = '';
    
    switch(metode) {
        case 'cash':
            infoHtml = `
                <div style="display:flex; align-items:center; gap:12px;">
                    <span style="font-size:28px;">💵</span>
                    <div>
                        <strong>Pembayaran Tunai (Cash)</strong>
                        <p style="font-size:12px; color:var(--muted); margin-top:4px;">Bayar langsung ke kasir dengan nominal ${total}</p>
                    </div>
                </div>
            `;
            break;
        case 'bank_bca':
            infoHtml = `
                <div style="display:flex; align-items:center; gap:12px;">
                    <span style="font-size:28px;">🏦</span>
                    <div>
                        <strong>Bank BCA</strong>
                        <p style="font-size:12px; color:var(--muted); margin-top:4px;">Nomor Rekening: <strong>1234567890</strong> a.n TicketWave</p>
                        <p style="font-size:12px; color:var(--muted);">Transfer sebesar ${total} ke rekening di atas</p>
                    </div>
                </div>
            `;
            break;
        case 'bank_bri':
            infoHtml = `
                <div style="display:flex; align-items:center; gap:12px;">
                    <span style="font-size:28px;">🏦</span>
                    <div>
                        <strong>Bank BRI</strong>
                        <p style="font-size:12px; color:var(--muted); margin-top:4px;">Nomor Rekening: <strong>0987654321</strong> a.n TicketWave</p>
                        <p style="font-size:12px; color:var(--muted);">Transfer sebesar ${total} ke rekening di atas</p>
                    </div>
                </div>
            `;
            break;
        case 'bank_mandiri':
            infoHtml = `
                <div style="display:flex; align-items:center; gap:12px;">
                    <span style="font-size:28px;">🏦</span>
                    <div>
                        <strong>Bank Mandiri</strong>
                        <p style="font-size:12px; color:var(--muted); margin-top:4px;">Nomor Rekening: <strong>1122334455</strong> a.n TicketWave</p>
                        <p style="font-size:12px; color:var(--muted);">Transfer sebesar ${total} ke rekening di atas</p>
                    </div>
                </div>
            `;
            break;
        case 'qris':
            infoHtml = `
                <div style="display:flex; align-items:center; gap:12px;">
                    <span style="font-size:28px;">📱</span>
                    <div>
                        <strong>QRIS (Scan QR Code)</strong>
                        <p style="font-size:12px; color:var(--muted); margin-top:4px;">Scan QR code menggunakan mobile banking atau e-wallet</p>
                        <p style="font-size:12px; color:var(--muted);">Nominal: ${total}</p>
                    </div>
                </div>
            `;
            break;
    }
    
    paymentInfoDiv.innerHTML = infoHtml;
}

updatePreview();
updatePaymentInfo();

document.getElementById('tiketSelect').addEventListener('change', updatePreview);
document.getElementById('jumlahInput').addEventListener('input', updatePreview);
document.getElementById('metodeSelect').addEventListener('change', updatePaymentInfo);
</script>
@endpush
@endsection