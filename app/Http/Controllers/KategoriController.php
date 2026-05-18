<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KategoriController extends Controller
{
    //Data dummy
    private function getAllKategori(): array
    {
        return [
            1 => ['id'=>1, 'nama'=>'Programming',         'ikon'=>'bi-code-slash', 'warna'=>'primary', 'deskripsi'=>'Buku pemrograman, algoritma, dan pengembangan software', 'jumlah_buku'=>25],
            2 => ['id'=>2, 'nama'=>'Jaringan Komputer',   'ikon'=>'bi-diagram-3',  'warna'=>'info',    'deskripsi'=>'Buku jaringan, protokol, dan keamanan siber',             'jumlah_buku'=>18],
            3 => ['id'=>3, 'nama'=>'Basis Data',          'ikon'=>'bi-database',   'warna'=>'warning', 'deskripsi'=>'Buku database SQL, NoSQL, dan data warehousing',           'jumlah_buku'=>15],
            4 => ['id'=>4, 'nama'=>'Kecerdasan Buatan',   'ikon'=>'bi-robot',      'warna'=>'success', 'deskripsi'=>'Buku AI, machine learning, dan data science',              'jumlah_buku'=>20],
            5 => ['id'=>5, 'nama'=>'Matematika & Statistika','ikon'=>'bi-calculator','warna'=>'danger','deskripsi'=>'Buku matematika diskrit dan statistika untuk informatika',  'jumlah_buku'=>12],
        ];
    }

    private function getBukuByKategori(int $id): array
    {
        $buku = [
            1 => [
                ['judul'=>'Pemrograman PHP Dasar hingga Advanced', 'pengarang'=>'Budi Raharjo', 'tahun'=>2023, 'stok'=>10],
                ['judul'=>'Laravel: From Zero to Hero',            'pengarang'=>'Andi Nugroho', 'tahun'=>2024, 'stok'=>5],
                ['judul'=>'Clean Code in PHP',                     'pengarang'=>'Siti Aminah',  'tahun'=>2022, 'stok'=>8],
                ['judul'=>'Python for Beginners',                  'pengarang'=>'Dedi Santoso', 'tahun'=>2023, 'stok'=>12],
            ],
            2 => [
                ['judul'=>'Jaringan Komputer Dasar',         'pengarang'=>'Hendra Kurnia', 'tahun'=>2022, 'stok'=>6],
                ['judul'=>'TCP/IP Protocol Suite',           'pengarang'=>'Maya Sari',     'tahun'=>2021, 'stok'=>4],
                ['judul'=>'Ethical Hacking & Cybersecurity', 'pengarang'=>'Agus Priyanto', 'tahun'=>2023, 'stok'=>9],
            ],
            3 => [
                ['judul'=>'MySQL untuk Pemula',                 'pengarang'=>'Budi Raharjo',  'tahun'=>2022, 'stok'=>11],
                ['judul'=>'PostgreSQL: Up and Running',         'pengarang'=>'Lina Febrianti','tahun'=>2023, 'stok'=>5],
                ['judul'=>'MongoDB: The Definitive Guide',      'pengarang'=>'Tono Sutrisno', 'tahun'=>2022, 'stok'=>3],
            ],
            4 => [
                ['judul'=>'Machine Learning dengan Python', 'pengarang'=>'Andi Nugroho', 'tahun'=>2024, 'stok'=>8],
                ['judul'=>'Deep Learning Fundamentals',    'pengarang'=>'Siti Aminah',  'tahun'=>2023, 'stok'=>6],
                ['judul'=>'Data Science Handbook',         'pengarang'=>'Rina Wijaya',  'tahun'=>2023, 'stok'=>7],
            ],
            5 => [
                ['judul'=>'Matematika Diskrit untuk Informatika', 'pengarang'=>'Hendra Kurnia', 'tahun'=>2022, 'stok'=>9],
                ['judul'=>'Statistika untuk Data Science',       'pengarang'=>'Maya Sari',     'tahun'=>2023, 'stok'=>5],
            ],
        ];

        return $buku[$id] ?? [];
    }

    // Daftar semua kategori
    public function index()
    {
        $kategori_list = collect($this->getAllKategori())->values();
        return view('kategori.index', compact('kategori_list'));
    }

    // Detail kategori + daftar buku
    public function show($id)
    {
        $all = $this->getAllKategori();

        if (!isset($all[$id])) {
            abort(404, 'Kategori tidak ditemukan');
        }

        $kategori  = $all[$id];
        $buku_list = $this->getBukuByKategori((int) $id);

        return view('kategori.show', compact('kategori', 'buku_list'));
    }

    // Filter kategori berdasarkan keyword
    public function search($keyword)
    {
        $kw = strtolower($keyword);

        $hasil = collect($this->getAllKategori())
            ->filter(fn($item) =>
                str_contains(strtolower($item['nama']),      $kw) ||
                str_contains(strtolower($item['deskripsi']), $kw)
            )
            ->values();

        return view('kategori.search', compact('hasil', 'keyword'));
    }
}