<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/buku/export', [BukuController::class, 'export'])->name('buku.export');
Route::post('/buku/bulk-delete', [BukuController::class, 'bulkDelete'])->name('buku.bulk-delete');
Route::get('/buku/search', [BukuController::class, 'search'])->name('buku.search');
Route::get('/buku/kategori/{kategori}', [BukuController::class, 'filterKategori'])->name('buku.kategori');


Route::resource('buku', BukuController::class);

Route::resource('anggota', AnggotaController::class);
Route::get('anggota/export/excel', [AnggotaController::class, 'export'])
     ->name('anggota.export');

Route::resource('anggota', AnggotaController::class);
Route::get('anggota/export/excel', [AnggotaController::class, 'export'])->name('anggota.export');
Route::get('anggota-search', [AnggotaController::class, 'search'])->name('anggota.search');

// Transaksi - CRUD + Custom routes
Route::resource('transaksi', TransaksiController::class);
Route::put('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');

Route::put('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])
     ->name('transaksi.kembalikan');

Route::get('/transaksi-laporan', [TransaksiController::class, 'laporan'])
     ->name('transaksi.laporan');

Route::get('/transaksi-laporan/pdf', [TransaksiController::class, 'exportPdf'])
     ->name('transaksi.exportPdf');