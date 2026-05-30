<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DashboardController;


// Halaman utama
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('buku', function () {
    return view('buku');
})->name('buku.index');

Route::get('anggota', function () {
    return view('anggota');
})->name('anggota.index');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route search (Tugas 3)
Route::get('/buku/search', [BukuController::class, 'search'])->name('buku.search');
 
// Route filter kategori (dari praktikum)
Route::get('/buku/kategori/{kategori}', [BukuController::class, 'filterKategori'])
     ->name('buku.kategori');
 
// Resource routes: index, create, store, show, edit, update, destroy
Route::resource('buku', BukuController::class);
 
// Anggota
Route::resource('anggota', AnggotaController::class);
 
// Resource route untuk Buku
Route::resource('buku', BukuController::class);
 
// Custom route untuk filter kategori
Route::get('/buku/kategori/{kategori}', [BukuController::class, 'filterKategori'])
     ->name('buku.kategori');
 
// Resource route untuk Anggota (akan dibuat nanti)
Route::resource('anggota', AnggotaController::class);

