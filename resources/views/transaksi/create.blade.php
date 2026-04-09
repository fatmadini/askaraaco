@extends('layouts.app')
@section('title', 'Beli Tiket')
@section('page-title', '💳 Beli Tiket')

@section('content')
<div class="card" style="max-width:520px;">
    <div class="card-header"><h4>🎫 Form Pembelian Tiket</h4></div>
    <div class="card-body">
        <form action="{{ route('transaksi.store') }}" method="POST" id="formBeli">
            @csrf
            <div class="form-group">
                <label>Nama Pembeli</label>
                <input type="text" name="nama_pembeli" class="form-control" value="{{ old('nama_pembeli') }}" placeholder="Nama lengkap pembeli..." required>
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
                            {{ $t->konser->nama_konser }} — {{ $t->kategori }} (Stok: {{ $t->stok }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Jumlah Tiket</label>
                <input type="number" name="jumlah" id="jumlahInput" class="form-control" value="{{ old('jumlah', 1) }}" min="1" required oninput="updatePreview()">
            </div>

            {{-- Preview Harga --}}
            <div style="background:var(--surface2);border-radius:12px;padding:16px;margin-bottom:20px;border:1px solid var(--border);">
                <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--muted);margin-bottom:8px;">
                    <span>Harga per tiket</span>
                    <span id="previewSatuan">Rp 0</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-family:'Syne',sans-serif;font-weight:700;font-size:17px;">
                    <span>Total Bayar</span>
                    <span id="previewTotal" style="color:var(--gold);">Rp 0</span>
                </div>
            </div>

            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-accent">✅ Beli Sekarang</button>
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
    const fmt = n => 'Rp ' + n.toLocaleString('id-ID');
    document.getElementById('previewSatuan').textContent = fmt(harga);
    document.getElementById('previewTotal').textContent = fmt(harga * jumlah);
}
updatePreview();
</script>
@endpush
@endsection