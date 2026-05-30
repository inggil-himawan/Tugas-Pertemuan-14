<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    // RESOURCE METHODS
    // ─────────────────────────────────────────────────────────────────

    /**
     * Menampilkan daftar semua buku beserta statistik.
     */
    public function index()
    {
        $bukus         = Buku::latest()->get();
        $totalBuku     = Buku::count();
        $bukuTersedia  = Buku::where('stok', '>', 0)->count();
        $bukuHabis     = Buku::where('stok', 0)->count();

        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis'
        ));
    }

    /**
     * Form tambah buku (diimplementasi Pertemuan 12).
     */
    public function create()
    {
        return view('buku.create');
    }

    /**
     * Simpan buku baru (diimplementasi Pertemuan 12).
     */
    public function store(Request $request)
    {
        // TODO: Pertemuan 12
    }

    /**
     * Menampilkan detail satu buku.
     */
    public function show(string $id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.show', compact('buku'));
    }

    /**
     * Form edit buku (diimplementasi Pertemuan 12).
     */
    public function edit(string $id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    /**
     * Update data buku (diimplementasi Pertemuan 12).
     */
    public function update(Request $request, string $id)
    {
        // TODO: Pertemuan 12
    }

    /**
     * Hapus buku (diimplementasi Pertemuan 12).
     */
    public function destroy(string $id)
    {
        // TODO: Pertemuan 12
    }

    // ─────────────────────────────────────────────────────────────────
    // CUSTOM METHODS
    // ─────────────────────────────────────────────────────────────────

    /**
     * Filter buku berdasarkan kategori.
     */
    public function filterKategori($kategori)
    {
        $bukus        = Buku::where('kategori', $kategori)->latest()->get();
        $totalBuku    = $bukus->count();
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis    = $bukus->where('stok', 0)->count();

        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'kategori'
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // TUGAS 3 — SEARCH & FILTER ADVANCED
    // ─────────────────────────────────────────────────────────────────

    /**
     * Mencari dan memfilter buku berdasarkan:
     *  - keyword  : judul, pengarang, atau penerbit
     *  - kategori : (dropdown)
     *  - tahun    : tahun terbit (dropdown)
     *  - stok     : semua / tersedia / habis
     *
     * Route  : GET /buku/search
     * Named  : buku.search
     */
    public function search(Request $request)
    {
        $query = Buku::query();

        // 1. Filter keyword (judul / pengarang / penerbit)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('judul',     'like', "%{$keyword}%")
                  ->orWhere('pengarang', 'like', "%{$keyword}%")
                  ->orWhere('penerbit',  'like', "%{$keyword}%");
            });
        }

        // 2. Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 3. Filter tahun terbit
        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        // 4. Filter ketersediaan stok
        if ($request->filled('stok')) {
            match ($request->stok) {
                'tersedia' => $query->where('stok', '>', 0),
                'habis'    => $query->where('stok', 0),
                default    => null,   // 'semua' — tidak ada filter tambahan
            };
        }

        $bukus        = $query->latest()->get();
        $totalBuku    = $bukus->count();
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis    = $bukus->where('stok', 0)->count();

        // Ambil semua nilai unik untuk opsi dropdown filter
        $kategoris = Buku::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
        $tahuns    = Buku::select('tahun_terbit')->distinct()->orderBy('tahun_terbit', 'desc')->pluck('tahun_terbit');

        // Teruskan input pencarian ke view agar form tetap terisi
        $searchInput = $request->only(['keyword', 'kategori', 'tahun', 'stok']);

        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'kategoris',
            'tahuns',
            'searchInput'
        ));
    }
}