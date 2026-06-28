@extends('layouts.app')
@section('title', 'Laporan Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan Transaksi</h1>
    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

{{-- Filter --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light"><strong><i class="bi bi-funnel me-1"></i> Filter</strong></div>
    <div class="card-body">
        <form method="GET" action="{{ route('transaksi.laporan') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Dari Tanggal</label>
                    <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="Semua" {{ request('status','Semua') == 'Semua' ? 'selected' : '' }}>Semua</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Anggota</label>
                    <select name="anggota_id" class="form-select">
                        <option value="">Semua Anggota</option>
                        @foreach ($anggotas as $anggota)
                            <option value="{{ $anggota->id }}"
                                {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                {{ $anggota->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('transaksi.laporan') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Ringkasan --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-primary text-center">
            <div class="card-body py-3">
                <h6 class="text-muted small">Total Transaksi</h6>
                <h2 class="mb-0 text-primary">{{ $transaksis->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-warning text-center">
            <div class="card-body py-3">
                <h6 class="text-muted small">Dipinjam</h6>
                <h2 class="mb-0 text-warning">{{ $transaksis->where('status','Dipinjam')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-success text-center">
            <div class="card-body py-3">
                <h6 class="text-muted small">Dikembalikan</h6>
                <h2 class="mb-0 text-success">{{ $transaksis->where('status','Dikembalikan')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-danger text-center">
            <div class="card-body py-3">
                <h6 class="text-muted small">Total Denda</h6>
                <h2 class="mb-0 text-danger">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- Tombol Export PDF --}}
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('transaksi.exportPdf', request()->query()) }}"
       target="_blank"
       class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
    </a>
</div>

{{-- Tabel --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Tgl Dikembalikan</th>
                        <th>Status</th>
                        <th class="text-end">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $t)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ $t->kode_transaksi }}</code></td>
                            <td>{{ $t->anggota->nama }}</td>
                            <td>{{ $t->buku->judul }}</td>
                            <td>{{ $t->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $t->tanggal_kembali->format('d/m/Y') }}</td>
                            <td>{{ $t->tanggal_dikembalikan ? $t->tanggal_dikembalikan->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if ($t->status === 'Dipinjam')
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                @else
                                    <span class="badge bg-success">Dikembalikan</span>
                                @endif
                            </td>
                            <td class="text-end {{ $t->denda > 0 ? 'text-danger' : '' }}">
                                Rp {{ number_format($t->denda, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Tidak ada data yang sesuai filter
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($transaksis->count() > 0)
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="8" class="text-end fw-bold">Total Denda:</td>
                            <td class="text-end fw-bold text-danger">
                                Rp {{ number_format($totalDenda, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection