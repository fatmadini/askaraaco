@extends('layouts.app')
@section('title', 'Data Tiket')
@section('page-title', '🎫 Data Tiket')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>🎫 Daftar Tiket</h4>
        <a href="{{ route('tiket.create') }}" class="btn btn-accent">+ Tambah Tiket</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Konser</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tiket as $i => $t)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $t->konser->nama_konser ?? '-' }}</strong></td>
                <td>
                    @if($t->kategori === 'VIP')
                        <span class="badge badge-vip">VIP</span>
                    @elseif($t->kategori === 'Regular')
                        <span class="badge badge-regular">Regular</span>
                    @else
                        <span class="badge badge-economy">Economy</span>
                    @endif
                </td>
                <td class="price">Rp {{ number_format($t->harga, 0, ',', '.') }}</td>
                <td>
                    @if($t->stok == 0)
                        <span style="color:var(--danger);font-weight:600;">Habis</span>
                    @elseif($t->stok <= 10)
                        <span style="color:var(--gold);font-weight:600;">{{ $t->stok }}</span>
                    @else
                        {{ $t->stok }}
                    @endif
                </td>
                <td>
                    <a href="{{ route('tiket.edit', $t->id) }}" class="btn btn-sm btn-gold">✏️ Edit</a>
                    <form action="{{ route('tiket.destroy', $t->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus tiket ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-state">
                    <div style="font-size:36px;margin-bottom:8px;">🎫</div>
                    <p>Belum ada data tiket. <a href="{{ route('tiket.create') }}" style="color:var(--accent2);">Tambah sekarang</a></p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection