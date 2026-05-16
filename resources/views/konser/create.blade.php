@extends('layouts.app')
@section('title', 'Tambah Konser')
@section('page-title', '🎤 Tambah Konser')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <h4>➕ Form Tambah Konser</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('konser.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Nama Konser</label>
                <input type="text" name="nama_konser" class="form-control" value="{{ old('nama_konser') }}" placeholder="Nama konser..." required>
            </div>
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
            </div>
            <div class="form-group">
                <label>Lokasi Venue</label>
                <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}" placeholder="Nama venue / lokasi detail..." required>
            </div>

            <div class="form-group">
                <label>📍 Kota / Daerah</label>
                <select name="kota" class="form-control" required>
                    <option value="">-- Pilih Kota --</option>
                    <option value="Jakarta" {{ old('kota') == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                    <option value="Bandung" {{ old('kota') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                    <option value="Surabaya" {{ old('kota') == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                    <option value="Yogyakarta" {{ old('kota') == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                    <option value="Semarang" {{ old('kota') == 'Semarang' ? 'selected' : '' }}>Semarang</option>
                    <option value="Medan" {{ old('kota') == 'Medan' ? 'selected' : '' }}>Medan</option>
                    <option value="Palembang" {{ old('kota') == 'Palembang' ? 'selected' : '' }}>Palembang</option>
                    <option value="Makassar" {{ old('kota') == 'Makassar' ? 'selected' : '' }}>Makassar</option>
                    <option value="Malang" {{ old('kota') == 'Malang' ? 'selected' : '' }}>Malang</option>
                    <option value="Bali" {{ old('kota') == 'Bali' ? 'selected' : '' }}>Bali</option>
                    <option value="Lombok" {{ old('kota') == 'Lombok' ? 'selected' : '' }}>Lombok</option>
                    <option value="Ambon" {{ old('kota') == 'Ambon' ? 'selected' : '' }}>Ambon</option>
                    <option value="Papua" {{ old('kota') == 'Papua' ? 'selected' : '' }}>Papua</option>
                    <option value="Aceh" {{ old('kota') == 'Aceh' ? 'selected' : '' }}>Aceh</option>
                    <option value="Padang" {{ old('kota') == 'Padang' ? 'selected' : '' }}>Padang</option>
                    <option value="Pekanbaru" {{ old('kota') == 'Pekanbaru' ? 'selected' : '' }}>Pekanbaru</option>
                    <option value="Banjarmasin" {{ old('kota') == 'Banjarmasin' ? 'selected' : '' }}>Banjarmasin</option>
                    <option value="Pontianak" {{ old('kota') == 'Pontianak' ? 'selected' : '' }}>Pontianak</option>
                    <option value="Manado" {{ old('kota') == 'Manado' ? 'selected' : '' }}>Manado</option>
                    <option value="Kupang" {{ old('kota') == 'Kupang' ? 'selected' : '' }}>Kupang</option>
                </select>
                <small style="color: var(--muted);">Pilih kota dimana konser berlangsung</small>
            </div>
            
            <div class="form-group">
                <label>Harga Dasar (Rp)</label>
                <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" placeholder="150000" required>
            </div>
            <div class="form-group">
                <label>Kuota</label>
                <input type="number" name="kuota" class="form-control" value="{{ old('kuota') }}" placeholder="500" required>
            </div>
            <div class="form-group">
                <label>Foto Konser</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small style="color: var(--muted);">Upload gambar konser (opsional)</small>
            </div>
            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="btn btn-accent">💾 Simpan</button>
                <a href="{{ route('konser.index') }}" class="btn btn-outline">← Kembali</a>
            </div>
        </form>
    </div>
</div>

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
.btn-accent {
    background: #7c3aed;
    color: white;
}
.btn-accent:hover {
    background: #a855f7;
}
.btn-outline {
    background: transparent;
    color: var(--text);
    border: 1px solid var(--border);
}
.btn-outline:hover {
    background: var(--surface2);
}
.form-group {
    margin-bottom: 18px;
}
.form-group label {
    display: block;
    font-size: 13px;
    color: var(--muted);
    margin-bottom: 6px;
    font-weight: 500;
}
.form-control {
    width: 100%;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 11px 14px;
    color: var(--text);
    font-size: 14px;
    outline: none;
}
.form-control:focus {
    border-color: #7c3aed;
}
.card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 20px;
}
.card-header {
    padding: 18px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}
.card-header h4 {
    font-family: 'Syne', sans-serif;
    font-size: 16px;
    font-weight: 700;
    margin: 0;
}
.card-body {
    padding: 20px;
}
</style>
@endsection