<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    return view('welcome');
})->name('dashboard');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    if (auth()->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    return back()->withErrors([
        'username' => 'Username atau password salah.',
    ]);
})->name('login.post');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::prefix('bahan-baku')->name('bahan-baku.')->group(function () {
        Route::get('/', [BahanBakuController::class, 'index'])->name('index');
        Route::get('/create', [BahanBakuController::class, 'create'])->name('create');
        Route::post('/', [BahanBakuController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BahanBakuController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BahanBakuController::class, 'update'])->name('update');
        Route::get('/{id}/restock', [BahanBakuController::class, 'restock'])->name('restock');
        Route::post('/{id}/restock', [BahanBakuController::class, 'processRestock'])->name('processRestock');
        Route::delete('/{id}', [BahanBakuController::class, 'destroy'])->name('destroy');
    });

    Route::resource('resep', ResepController::class);

    Route::resource('penjualan', PenjualanController::class)->except(['edit', 'update']);

    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/harian', [ReportController::class, 'harian'])->name('harian');
        Route::get('/rentang', [ReportController::class, 'rentang'])->name('rentang');
    });
});
