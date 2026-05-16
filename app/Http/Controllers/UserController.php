<?php

namespace App\Http\Controllers;

use App\Models\Konser;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function events(Request $request)
    {
        $query = Konser::with('tikets');
        
        // SEARCH
        if ($request->filled('search')) {
            $query->where('nama_konser', 'like', '%' . $request->search . '%');
        }
        
        // FILTER KOTA
        if ($request->filled('kota')) {
            $query->where('kota', $request->kota);
        }
        
        // FILTER TANGGAL
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_selesai);
        }
        
        $events = $query->latest()->paginate(12);
        
        // Ambil daftar kota unik untuk filter
        $kotaList = Konser::select('kota')->whereNotNull('kota')->distinct()->pluck('kota');
        
        return view('user.events', compact('events', 'kotaList'));
    }
    
    public function myTickets()
    {
        $transaksis = Transaksi::with(['tiket.konser', 'user'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('user.my-tickets', compact('transaksis'));
    }
    
    public function profile()
    {
        $totalTiket = Transaksi::where('user_id', auth()->id())
            ->where('status_pembayaran', 'paid')
            ->sum('jumlah');
        
        $totalTransaksi = Transaksi::where('user_id', auth()->id())
            ->where('status_pembayaran', 'paid')
            ->count();
        
        $totalBelanja = Transaksi::where('user_id', auth()->id())
            ->where('status_pembayaran', 'paid')
            ->sum('total');
        
        return view('user.profile', compact('totalTiket', 'totalTransaksi', 'totalBelanja'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'nullable|string|max:15',
            'password' => 'nullable|min:8|confirmed',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_telepon = $request->no_telepon;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        
        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui!');
    }
    
    public function help()
    {
        return view('user.help');
    }
    
    public function cart()
    {
        return view('user.cart');
    }
    
    public function checkout()
    {
        return view('user.checkout');
    }
}