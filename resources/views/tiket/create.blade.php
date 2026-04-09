@extends('layouts.app')
@section('title', 'Tambah Tiket')
@section('page-title', '🎫 Tambah Tiket')

@section('content')
<div class="card" style="max-width:500px;">
    <div class="card-header"><h4>➕ Form Tambah Tiket</h4></div>
    <div class="card-body">
        <form action="{{ route('tiket.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Konser</label>
                <select name="konser_id" class="form-control" required>
                    <option value="">-- Pilih Konser --</option>
                    @foreach($konser as $k)
                        <option value="{{ $k->id }}" {{ old('konser_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_konser }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Kategori Tiket</label>
                <select name="kategori" class="form-control" required>
                    <option value="VIP"      {{ old('kategori') === 'VIP'      ? 'selected' : '' }}>VIP</option>
                    <option value="Regular"  {{ old('kategori') === 'Regular'  ? 'selected' : '' }}>Regular</option>
                    <option value="Economy"  {{ old('kategori') === 'Economy'  ? 'selected' : '' }}>Economy</option>
                </select>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" placeholder="250000" min="0" required>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" value="{{ old('stok') }}" placeholder="100" min="1" required>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-accent">💾 Simpan</button>
                <a href="{{ route('tiket.index') }}" class="btn btn-outline">← Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection