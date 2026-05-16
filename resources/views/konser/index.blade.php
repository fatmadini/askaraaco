@extends('layouts.app')
@section('title', 'Data Konser')
@section('page-title', '🎤 Data Konser')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>🎤 Daftar Konser</h4>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('konser.pdf') }}" class="btn btn-pdf">📄 Export PDF</a>
            <a href="{{ route('konser.create') }}" class="btn btn-accent">+ Tambah Konser</a>
            <a href="{{ route('konser.excel') }}" class="btn btn-success">📊 Export Excel</a>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Konser</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Kota</th>
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
                    <td>
                        @if($k->kota)
                            <span style="background:rgba(124,58,237,0.15); color:#a855f7; padding:4px 10px; border-radius:20px; font-size:11px;">
                                📍 {{ $k->kota }}
                            </span>
                        @else
                            <span style="color:var(--danger);">-</span>
                        @endif
                    </td>
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
                    <td colspan="10" class="empty-state">Belum ada data konser</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.table-responsive {
    overflow-x: auto;
}
table {
    width: 100%;
    min-width: 900px;
}
th, td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid var(--border);
}
th {
    background: var(--surface2);
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--muted);
}
.price {
    color: var(--gold);
    font-weight: 600;
}
.empty-state {
    text-align: center;
    padding: 48px;
    color: var(--muted);
}
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
.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}
.btn-accent {
    background: #7c3aed;
    color: white;
}
.btn-accent:hover {
    background: #a855f7;
}
.btn-gold {
    background: rgba(245,158,11,0.2);
    color: #f59e0b;
    border: 1px solid rgba(245,158,11,0.3);
}
.btn-gold:hover {
    background: rgba(245,158,11,0.35);
}
.btn-pdf {
    background: rgba(239,68,68,0.15);
    color: #ef4444;
    border: 1px solid rgba(239,68,68,0.3);
}
.btn-pdf:hover {
    background: rgba(239,68,68,0.3);
}
.btn-success {
    background: rgba(16,185,129,0.15);
    color: #10b981;
    border: 1px solid rgba(16,185,129,0.3);
}
.btn-success:hover {
    background: rgba(16,185,129,0.3);
}
.btn-danger {
    background: rgba(239,68,68,0.15);
    color: #ef4444;
    border: 1px solid rgba(239,68,68,0.25);
}
.btn-danger:hover {
    background: rgba(239,68,68,0.3);
}
.foto-thumb {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    background: var(--surface2);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.foto-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
@endsection