<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Buku;
use App\Models\Anggota;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['anggota', 'buku'])->latest()->get();
        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $anggotas = Anggota::where('status', 'Aktif')->orderBy('nama')->get();
        $bukus    = Buku::where('stok', '>', 0)->orderBy('judul')->get();
        return view('transaksi.create', compact('anggotas', 'bukus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id'     => 'required|exists:anggota,id',
            'buku_id'        => 'required|exists:buku,id',
            'tanggal_pinjam' => 'required|date',
            'keterangan'     => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $buku = Buku::findOrFail($request->buku_id);
                if ($buku->stok <= 0) {
                    throw new \Exception('Stok buku habis!');
                }

                Transaksi::create([
                    'kode_transaksi'  => $this->generateKodeTransaksi(),
                    'anggota_id'      => $request->anggota_id,
                    'buku_id'         => $request->buku_id,
                    'tanggal_pinjam'  => $request->tanggal_pinjam,
                    'tanggal_kembali' => Carbon::parse($request->tanggal_pinjam)->addDays(7),
                    'status'          => 'Dipinjam',
                    'keterangan'      => $request->keterangan,
                ]);

                $buku->decrement('stok');
            });

            return redirect()->route('transaksi.index')
                             ->with('success', 'Transaksi peminjaman berhasil dibuat!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                             ->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // ----------------------------------------------------------------
    // TUGAS 1: Detail transaksi
    // ----------------------------------------------------------------
    public function show(string $id)
    {
        $transaksi = Transaksi::with(['anggota', 'buku'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    // ----------------------------------------------------------------
    // TUGAS 1: Proses pengembalian buku + hitung denda
    // ----------------------------------------------------------------
    public function kembalikan(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $transaksi = Transaksi::with('buku')->findOrFail($id);

                if ($transaksi->status === 'Dikembalikan') {
                    throw new \Exception('Buku sudah pernah dikembalikan.');
                }

                $tanggalDikembalikan = now()->toDateString();
                $denda = $this->hitungDenda($transaksi, $tanggalDikembalikan);

                $transaksi->update([
                    'status'               => 'Dikembalikan',
                    'tanggal_dikembalikan' => $tanggalDikembalikan,
                    'denda'                => $denda,
                ]);

                $transaksi->buku->increment('stok');
            });

            return redirect()->route('transaksi.show', $id)
                             ->with('success', 'Buku berhasil dikembalikan!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // ----------------------------------------------------------------
    // TUGAS 2: Laporan dengan filter
    // ----------------------------------------------------------------
    public function laporan(Request $request)
    {
        $query = Transaksi::with(['anggota', 'buku']);

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pinjam', [$request->dari, $request->sampai]);
        }
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        $transaksis = $query->latest()->get();
        $totalDenda = $transaksis->sum('denda');
        $anggotas   = Anggota::orderBy('nama')->get();

        return view('transaksi.laporan', compact('transaksis', 'totalDenda', 'anggotas'));
    }

    // ----------------------------------------------------------------
    // TUGAS 2: Export PDF (pakai window.print(), tanpa install package)
    // ----------------------------------------------------------------
    public function exportPdf(Request $request)
    {
        $query = Transaksi::with(['anggota', 'buku']);

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pinjam', [$request->dari, $request->sampai]);
        }
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }
        if ($request->filled('anggota_id')) {
            $query->where('anggota_id', $request->anggota_id);
        }

        $transaksis = $query->latest()->get();
        $totalDenda = $transaksis->sum('denda');

        return view('transaksi.pdf', compact('transaksis', 'totalDenda'));
    }

    // ----------------------------------------------------------------
    // Private helpers
    // ----------------------------------------------------------------
    private function generateKodeTransaksi(): string
    {
        $last = Transaksi::latest('id')->first();
        $no   = $last ? (intval(substr($last->kode_transaksi, -3)) + 1) : 1;
        return 'TRX-' . str_pad($no, 3, '0', STR_PAD_LEFT);
    }

    private function hitungDenda(Transaksi $transaksi, string $tanggalDikembalikan): int
    {
        $batas     = Carbon::parse($transaksi->tanggal_kembali);
        $kembali   = Carbon::parse($tanggalDikembalikan);
        $terlambat = (int) ceil($batas->floatDiffInDays($kembali));
        $terlambat = $terlambat > 0 ? $terlambat : 0;
        return $terlambat > 0 ? $terlambat * 5000 : 0;
    }
}