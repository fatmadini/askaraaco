@extends('layouts.app')
@section('title', 'Edit Tiket')
@section('page-title', '✏️ Edit Tiket')

@section('content')
<div class="card" style="max-width:500px;">
    <div class="card-header"><h4>✏️ Edit Data Tiket</h4></div>
    <div class="card-body">
        <form action="{{ route('tiket.update', $tiket->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Konser</label>
                <select name="konser_id" class="form-control" required>
                    @foreach($konser as $k)
                        <option value="{{ $k->id }}" {{ $tiket->konser_id == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_konser }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Kategori Tiket</label>
                <select name="kategori" class="form-control" required>
                    <option value="VIP"      {{ $tiket->kategori === 'VIP'      ? 'selected' : '' }}>VIP</option>
                    <option value="Regular"  {{ $tiket->kategori === 'Regular'  ? 'selected' : '' }}>Regular</option>
                    <option value="Economy"  {{ $tiket->kategori === 'Economy'  ? 'selected' : '' }}>Economy</option>
                </select>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="{{ old('harga', $tiket->harga) }}" min="0" required>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" value="{{ old('stok', $tiket->stok) }}" min="0" required>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-accent">💾 Update</button>
                <a href="{{ route('tiket.index') }}" class="btn btn-outline">← Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection