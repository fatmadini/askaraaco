@extends('layouts.app')
@section('title', 'Transaksi')
@section('page-title', '💳 Data Transaksi')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>💳 Daftar Transaksi</h4>
        <a href="{{ route('transaksi.create') }}" class="btn btn-accent">+ Beli Tiket</a>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Pembeli</th>
                    <th>Konser</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <small style="font-family: monospace; font-size: 11px;">{{ $t->kode_unik ?? '-' }}</small>
                    </td>
                    <td>
                        <strong>{{ $t->nama_pembeli }}</strong>
                        @if($t->user && $t->user->isCustomer())
                            <br><small style="color:var(--muted);">{{ $t->user->email }}</small>
                        @endif
                    </td>
                    <td>{{ $t->tiket->konser->nama_konser ?? '-' }}</td>
                    <td>
                        @php $kat = $t->tiket->kategori ?? '-'; @endphp
                        @if($kat === 'VIP') 
                            <span class="badge badge-vip">⭐ VIP</span>
                        @elseif($kat === 'Regular') 
                            <span class="badge badge-regular">Regular</span>
                        @elseif($kat === 'Economy') 
                            <span class="badge badge-economy">Economy</span>
                        @else 
                            {{ $kat }} 
                        @endif
                    </td>
                    <td>{{ $t->jumlah }} tiket</td>
                    <td class="price">Rp {{ number_format($t->total,0,',','.') }}<\/td>
                    
                    <td>
                        @if($t->metode_pembayaran == 'cash')
                            <span style="background:rgba(16,185,129,0.15); color:#10b981; padding:4px 12px; border-radius:20px; font-size:11px;">
                                💵 Cash
                            </span>
                        @elseif($t->metode_pembayaran == 'bank_bca')
                            <span style="background:rgba(59,130,246,0.15); color:#3b82f6; padding:4px 12px; border-radius:20px; font-size:11px;">
                                🏦 BCA
                            </span>
                        @elseif($t->metode_pembayaran == 'bank_bri')
                            <span style="background:rgba(59,130,246,0.15); color:#3b82f6; padding:4px 12px; border-radius:20px; font-size:11px;">
                                🏦 BRI
                            </span>
                        @elseif($t->metode_pembayaran == 'bank_mandiri')
                            <span style="background:rgba(59,130,246,0.15); color:#3b82f6; padding:4px 12px; border-radius:20px; font-size:11px;">
                                🏦 Mandiri
                            </span>
                        @else
                            <span style="background:rgba(168,85,247,0.15); color:#a855f7; padding:4px 12px; border-radius:20px; font-size:11px;">
                                📱 QRIS
                            </span>
                        @endif
                    <\/td>
                    
                    <td>
                        @if($t->status_pembayaran == 'paid')
                            <span style="background:rgba(16,185,129,0.15); color:#10b981; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:500;">
                                ✅ Lunas
                            </span>
                        @elseif($t->status_pembayaran == 'pending')
                            <span style="background:rgba(245,158,11,0.15); color:var(--gold); padding:4px 12px; border-radius:20px; font-size:11px; font-weight:500;">
                                ⏳ Menunggu Bayar
                            </span>
                        @else
                            <span style="background:rgba(239,68,68,0.15); color:var(--danger); padding:4px 12px; border-radius:20px; font-size:11px; font-weight:500;">
                                ❌ Gagal
                            </span>
                        @endif
                    <\/td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal)->translatedFormat('d M Y H:i') }}<\/td>
                    <td>
                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            @if($t->status_pembayaran == 'paid')
                                <a href="{{ route('transaksi.struk', $t->id) }}" class="btn btn-sm btn-success">🧾 Struk</a>
                            @else
                                @if($t->metode_pembayaran == 'qris')
                                    <a href="{{ route('transaksi.qris', $t->id) }}" class="btn btn-sm btn-gold">📱 Bayar QRIS</a>
                                @elseif($t->metode_pembayaran != 'cash')
                                    <a href="{{ route('transaksi.bank', $t->id) }}" class="btn btn-sm btn-gold">🏦 Bayar Transfer</a>
                                @endif
                            @endif
                            
                            @if(auth()->user()->isAdmin())
                                <form action="{{ route('transaksi.destroy', $t->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus transaksi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                                </form>
                            @endif
                        </div>
                    <\/td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="empty-state">
                        <div style="font-size:48px; margin-bottom:12px;">💳</div>
                        <p>Belum ada transaksi</p>
                        <a href="{{ route('transaksi.create') }}" style="color:var(--accent2); margin-top:8px; display:inline-block;">+ Beli Tiket Sekarang</a>
                    <\/td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(auth()->user()->isAdmin() && $data->count() > 0)
<div class="card" style="margin-top:20px;">
    <div class="card-header">
        <h4>📊 Ringkasan Transaksi</h4>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; text-align: center;">
            <div>
                <div style="font-size: 12px; color: var(--muted);">Total Transaksi</div>
                <div style="font-size: 24px; font-weight: 700;">{{ $data->count() }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--muted);">✅ Lunas</div>
                <div style="font-size: 24px; font-weight: 700; color: #10b981;">{{ $data->where('status_pembayaran', 'paid')->count() }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--muted);">⏳ Pending</div>
                <div style="font-size: 24px; font-weight: 700; color: #f59e0b;">{{ $data->where('status_pembayaran', 'pending')->count() }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--muted);">💰 Total Pendapatan</div>
                <div style="font-size: 18px; font-weight: 700; color: var(--gold);">Rp {{ number_format($data->where('status_pembayaran','paid')->sum('total'),0,',','.') }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--muted);">📱 QRIS Pending</div>
                <div style="font-size: 24px; font-weight: 700; color: #a855f7;">{{ $data->where('metode_pembayaran', 'qris')->where('status_pembayaran','pending')->count() }}</div>
            </div>
        </div>
    </div>
</div>
@endif

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
}
</style>
@endsection