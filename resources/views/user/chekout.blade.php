@extends('layouts.app')
@section('title', 'Checkout')
@section('page-title', '💳 Checkout')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h4>💳 Detail Pembayaran</h4>
    </div>
    <div class="card-body">
        <div id="checkoutItems" style="margin-bottom: 20px;"></div>
        
        <form action="{{ route('transaksi.store') }}" method="POST" id="checkoutForm">
            @csrf
            <input type="hidden" name="cart_data" id="cartData">
            <input type="hidden" name="total_harga" id="totalHarga">
            
            <div class="form-group">
                <label>Nama Pembeli</label>
                <input type="text" name="nama_pembeli" class="form-control" value="{{ auth()->user()->name }}" required>
            </div>
            
            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metodeSelect" class="form-control" required>
                    <option value="cash">💵 Cash (Tunai)</option>
                    <option value="bank_bca">🏦 Bank BCA</option>
                    <option value="bank_bri">🏦 Bank BRI</option>
                    <option value="bank_mandiri">🏦 Bank Mandiri</option>
                    <option value="qris">📱 QRIS (Scan QR Code)</option>
                </select>
            </div>
            
            <div id="paymentInfo" style="background: var(--surface2); border-radius: 12px; padding: 15px; margin: 15px 0;"></div>
            
            <div style="background: var(--surface2); border-radius: 12px; padding: 15px; margin: 15px 0;">
                <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold;">
                    <span>Total Bayar:</span>
                    <span id="checkoutTotal" style="color: var(--gold);">Rp 0</span>
                </div>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-accent">✅ Bayar Sekarang</button>
                <a href="{{ route('user.cart') }}" class="btn btn-outline">← Kembali ke Keranjang</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let cart = JSON.parse(localStorage.getItem('cart') || '[]');
let total = 0;

function displayCheckoutItems() {
    let html = '<h5>Ringkasan Pesanan:</h5><ul style="margin-top: 10px;">';
    cart.forEach(item => {
        let subtotal = item.harga * item.jumlah;
        total += subtotal;
        html += `<li>${item.nama_konser} - ${item.kategori} (${item.jumlah}x) = Rp ${subtotal.toLocaleString('id-ID')}</li>`;
    });
    html += '</ul>';
    document.getElementById('checkoutItems').innerHTML = html;
    document.getElementById('checkoutTotal').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('totalHarga').value = total;
    document.getElementById('cartData').value = JSON.stringify(cart);
}

function updatePaymentInfo() {
    const metode = document.getElementById('metodeSelect').value;
    const totalFormatted = document.getElementById('checkoutTotal').innerHTML;
    const paymentInfoDiv = document.getElementById('paymentInfo');
    
    let infoHtml = '';
    switch(metode) {
        case 'cash':
            infoHtml = `<div><strong>💵 Pembayaran Tunai</strong><p style="font-size:12px;">Bayar langsung ke kasir dengan nominal ${totalFormatted}</p></div>`;
            break;
        case 'bank_bca':
            infoHtml = `<div><strong>🏦 Bank BCA</strong><p style="font-size:12px;">No Rek: 1234567890 a.n TicketWave<br>Transfer ${totalFormatted}</p></div>`;
            break;
        case 'bank_bri':
            infoHtml = `<div><strong>🏦 Bank BRI</strong><p style="font-size:12px;">No Rek: 0987654321 a.n TicketWave<br>Transfer ${totalFormatted}</p></div>`;
            break;
        case 'bank_mandiri':
            infoHtml = `<div><strong>🏦 Bank Mandiri</strong><p style="font-size:12px;">No Rek: 1122334455 a.n TicketWave<br>Transfer ${totalFormatted}</p></div>`;
            break;
        case 'qris':
            infoHtml = `<div><strong>📱 QRIS</strong><p style="font-size:12px;">Scan QR code menggunakan mobile banking/e-wallet<br>Nominal: ${totalFormatted}</p></div>`;
            break;
    }
    paymentInfoDiv.innerHTML = infoHtml;
}

if (cart.length === 0) {
    window.location.href = "{{ route('user.cart') }}";
}

displayCheckoutItems();
updatePaymentInfo();
document.getElementById('metodeSelect').addEventListener('change', updatePaymentInfo);
</script>
@endpush
@endsection