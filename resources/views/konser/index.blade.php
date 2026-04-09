@extends('layouts.app')
@section('title', 'Data Konser')
@section('page-title', '🎤 Data Konser')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>🎤 Daftar Konser</h4>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('konser.pdf') }}" class="btn btn-pdf">📄 Export PDF</a>
            <a href="{{ route('konser.create') }}" class="btn btn-accent">+ Tambah Konser</a>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama Konser</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Harga</th>
                <th>Kuota</th>
                <th>Tiket</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($konser as $i => $k)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <div class="foto-thumb">
                        @if($k->foto)
                            <img src="{{ asset('foto/'.$k->foto) }}" alt="">
                        @else
                            🎤
                        @endif
                    </div>
                </td>
                <td><strong>{{ $k->nama_konser }}</strong></td>
                <td>{{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d M Y') }}</td>
                <td>{{ $k->lokasi }}</td>
                <td class="price">Rp {{ number_format($k->harga,0,',','.') }}</td>
                <td>{{ $k->kuota }}</td>
                <td>{{ $k->tikets->count() }} jenis</td>
                <td>
                    <a href="{{ route('konser.edit', $k->id) }}" class="btn btn-sm btn-gold">✏️ Edit</a>
                    <form action="{{ route('konser.destroy', $k->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus konser ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="empty-state">Belum ada data konser</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection