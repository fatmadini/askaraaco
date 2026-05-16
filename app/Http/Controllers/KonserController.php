<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Konser;
use Barryvdh\DomPDF\Facade\Pdf;

class KonserController extends Controller
{
   
    public function index()
    {
        $konser = Konser::with('tikets')->latest()->get();
        return view('konser.index', compact('konser'));
    }


    public function create()
    {
        return view('konser.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_konser' => 'required|string|max:255',
            'tanggal'     => 'required|date',
            'lokasi'      => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'kuota'       => 'required|numeric|min:1',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $nama_file = null;
        if ($request->hasFile('foto')) {
            $file      = $request->file('foto');
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('foto'), $nama_file);
        }

        $kota = Konser::detectKota($request->lokasi);

        Konser::create([
            'nama_konser' => $request->nama_konser,
            'tanggal'     => $request->tanggal,
            'lokasi'      => $request->lokasi,
            'kota'        => $kota,
            'harga'       => $request->harga,
            'kuota'       => $request->kuota,
            'foto'        => $nama_file,
        ]);

        return redirect('/konser')->with('success', 'Konser berhasil ditambahkan! Kota: ' . ($kota ?? 'Tidak terdeteksi'));
    }

    public function edit($id)
    {
        $data = Konser::findOrFail($id);
        return view('konser.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
        $data = Konser::findOrFail($id);

        $request->validate([
            'nama_konser' => 'required|string|max:255',
            'tanggal'     => 'required|date',
            'lokasi'      => 'required|string|max:255',
            'harga'       => 'required|numeric|min:0',
            'kuota'       => 'required|numeric|min:1',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($data->foto && file_exists(public_path('foto/' . $data->foto))) {
                unlink(public_path('foto/' . $data->foto));
            }
            $file      = $request->file('foto');
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('foto'), $nama_file);
            $data->foto = $nama_file;
        }

        $kota = Konser::detectKota($request->lokasi);

        $data->update([
            'nama_konser' => $request->nama_konser,
            'tanggal'     => $request->tanggal,
            'lokasi'      => $request->lokasi,
            'kota'        => $kota,
            'harga'       => $request->harga,
            'kuota'       => $request->kuota,
        ]);

        return redirect('/konser')->with('success', 'Data konser berhasil diupdate! Kota: ' . ($kota ?? 'Tidak terdeteksi'));
    }


    public function destroy($id)
    {
        $data = Konser::findOrFail($id);
        if ($data->foto && file_exists(public_path('foto/' . $data->foto))) {
            unlink(public_path('foto/' . $data->foto));
        }
        $data->delete();
        return redirect('/konser')->with('success', 'Konser berhasil dihapus!');
    }

   
    public function exportPdf()
    {
        $data = Konser::with('tikets')->get();
        $pdf  = Pdf::loadView('konser.pdf', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->download('laporan-konser.pdf');
    }

   
    public function exportExcel()
    {
        $data = Konser::with('tikets')->get();

        $filename = 'laporan-konser-' . now()->format('Ymd-His') . '.xls';

        $html = '
        <html>
        <head>
            <meta charset="UTF-8">
        </head>
        <body>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr style="background:#7c3aed;color:white;">
                    <th>No</th>
                    <th>Nama Konser</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Kota</th>
                    <th>Harga Dasar</th>
                    <th>Kuota</th>
                    <th>Jenis Tiket</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($data as $i => $k) {
            $tikets = $k->tikets->pluck('kategori')->join(', ') ?: '-';
            $html .= '
                <tr>
                    <td>' . ($i + 1) . '</td>
                    <td>' . e($k->nama_konser) . '</td>
                    <td>' . \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') . '</td>
                    <td>' . e($k->lokasi) . '</td>
                    <td>' . e($k->kota ?? '-') . '</td>
                    <td>Rp ' . number_format($k->harga, 0, ',', '.') . '</td>
                    <td>' . number_format($k->kuota, 0, ',', '.') . '</td>
                    <td>' . e($tikets) . '</td>
                </tr>';
        }

        $html .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align:right;font-weight:bold;">Total Konser:</td>
                    <td colspan="3">' . $data->count() . ' konser</td>
                </tr>
            </tfoot>
        </table>
        </body>
        </html>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Expires', '0');
    }
}