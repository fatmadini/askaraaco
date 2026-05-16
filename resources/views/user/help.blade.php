@extends('layouts.app')
@section('title', 'Bantuan')
@section('page-title', '❓ Pusat Bantuan')

@section('content')
<div class="events-grid" style="grid-template-columns: repeat(2, 1fr);">
    {{-- FAQ Section --}}
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-question-circle"></i> Frequently Asked Questions</h4>
        </div>
        <div class="card-body">
            <div style="margin-bottom: 20px;">
                <h5 style="color: var(--gold); margin-bottom: 8px;">🎫 Cara Beli Tiket</h5>
                <p style="font-size: 13px; color: var(--muted);">1. Pilih event di halaman Event<br>2. Pilih kategori tiket dan jumlah<br>3. Pilih metode pembayaran<br>4. Selesaikan pembayaran<br>5. Tiket akan muncul di "Tiket Saya"</p>
            </div>
            <div style="margin-bottom: 20px;">
                <h5 style="color: var(--gold); margin-bottom: 8px;">💳 Metode Pembayaran</h5>
                <p style="font-size: 13px; color: var(--muted);">Kami menerima pembayaran via:<br>• Cash (Tunai)<br>• Transfer Bank (BCA, BRI, Mandiri)<br>• QRIS (Scan via mobile banking/e-wallet)</p>
            </div>
            <div style="margin-bottom: 20px;">
                <h5 style="color: var(--gold); margin-bottom: 8px;">🔄 Refund & Pembatalan</h5>
                <p style="font-size: 13px; color: var(--muted);">Refund dapat dilakukan maksimal H-7 sebelum event berlangsung. Admin akan memproses refund dalam 3-5 hari kerja.</p>
            </div>
            <div style="margin-bottom: 20px;">
                <h5 style="color: var(--gold); margin-bottom: 8px;">📱 E-Ticket</h5>
                <p style="font-size: 13px; color: var(--muted);">Setelah pembayaran berhasil, e-ticket dengan QR code akan tersedia di menu "Tiket Saya". Scan QR code saat masuk venue.</p>
            </div>
            <div>
                <h5 style="color: var(--gold); margin-bottom: 8px;">📞 Lupa Password?</h5>
                <p style="font-size: 13px; color: var(--muted);">Klik "Lupa Password" di halaman login, atau hubungi admin untuk bantuan lebih lanjut.</p>
            </div>
        </div>
    </div>

    {{-- Contact & Policies --}}
    <div>
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-headset"></i> Hubungi Kami</h4>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 15px;">
                    <i class="fab fa-whatsapp" style="color: #25D366; font-size: 20px;"></i>
                    <strong> WhatsApp:</strong>
                    <p style="margin-top: 5px;">+62 812-3456-7890 (Senin-Minggu, 08:00 - 22:00)</p>
                </div>
                <div style="margin-bottom: 15px;">
                    <i class="fas fa-envelope" style="color: var(--accent2); font-size: 20px;"></i>
                    <strong> Email:</strong>
                    <p style="margin-top: 5px;">support@ticketwave.com</p>
                </div>
                <div>
                    <i class="fab fa-instagram" style="color: #E4405F; font-size: 20px;"></i>
                    <strong> Instagram:</strong>
                    <p style="margin-top: 5px;">@ticketwave.id</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-file-contract"></i> Dokumen Legal</h4>
            </div>
            <div class="card-body">
                <p><a href="#" style="color: var(--accent2);">📜 Syarat & Ketentuan</a></p>
                <p style="margin-top: 10px;"><a href="#" style="color: var(--accent2);">🔒 Kebijakan Privasi</a></p>
                <p style="margin-top: 10px; font-size: 12px; color: var(--muted);">© 2024 TicketWave. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>
@endsection