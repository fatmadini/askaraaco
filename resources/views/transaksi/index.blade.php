@extends('layouts.app')
@section('title', 'Transaksi')
@section('page-title', '💳 Data Transaksi')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>💳 Daftar Transaksi</h4>
        <a href="{{ route('transaksi.create') }}" class="btn btn-accent">+ Beli Tiket</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pembeli</th>
                <th>Konser</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $t)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $t->nama_pembeli }}</strong></td>
                <td>{{ $t->tiket->konser->nama_konser ?? '-' }}</td>
                <td>
                    @php $kat = $t->tiket->kategori ?? '-'; @endphp
                    @if($kat === 'VIP') <span class="badge badge-vip">VIP</span>
                    @elseif($kat === 'Regular') <span class="badge badge-regular">Regular</span>
                    @elseif($kat === 'Economy') <span class="badge badge-economy">Economy</span>
                    @else {{ $kat }} @endif
                </td>
                <td>{{ $t->jumlah }} tiket</td>
                <td class="price">Rp {{ number_format($t->total,0,',','.') }}</td>
                <td>{{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d M Y') }}</td>
                <td>
                    <a href="{{ route('transaksi.struk', $t->id) }}" class="btn btn-sm btn-success">🧾 Struk</a>
                    <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus transaksi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="empty-state">Belum ada transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection