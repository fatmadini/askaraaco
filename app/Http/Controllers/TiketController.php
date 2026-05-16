<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket;
use App\Models\Konser;

class TiketController extends Controller
{
   
    public function index()
    {
        $tiket = Tiket::with('konser')->latest()->get();
        return view('tiket.index', compact('tiket'));
    }


    public function create()
    {
        $konser = Konser::all();
        return view('tiket.create', compact('konser'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'konser_id' => 'required|exists:konsers,id',
            'kategori'  => 'required|in:VIP,Regular,Economy',
            'harga'     => 'required|numeric|min:0',
            'stok'      => 'required|numeric|min:1',
        ]);

        Tiket::create([
            'konser_id' => $request->konser_id,
            'kategori'  => $request->kategori,
            'harga'     => $request->harga,
            'stok'      => $request->stok,
        ]);

        return redirect('/tiket')->with('success', 'Tiket berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $tiket  = Tiket::findOrFail($id);
        $konser = Konser::all();
        return view('tiket.edit', compact('tiket', 'konser'));
    }

    public function update(Request $request, $id)
    {
        $tiket = Tiket::findOrFail($id);

        $request->validate([
            'konser_id' => 'required|exists:konsers,id',
            'kategori'  => 'required|in:VIP,Regular,Economy',
            'harga'     => 'required|numeric|min:0',
            'stok'      => 'required|numeric|min:0',
        ]);

        $tiket->update([
            'konser_id' => $request->konser_id,
            'kategori'  => $request->kategori,
            'harga'     => $request->harga,
            'stok'      => $request->stok,
        ]);

        return redirect('/tiket')->with('success', 'Tiket berhasil diupdate!');
    }

    public function destroy($id)
    {
        Tiket::findOrFail($id)->delete();
        return redirect('/tiket')->with('success', 'Tiket berhasil dihapus!');
    }
}