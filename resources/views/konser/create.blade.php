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
                <label>Lokasi</label>
                <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}" placeholder="Lokasi konser..." required>
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
            </div>
            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="btn btn-accent">💾 Simpan</button>
                <a href="{{ route('konser.index') }}" class="btn btn-outline">← Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection