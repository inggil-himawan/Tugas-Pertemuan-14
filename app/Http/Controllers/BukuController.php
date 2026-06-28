<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;

class BukuController extends Controller
{

    // RESOURCE METHODS

    /**
     * Menampilkan daftar semua buku beserta statistik.
     */
    public function index()
    {
        $bukus = Buku::paginate(10);
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
    public function store(StoreBukuRequest $request)
    {
        try {
            // Create buku baru dengan validated data
            Buku::create($request->validated());
            // Redirect dengan success message
            return redirect()->route('buku.index')
                            ->with('success', 'Buku berhasil ditambahkan!');
                            
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage());
        }
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
     * Form edit buku.
     */
    public function edit(string $id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    /**
     * Update data buku.
     */
    public function update(UpdateBukuRequest $request, string $id)
    {
        try {
            $buku = Buku::findOrFail($id);
            
            // Update buku dengan validated data
            $buku->update($request->validated());
            
            // Redirect dengan success message
            return redirect()->route('buku.show', $buku->id)
                            ->with('success', 'Buku berhasil diupdate!');
                            
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Gagal mengupdate buku: ' . $e->getMessage());
        }
    }

    /**
     * Hapus buku (diimplementasi Pertemuan 12).
     */
    public function destroy(string $id)
    {
        try {
            $buku = Buku::findOrFail($id);
            $judulBuku = $buku->judul;
            
            // Delete buku
            $buku->delete();
            
            // Redirect dengan success message
            return redirect()->route('buku.index')
                            ->with('success', "Buku '{$judulBuku}' berhasil dihapus!");
                            
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                            ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }


    // CUSTOM METHODS

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
                default    => null, 
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

   /**
     * Bulk delete multiple buku.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->buku_ids;

        if (empty($ids)) {
            return redirect()->route('buku.index')
                            ->with('warning', 'Tidak ada buku yang dipilih.');
        }

        try {
            $count = count($ids);
            Buku::whereIn('id', $ids)->delete();

            return redirect()->route('buku.index')
                            ->with('success', $count . ' buku berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('buku.index')
                            ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }

    /**
     * Export semua data buku ke file CSV.
     */
    public function export()
    {
        $bukus = Buku::orderBy('kode_buku')->get();

        $filename = 'data_buku_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($bukus) {
            $file = fopen('php://output', 'w');

            // BOM agar karakter Indonesia terbaca benar di Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom
            fputcsv($file, [
                'Kode Buku', 'Judul', 'Kategori', 'Pengarang',
                'Penerbit', 'Tahun Terbit', 'ISBN', 'Bahasa',
                'Harga (Rp)', 'Stok'
            ]);

            // Data baris
            foreach ($bukus as $buku) {
                fputcsv($file, [
                    $buku->kode_buku,
                    $buku->judul,
                    $buku->kategori,
                    $buku->pengarang,
                    $buku->penerbit,
                    $buku->tahun_terbit,
                    $buku->isbn ?? '-',
                    $buku->bahasa,
                    $buku->harga,
                    $buku->stok,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}   

