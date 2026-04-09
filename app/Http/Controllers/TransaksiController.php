<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Tiket;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    // =========================
    // TAMPIL DATA
    // =========================
    public function index()
    {
        $data = Transaksi::with('tiket.konser')->latest()->get();
        return view('transaksi.index', compact('data'));
    }

    // =========================
    // FORM TAMBAH
    // =========================
    public function create()
    {
        $tikets = Tiket::with('konser')->where('stok', '>', 0)->get();

        if ($tikets->isEmpty()) {
            return redirect('/konser')->with('error', 'Tambah tiket dulu sebelum transaksi!');
        }

        return view('transaksi.create', compact('tikets'));
    }

    // =========================
    // SIMPAN DATA
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'tiket_id'     => 'required|exists:tikets,id',
            'jumlah'       => 'required|numeric|min:1',
        ]);

        $tiket = Tiket::find($request->tiket_id);

        if (!$tiket) {
            return back()->with('error', 'Tiket tidak ditemukan!');
        }

        if ($tiket->stok < $request->jumlah) {
            return back()->with('error', "Stok tiket tidak cukup! Tersedia: {$tiket->stok}");
        }

        $total = $tiket->harga * $request->jumlah;

        $transaksi = Transaksi::create([
            'nama_pembeli' => $request->nama_pembeli,
            'tiket_id'     => $request->tiket_id,
            'jumlah'       => $request->jumlah,
            'total'        => $total,
            'tanggal'      => now()->toDateString(),
        ]);

        // Kurangi stok
        $tiket->decrement('stok', $request->jumlah);

        return redirect('/transaksi/' . $transaksi->id . '/struk')
            ->with('success', 'Transaksi berhasil!');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy($id)
    {
        Transaksi::findOrFail($id)->delete();
        return redirect('/transaksi')->with('success', 'Transaksi berhasil dihapus!');
    }

    // =========================
    // STRUK (preview di browser)
    // =========================
    public function struk($id)
    {
        $data = Transaksi::with('tiket.konser')->findOrFail($id);
        return view('transaksi.struk', compact('data'));
    }

    // =========================
    // LAPORAN
    // =========================
    public function laporan()
    {
        $data            = Transaksi::with('tiket.konser')->latest()->get();
        $totalPendapatan = $data->sum('total');
        return view('transaksi.laporan', compact('data', 'totalPendapatan'));
    }

    // =========================
    // EXPORT PDF LAPORAN TRANSAKSI
    // =========================
    public function exportPdf()
    {
        $data            = Transaksi::with('tiket.konser')->latest()->get();
        $totalPendapatan = $data->sum('total');
        $pdf = Pdf::loadView('transaksi.pdf', compact('data', 'totalPendapatan'))
                  ->setPaper('a4', 'landscape');
        return $pdf->download('laporan-transaksi.pdf');
    }
}