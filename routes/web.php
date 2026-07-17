<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
})->name('dashboard');

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
