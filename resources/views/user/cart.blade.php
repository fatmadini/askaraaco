@extends('layouts.app')
@section('title', 'Keranjang')
@section('page-title', '🛒 Keranjang Belanja')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>🛒 Daftar Tiket</h4>
        <a href="{{ route('user.events') }}" class="btn btn-outline">+ Tambah Tiket</a>
    </div>
    <div class="card-body">
        @if(count($cart) > 0)
        <div style="overflow-x: auto;">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="cartBody">
                    @foreach($cart as $index => $item)
                    <tr data-id="{{ $item['tiket_id'] }}">
                        <td>{{ $item['nama_konser'] }}</td>
                        <td><span class="badge badge-regular">{{ $item['kategori'] }}</span></td>
                        <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                        <td>
                            <input type="number" class="cart-qty" data-id="{{ $item['tiket_id'] }}" value="{{ $item['jumlah'] }}" min="1" max="{{ $item['stok'] }}" style="width: 70px; padding: 5px; border-radius: 8px; background: var(--surface2); border: 1px solid var(--border); color: white;">
                        </td>
                        <td class="subtotal">Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-item" data-id="{{ $item['tiket_id'] }}">🗑️ Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                        <td colspan="2"><strong id="cartTotal" style="color: var(--gold); font-size: 20px;">Rp 0</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
            <button id="clearCart" class="btn btn-outline">Kosongkan Keranjang</button>
            <a href="{{ route('user.checkout') }}" class="btn btn-accent">✅ Lanjut ke Pembayaran</a>
        </div>
        @else
        <div class="empty-state">
            <div style="font-size: 48px; margin-bottom: 12px;">🛒</div>
            <p>Keranjang Anda kosong</p>
            <a href="{{ route('user.events') }}" class="btn btn-accent" style="margin-top: 12px;">🎟️ Beli Tiket Sekarang</a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function updateCartDisplay() {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    let total = 0;
    
    cart.forEach(item => {
        total += item.harga * item.jumlah;
    });
    
    document.getElementById('cartTotal').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
    
    // Update cart count di navbar
    let cartCount = cart.reduce((sum, item) => sum + item.jumlah, 0);
    let cartCountSpan = document.getElementById('cartCount');
    if (cartCountSpan) cartCountSpan.textContent = cartCount;
}

function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
    location.reload();
}

// Update quantity
document.querySelectorAll('.cart-qty').forEach(input => {
    input.addEventListener('change', function() {
        let tiketId = parseInt(this.dataset.id);
        let newQty = parseInt(this.value);
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        let index = cart.findIndex(item => item.tiket_id === tiketId);
        
        if (index !== -1 && newQty > 0) {
            cart[index].jumlah = newQty;
            saveCart(cart);
        }
    });
});

// Remove item
document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', function() {
        let tiketId = parseInt(this.dataset.id);
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        cart = cart.filter(item => item.tiket_id !== tiketId);
        saveCart(cart);
    });
});

// Clear cart
document.getElementById('clearCart')?.addEventListener('click', function() {
    if (confirm('Kosongkan semua keranjang?')) {
        localStorage.setItem('cart', '[]');
        location.reload();
    }
});

updateCartDisplay();
</script>
@endpush
@endsection