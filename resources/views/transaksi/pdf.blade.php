<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 16px; }
        .header h2 { font-size: 16px; }
        .header p { font-size: 11px; color: #555; }
        .summary { display: flex; gap: 12px; margin-bottom: 16px; }
        .summary div { flex: 1; border: 1px solid #ccc; padding: 8px; text-align: center; border-radius: 4px; }
        .summary div strong { display: block; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th { background: #333; color: #fff; padding: 6px 8px; text-align: left; font-size: 11px; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 11px; }
        tr:nth-child(even) td { background: #f9f9f9; }
        tfoot td { font-weight: bold; border-top: 2px solid #333; }
        .text-end { text-align: right; }
        .merah { color: #dc3545; }
        .badge-pinjam { background: #ffc107; color: #000; padding: 2px 6px; border-radius: 3px; font-size: 10px; }
        .badge-kembali { background: #198754; color: #fff; padding: 2px 6px; border-radius: 3px; font-size: 10px; }
        .no-print { padding: 10px; background: #f0f0f0; margin-bottom: 12px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()"
                style="padding:8px 16px;background:#dc3545;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:13px;">
            🖨️ Cetak / Simpan PDF
        </button>
        <button onclick="window.close()"
                style="padding:8px 16px;background:#6c757d;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:13px;margin-left:8px;">
            ✕ Tutup
        </button>
    </div>

    <div class="header">
        <h2>LAPORAN TRANSAKSI PEMINJAMAN BUKU</h2>
        <p>Dicetak: {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <div class="summary">
        <div><span>Total</span><strong>{{ $transaksis->count() }}</strong></div>
        <div><span>Dipinjam</span><strong>{{ $transaksis->where('status','Dipinjam')->count() }}</strong></div>
        <div><span>Dikembalikan</span><strong>{{ $transaksis->where('status','Dikembalikan')->count() }}</strong></div>
        <div><span>Total Denda</span><strong class="merah">Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th><th>Kode</th><th>Anggota</th><th>Buku</th>
                <th>Tgl Pinjam</th><th>Batas Kembali</th><th>Tgl Dikembalikan</th>
                <th>Status</th><th class="text-end">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $t->kode_transaksi }}</td>
                    <td>{{ $t->anggota->nama }}</td>
                    <td>{{ $t->buku->judul }}</td>
                    <td>{{ $t->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>{{ $t->tanggal_kembali->format('d/m/Y') }}</td>
                    <td>{{ $t->tanggal_dikembalikan ? $t->tanggal_dikembalikan->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if ($t->status === 'Dipinjam')
                            <span class="badge-pinjam">Dipinjam</span>
                        @else
                            <span class="badge-kembali">Dikembalikan</span>
                        @endif
                    </td>
                    <td class="text-end {{ $t->denda > 0 ? 'merah' : '' }}">
                        Rp {{ number_format($t->denda, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center;padding:12px;color:#888">Tidak ada data</td></tr>
            @endforelse
        </tbody>
        @if ($transaksis->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="8" class="text-end">Total Denda:</td>
                    <td class="text-end merah">Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

</body>
</html>