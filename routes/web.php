<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KonserController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;

// ========== AUTH ==========
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->name('register')
    ->middleware('guest');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

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

// ========== REDIRECT ROOT ==========
Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// ========== PROTECTED ROUTES (WAJIB LOGIN) ==========
Route::middleware('auth')->group(function () {

    // ========== DASHBOARD ==========
    Route::get('/dashboard', function () {
        $konser = \App\Models\Konser::with('tikets')->latest()->get();
        
        if (auth()->user()->isAdmin()) {
            $transaksi = \App\Models\Transaksi::with('tiket.konser')->latest()->get();
            $totalPendapatan = $transaksi->where('status_pembayaran', 'paid')->sum('total');
            $totalTiketTerjual = $transaksi->where('status_pembayaran', 'paid')->sum('jumlah');
        } else {
            $transaksi = \App\Models\Transaksi::where('user_id', auth()->id())->with('tiket.konser')->latest()->get();
            $totalPendapatan = $transaksi->where('status_pembayaran', 'paid')->sum('total');
            $totalTiketTerjual = $transaksi->where('status_pembayaran', 'paid')->sum('jumlah');
        }
        
        return view('dashboard', compact('konser', 'transaksi', 'totalPendapatan', 'totalTiketTerjual'));
    })->name('dashboard');

    // ========== ROUTE UNTUK CUSTOMER (SEMUA USER BISA AKSES) ==========
    Route::get('/events', [UserController::class, 'events'])->name('user.events');
    Route::get('/my-tickets', [UserController::class, 'myTickets'])->name('user.my-tickets');
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/help', [UserController::class, 'help'])->name('user.help');
    Route::get('/cart', [UserController::class, 'cart'])->name('user.cart');
    Route::get('/checkout', [UserController::class, 'checkout'])->name('user.checkout');

    // ========== ROUTE TRANSAKSI (SEMUA USER BISA AKSES) ==========
    Route::get('/transaksi/excel', [TransaksiController::class, 'exportExcel'])->name('transaksi.excel');
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/qris/{id}', [TransaksiController::class, 'qris'])->name('transaksi.qris');
    Route::get('/transaksi/bank/{id}', [TransaksiController::class, 'bank'])->name('transaksi.bank');
    Route::get('/transaksi/check-payment/{id}', [TransaksiController::class, 'checkPayment'])->name('transaksi.check-payment');
    Route::get('/transaksi/confirm-payment/{id}', [TransaksiController::class, 'confirmPayment'])->name('transaksi.confirm-payment');
    Route::get('/transaksi/laporan', [TransaksiController::class, 'laporan'])->name('transaksi.laporan');
    Route::get('/transaksi/pdf', [TransaksiController::class, 'exportPdf'])->name('transaksi.pdf');
    Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    Route::get('/transaksi/{id}/struk', [TransaksiController::class, 'struk'])->name('transaksi.struk');

    // ========== ROUTE KHUSUS ADMIN ==========
    Route::middleware(['role:admin'])->group(function () {
        
        // ========== MANAJEMEN KONSER ==========
        Route::get('/konser', [KonserController::class, 'index'])->name('konser.index');
        Route::get('/konser/create', [KonserController::class, 'create'])->name('konser.create');
        Route::post('/konser', [KonserController::class, 'store'])->name('konser.store');
        Route::get('/konser/pdf', [KonserController::class, 'exportPdf'])->name('konser.pdf');
        Route::get('/konser/excel', [KonserController::class, 'exportExcel'])->name('konser.excel');
        Route::get('/konser/{id}/edit', [KonserController::class, 'edit'])->name('konser.edit');
        Route::put('/konser/{id}', [KonserController::class, 'update'])->name('konser.update');
        Route::delete('/konser/{id}', [KonserController::class, 'destroy'])->name('konser.destroy');
        
        // ========== MANAJEMEN TIKET ==========
        Route::get('/tiket', [TiketController::class, 'index'])->name('tiket.index');
        Route::get('/tiket/create', [TiketController::class, 'create'])->name('tiket.create');
        Route::post('/tiket', [TiketController::class, 'store'])->name('tiket.store');
        Route::get('/tiket/{id}/edit', [TiketController::class, 'edit'])->name('tiket.edit');
        Route::put('/tiket/{id}', [TiketController::class, 'update'])->name('tiket.update');
        Route::delete('/tiket/{id}', [TiketController::class, 'destroy'])->name('tiket.destroy');
        
        // ========== LAPORAN PER KOTA ==========
        Route::get('/transaksi/laporan-per-kota', [TransaksiController::class, 'laporanPerKota'])->name('transaksi.laporan_per_kota');
        
    });
});