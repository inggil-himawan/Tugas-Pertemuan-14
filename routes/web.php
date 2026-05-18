<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerpustakaanController;
use App\Http\Controllers\KategoriController;

// ── Halaman utama ────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');


// ── Praktikum 6: Route sederhana ─────────────────────────────
Route::get('/hello', function () {
    return 'Hello dari Laravel!';
});

Route::get('/info', function () {
    return '<h1>Sistem Perpustakaan</h1><p>Selamat datang!</p>';
});

Route::get('/buku', function () {
    return [
        'judul'     => 'Laravel Programming',
        'pengarang' => 'John Doe',
        'harga'     => 150000
    ];
});

Route::get('/search/{kategori}/{keyword}', function ($kategori, $keyword) {
    return "Cari buku kategori: $kategori dengan keyword: $keyword";
});

// Named route dari Praktikum 6
Route::get('/perpustakaan-home', function () {
    return 'Halaman Perpustakaan';
})->name('perpus.home');

Route::get('/test-route', function () {
    return "URL perpustakaan: " . route('perpus.home');
});


// ── Praktikum 7 & 8: PerpustakaanController ──────────────────
Route::get('/perpustakaan', [PerpustakaanController::class, 'index'])->name('perpustakaan.index');
Route::get('/buku/{id}',    [PerpustakaanController::class, 'show'])->name('buku.show');
Route::get('/about',        [PerpustakaanController::class, 'about'])->name('perpustakaan.about');


// ── TUGAS 1: Anggota ─────────────────────────────────────────
Route::get('/anggota', function () {
    $anggota_list = collect([
        ['id'=>1,'kode'=>'AGT-001','nama'=>'Budi Santoso', 'email'=>'budi@email.com', 'telepon'=>'081234567890','alamat'=>'Jakarta',   'pekerjaan'=>'Mahasiswa', 'tanggal_daftar'=>'01 Jan 2024','status'=>'Aktif'],
        ['id'=>2,'kode'=>'AGT-002','nama'=>'Siti Rahayu',  'email'=>'siti@email.com', 'telepon'=>'082345678901','alamat'=>'Bandung',   'pekerjaan'=>'Guru',      'tanggal_daftar'=>'15 Feb 2024','status'=>'Aktif'],
        ['id'=>3,'kode'=>'AGT-003','nama'=>'Ahmad Fauzi',  'email'=>'ahmad@email.com','telepon'=>'083456789012','alamat'=>'Semarang',  'pekerjaan'=>'Dosen',     'tanggal_daftar'=>'20 Mar 2024','status'=>'Aktif'],
        ['id'=>4,'kode'=>'AGT-004','nama'=>'Dewi Lestari', 'email'=>'dewi@email.com', 'telepon'=>'084567890123','alamat'=>'Yogyakarta','pekerjaan'=>'Peneliti',  'tanggal_daftar'=>'10 Apr 2024','status'=>'Non-Aktif'],
        ['id'=>5,'kode'=>'AGT-005','nama'=>'Rizky Pratama','email'=>'rizky@email.com','telepon'=>'085678901234','alamat'=>'Pekalongan','pekerjaan'=>'Mahasiswa', 'tanggal_daftar'=>'05 Mei 2024','status'=>'Aktif'],
        ['id'=>6,'kode'=>'AGT-006','nama'=>'Nurul Hidayah','email'=>'nurul@email.com','telepon'=>'086789012345','alamat'=>'Surabaya',  'pekerjaan'=>'Pustakawan','tanggal_daftar'=>'18 Jun 2024','status'=>'Aktif'],
    ]);
    return view('anggota.index', compact('anggota_list'));
})->name('anggota.index');

Route::get('/anggota/{id}', function ($id) {
    $data = [
        1 => ['id'=>1,'kode'=>'AGT-001','nama'=>'Budi Santoso', 'email'=>'budi@email.com', 'telepon'=>'081234567890','alamat'=>'Jl. Merdeka No.10, Jakarta',   'pekerjaan'=>'Mahasiswa', 'tanggal_daftar'=>'01 Jan 2024','status'=>'Aktif'],
        2 => ['id'=>2,'kode'=>'AGT-002','nama'=>'Siti Rahayu',  'email'=>'siti@email.com', 'telepon'=>'082345678901','alamat'=>'Jl. Sudirman No.25, Bandung',  'pekerjaan'=>'Guru',      'tanggal_daftar'=>'15 Feb 2024','status'=>'Aktif'],
        3 => ['id'=>3,'kode'=>'AGT-003','nama'=>'Ahmad Fauzi',  'email'=>'ahmad@email.com','telepon'=>'083456789012','alamat'=>'Jl. Diponegoro No.7, Semarang','pekerjaan'=>'Dosen',     'tanggal_daftar'=>'20 Mar 2024','status'=>'Aktif'],
        4 => ['id'=>4,'kode'=>'AGT-004','nama'=>'Dewi Lestari', 'email'=>'dewi@email.com', 'telepon'=>'084567890123','alamat'=>'Jl. Gajah Mada No.3, Jogja',  'pekerjaan'=>'Peneliti',  'tanggal_daftar'=>'10 Apr 2024','status'=>'Non-Aktif'],
        5 => ['id'=>5,'kode'=>'AGT-005','nama'=>'Rizky Pratama','email'=>'rizky@email.com','telepon'=>'085678901234','alamat'=>'Jl. Ahmad Yani No.55, PKL',   'pekerjaan'=>'Mahasiswa', 'tanggal_daftar'=>'05 Mei 2024','status'=>'Aktif'],
        6 => ['id'=>6,'kode'=>'AGT-006','nama'=>'Nurul Hidayah','email'=>'nurul@email.com','telepon'=>'086789012345','alamat'=>'Jl. Pahlawan No.12, Surabaya','pekerjaan'=>'Pustakawan','tanggal_daftar'=>'18 Jun 2024','status'=>'Aktif'],
    ];
    if (!isset($data[$id])) abort(404, 'Anggota tidak ditemukan');
    $anggota = $data[$id];
    return view('anggota.show', compact('anggota'));
})->name('anggota.show');


// ── TUGAS 2: KategoriController ──────────────────────────────
// ⚠️ Urutan penting: /search/{keyword} harus SEBELUM /{id}
//    supaya '/kategori/search/php' tidak ditangkap sebagai id='search'
Route::get('/kategori',                  [KategoriController::class, 'index']) ->name('kategori.index');
Route::get('/kategori/search/{keyword}', [KategoriController::class, 'search'])->name('kategori.search');
Route::get('/kategori/{id}',             [KategoriController::class, 'show'])  ->name('kategori.show');