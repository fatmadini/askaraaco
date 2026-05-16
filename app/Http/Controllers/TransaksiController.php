<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Tiket;
use App\Models\Konser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;

class TransaksiController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $data = Transaksi::with(['tiket.konser', 'user'])->latest()->get();
        } else {
            $data = Transaksi::where('user_id', auth()->id())->with(['tiket.konser', 'user'])->latest()->get();
        }
        return view('transaksi.index', compact('data'));
    }

    public function create(Request $request)
    {
        if ($request->has('konser_id')) {
            $tikets = Tiket::with('konser')
                ->where('konser_id', $request->konser_id)
                ->where('stok', '>', 0)
                ->get();
        } else {
            $tikets = Tiket::with('konser')->where('stok', '>', 0)->get();
        }
        
        return view('transaksi.create', compact('tikets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'tiket_id' => 'required|exists:tikets,id',
            'jumlah' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:cash,bank_bca,bank_bri,bank_mandiri,qris',
        ]);

        $tiket = Tiket::findOrFail($request->tiket_id);
        
        if ($tiket->stok < $request->jumlah) {
            return back()->with('error', 'Stok tiket tidak mencukupi! Stok tersisa: ' . $tiket->stok);
        }

        $total = $tiket->harga * $request->jumlah;
        $kodeUnik = 'TXN-' . strtoupper(Str::random(8)) . '-' . date('YmdHis');

        DB::beginTransaction();
        try {
            $statusBayar = ($request->metode_pembayaran == 'cash') ? 'paid' : 'pending';
            
            $transaksi = Transaksi::create([
                'nama_pembeli' => $request->nama_pembeli,
                'tiket_id' => $request->tiket_id,
                'jumlah' => $request->jumlah,
                'total' => $total,
                'tanggal' => now(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => $statusBayar,
                'kode_unik' => $kodeUnik,
                'user_id' => auth()->id(),
            ]);


            $tiket->decrement('stok', $request->jumlah);
            DB::commit();

            if (!$transaksi->barcode_path) {
                $transaksi->generateQRCode();
            }

            if ($request->metode_pembayaran == 'qris') {
                return redirect()->route('transaksi.qris', $transaksi->id)
                    ->with('success', 'Silakan scan QR code untuk menyelesaikan pembayaran');
            } 
            elseif ($request->metode_pembayaran != 'cash') {
                return redirect()->route('transaksi.bank', $transaksi->id)
                    ->with('success', 'Silakan transfer ke rekening yang tertera');
            }

            return redirect()->route('transaksi.struk', $transaksi->id)
                ->with('success', 'Transaksi berhasil! 🎉 QR Code tiket sudah dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function qris($id)
    {
        $transaksi = Transaksi::with(['tiket.konser', 'user'])->findOrFail($id);
        
        if (!$transaksi->barcode_path && $transaksi->status_pembayaran == 'paid') {
            $transaksi->generateQRCode();
        }
        
        $qrCodePath = asset('images/qris/qris_code.png');
        return view('transaksi.qris', compact('transaksi', 'qrCodePath'));
    }

    public function bank($id)
    {
        $transaksi = Transaksi::with(['tiket.konser', 'user'])->findOrFail($id);
        
        if (!$transaksi->barcode_path && $transaksi->status_pembayaran == 'paid') {
            $transaksi->generateQRCode();
        }
        
        $rekening = [
            'bank_bca' => [
                'nama' => 'Bank BCA',
                'no_rekening' => '1234567890',
                'atas_nama' => 'TicketWave',
                'kode_bank' => 'BCA'
            ],
            'bank_bri' => [
                'nama' => 'Bank BRI',
                'no_rekening' => '0987654321',
                'atas_nama' => 'TicketWave',
                'kode_bank' => 'BRI'
            ],
            'bank_mandiri' => [
                'nama' => 'Bank Mandiri',
                'no_rekening' => '1122334455',
                'atas_nama' => 'TicketWave',
                'kode_bank' => 'Mandiri'
            ]
        ];
        
        $bankInfo = $rekening[$transaksi->metode_pembayaran] ?? null;
        
        return view('transaksi.bank', compact('transaksi', 'bankInfo'));
    }
    
    public function checkPayment($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return response()->json([
            'status' => $transaksi->status_pembayaran,
            'message' => $transaksi->status_pembayaran === 'paid' ? 'Pembayaran berhasil' : 'Menunggu pembayaran',
            'has_barcode' => $transaksi->barcode_path ? true : false
        ]);
    }
    
    public function confirmPayment($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        if ($transaksi->status_pembayaran === 'pending') {
            $transaksi->update(['status_pembayaran' => 'paid']);
            
            if (!$transaksi->barcode_path) {
                $transaksi->generateQRCode();
            }
            
            return redirect()->route('transaksi.struk', $transaksi->id)
                ->with('success', 'Pembayaran berhasil dikonfirmasi! 🎫 QR Code tiket sudah tersedia.');
        }
        
        return redirect()->route('transaksi.index');
    }

    public function struk($id)
    {
        $data = Transaksi::with(['tiket.konser', 'user'])->findOrFail($id);
        
        if ($data->status_pembayaran == 'paid' && !$data->barcode_path) {
            $data->generateQRCode();
            $data->refresh();
        }
        
        return view('transaksi.struk', compact('data'));
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        if ($transaksi->barcode_path && file_exists(public_path($transaksi->barcode_path))) {
            @unlink(public_path($transaksi->barcode_path));
        }
        
        if ($transaksi->status_pembayaran === 'paid') {
            $tiket = Tiket::find($transaksi->tiket_id);
            if ($tiket) {
                $tiket->increment('stok', $transaksi->jumlah);
            }
        }
        
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi dihapus!');
    }

    public function laporan()
    {
        $data = Transaksi::with(['tiket.konser', 'user'])
            ->where('status_pembayaran', 'paid')
            ->latest()
            ->get();
        $totalPendapatan = $data->sum('total');
        return view('transaksi.laporan', compact('data', 'totalPendapatan'));
    }

    public function laporanPerKota()
    {
        $transaksis = Transaksi::with(['tiket.konser'])
            ->where('status_pembayaran', 'paid')
            ->get();
        
        $laporanPerKota = [];
        
        foreach ($transaksis as $transaksi) {
            $konser = $transaksi->tiket->konser;
            $kota = $konser->kota;
            if (empty($kota)) {
                $lokasi = $konser->lokasi ?? '';
                
                if (preg_match('/palembang|jakabaring/i', $lokasi)) {
                    $kota = 'Palembang';
                } elseif (preg_match('/bandung/i', $lokasi)) {
                    $kota = 'Bandung';
                } elseif (preg_match('/jakarta|gbk|ice bsd/i', $lokasi)) {
                    $kota = 'Jakarta';
                } elseif (preg_match('/surabaya/i', $lokasi)) {
                    $kota = 'Surabaya';
                } else {
                    $kota = 'Lainnya (' . $lokasi . ')';
                }
            }
            
            if (!isset($laporanPerKota[$kota])) {
                $laporanPerKota[$kota] = [
                    'total_transaksi' => 0,
                    'total_tiket' => 0,
                    'total_pendapatan' => 0,
                    'transaksis' => []
                ];
            }
            
            $laporanPerKota[$kota]['total_transaksi']++;
            $laporanPerKota[$kota]['total_tiket'] += $transaksi->jumlah;
            $laporanPerKota[$kota]['total_pendapatan'] += $transaksi->total;
            $laporanPerKota[$kota]['transaksis'][] = $transaksi;
        }
        
        ksort($laporanPerKota);
        
        $totalSemua = [
            'total_transaksi' => $transaksis->count(),
            'total_tiket' => $transaksis->sum('jumlah'),
            'total_pendapatan' => $transaksis->sum('total'),
        ];
        
        $kotaList = Konser::select('kota')->whereNotNull('kota')->distinct()->pluck('kota');
        
        return view('transaksi.laporan_per_kota', compact('laporanPerKota', 'totalSemua', 'kotaList'));
    }

    public function exportPdf()
    {
        $data = Transaksi::with(['tiket.konser', 'user'])->where('status_pembayaran', 'paid')->get();
        $pdf = Pdf::loadView('transaksi.pdf', compact('data'));
        return $pdf->download('laporan-transaksi.pdf');
        
    }

    public function exportExcel()
    {
        return Excel::download(new TransaksiExport, 'laporan-transaksi.xlsx');
    }

    public function regenerateQRCode($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        if ($transaksi->status_pembayaran != 'paid') {
            return back()->with('error', 'QR Code hanya bisa digenerate untuk transaksi yang sudah lunas!');
        }
        
        $transaksi->regenerateQRCode();
        
        return back()->with('success', 'QR Code berhasil digenerate ulang!');
    }

    public function validateQRCode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);
        
        $transaksi = Transaksi::where('barcode', $request->barcode)
            ->orWhere('kode_unik', $request->barcode)
            ->first();
        
        if (!$transaksi) {
            return response()->json([
                'valid' => false,
                'message' => 'Barcode tidak valid!'
            ], 404);
        }
        
        if ($transaksi->status_pembayaran != 'paid') {
            return response()->json([
                'valid' => false,
                'message' => 'Tiket belum lunas!'
            ], 400);
        }
        
        return response()->json([
            'valid' => true,
            'message' => 'Tiket valid! Selamat menikmati konser.',
            'data' => [
                'nama_pembeli' => $transaksi->nama_pembeli,
                'konser' => $transaksi->tiket->konser->nama_konser ?? '-',
                'kategori' => $transaksi->tiket->kategori ?? '-',
                'jumlah' => $transaksi->jumlah,
                'tanggal' => $transaksi->tiket->konser->tanggal ?? '-',
                'jam' => $transaksi->tiket->konser->waktu_mulai ?? '19:00'
            ]
        ]);
    }
}