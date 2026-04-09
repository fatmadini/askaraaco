@extends('layouts.app')
@section('title', 'Edit Konser')
@section('page-title', '✏️ Edit Konser')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <h4>✏️ Edit Data Konser</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('konser.update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Nama Konser</label>
                <input type="text" name="nama_konser" class="form-control" value="{{ old('nama_konser', $data->nama_konser) }}" required>
            </div>
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $data->tanggal) }}" required>
            </div>
            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $data->lokasi) }}" required>
            </div>
            <div class="form-group">
                <label>Harga Dasar (Rp)</label>
                <input type="number" name="harga" class="form-control" value="{{ old('harga', $data->harga) }}" required>
            </div>
            <div class="form-group">
                <label>Kuota</label>
                <input type="number" name="kuota" class="form-control" value="{{ old('kuota', $data->kuota) }}" required>
            </div>
            <div class="form-group">
                <label>Foto Konser</label>
                @if($data->foto)
                    <div style="margin-bottom:10px;">
                        <img src="{{ asset('foto/'.$data->foto) }}" style="width:100%;max-width:300px;height:150px;object-fit:cover;border-radius:10px;" alt="">
                        <p style="font-size:12px;color:var(--muted);margin-top:6px;">Foto saat ini. Upload baru untuk mengganti.</p>
                    </div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="btn btn-accent">💾 Update</button>
                <a href="{{ route('konser.index') }}" class="btn btn-outline">← Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection