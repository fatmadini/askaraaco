<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KonserController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TiketController;

// ========== AUTH ==========
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');
    if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }
    return back()->withErrors(['email' => 'Email atau password salah.']);
})->middleware('guest');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// ========== PROTECTED ROUTES ==========
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', fn() => redirect('/dashboard'));
    Route::get('/dashboard', function () {
        $konser    = \App\Models\Konser::with('tikets')->latest()->get();
        $transaksi = \App\Models\Transaksi::with('tiket.konser')->latest()->get();
        $totalPendapatan = $transaksi->sum('total');
        return view('dashboard', compact('konser', 'transaksi', 'totalPendapatan'));
    })->name('dashboard');

    // ========== KONSER ==========
    Route::get('/konser',              [KonserController::class, 'index'])->name('konser.index');
    Route::get('/konser/create',       [KonserController::class, 'create'])->name('konser.create');
    Route::post('/konser',             [KonserController::class, 'store'])->name('konser.store');
    Route::get('/konser/{id}/edit',    [KonserController::class, 'edit'])->name('konser.edit');
    Route::put('/konser/{id}',         [KonserController::class, 'update'])->name('konser.update');
    Route::delete('/konser/{id}',      [KonserController::class, 'destroy'])->name('konser.destroy');
    Route::get('/konser/pdf',          [KonserController::class, 'exportPdf'])->name('konser.pdf');
    Route::get('/konser/excel',        [KonserController::class, 'exportExcel'])->name('konser.excel');

    // ========== TIKET ==========
    Route::get('/tiket',              [TiketController::class, 'index'])->name('tiket.index');
    Route::get('/tiket/create',       [TiketController::class, 'create'])->name('tiket.create');
    Route::post('/tiket',             [TiketController::class, 'store'])->name('tiket.store');
    Route::get('/tiket/{id}/edit',    [TiketController::class, 'edit'])->name('tiket.edit');
    Route::put('/tiket/{id}',         [TiketController::class, 'update'])->name('tiket.update');
    Route::delete('/tiket/{id}',      [TiketController::class, 'destroy'])->name('tiket.destroy');

    // ========== TRANSAKSI ==========
    Route::get('/transaksi',           [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/create',    [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi',          [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::delete('/transaksi/{id}',   [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    Route::get('/transaksi/laporan',   [TransaksiController::class, 'laporan'])->name('transaksi.laporan');
    Route::get('/transaksi/pdf',       [TransaksiController::class, 'exportPdf'])->name('transaksi.pdf');
    Route::get('/transaksi/{id}/struk',[TransaksiController::class, 'struk'])->name('transaksi.struk');

});