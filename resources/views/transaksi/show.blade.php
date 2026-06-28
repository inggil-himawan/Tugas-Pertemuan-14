@extends('layouts.app')
@section('title', 'Detail Transaksi')

@section('content')
<div class="row justify-content-center">
<div class="col-md-8">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TUGAS 3: Warning terlambat --}}
    @php
        $terlambatHari = 0;
        if ($transaksi->status === 'Dipinjam' && now()->gt($transaksi->tanggal_kembali)) {
            $terlambatHari = (int) ceil($transaksi->tanggal_kembali->floatDiffInDays(now()));
        } elseif ($transaksi->status === 'Dikembalikan' && $transaksi->tanggal_dikembalikan > $transaksi->tanggal_kembali) {
            $terlambatHari = (int) ceil($transaksi->tanggal_kembali->floatDiffInDays($transaksi->tanggal_dikembalikan));
        }
    @endphp

    @if ($terlambatHari > 0)
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-octagon-fill me-2"></i>
            <strong>Peringatan Keterlambatan!</strong>
            Buku ini sudah melewati batas pengembalian selama <strong>{{ $terlambatHari }} hari</strong>.
            @if ($transaksi->status === 'Dipinjam')
                Estimasi denda yang akan dikenakan: <strong>Rp {{ number_format($terlambatHari * 5000, 0, ',', '.') }}</strong>.
            @endif
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center
            {{ $transaksi->status === 'Dipinjam' ? 'bg-warning text-dark' : 'bg-success text-white' }}">
            <h5 class="mb-0">Detail Transaksi: <code>{{ $transaksi->kode_transaksi }}</code></h5>
            <span class="badge {{ $transaksi->status === 'Dipinjam' ? 'bg-dark' : 'bg-light text-dark' }}">
                {{ $transaksi->status }}
            </span>
        </div>

        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="bg-light rounded p-3">
                        <small class="text-muted d-block mb-1">Anggota</small>
                        <strong>{{ $transaksi->anggota->nama }}</strong><br>
                        <small class="text-muted">{{ $transaksi->anggota->kode_anggota }}</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-light rounded p-3">
                        <small class="text-muted d-block mb-1">Buku</small>
                        <strong>{{ $transaksi->buku->judul }}</strong><br>
                        <small class="text-muted">{{ $transaksi->buku->kode_buku }}</small>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-sm">
                <tr>
                    <th class="table-light" style="width:40%">Tanggal Pinjam</th>
                    <td>{{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                </tr>
                <tr>
                    <th class="table-light">Batas Kembali</th>
                    <td class="{{ $terlambatHari > 0 ? 'text-danger fw-bold' : '' }}">
                        {{ $transaksi->tanggal_kembali->format('d M Y') }}
                        @if ($terlambatHari > 0)
                            <span class="badge bg-danger ms-2">Terlambat {{ $terlambatHari }} hari</span>
                        @endif
                    </td>
                </tr>
                @if ($transaksi->tanggal_dikembalikan)
                <tr>
                    <th class="table-light">Tanggal Dikembalikan</th>
                    <td>{{ $transaksi->tanggal_dikembalikan->format('d M Y') }}</td>
                </tr>
                @endif
                <tr>
                    <th class="table-light">Status</th>
                    <td>
                        @if ($transaksi->status === 'Dipinjam')
                            <span class="badge bg-warning text-dark">Dipinjam</span>
                        @else
                            <span class="badge bg-success">Dikembalikan</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="table-light">Denda</th>
                    <td class="{{ $transaksi->denda > 0 ? 'text-danger fw-bold' : '' }}">
                        Rp {{ number_format($transaksi->denda, 0, ',', '.') }}
                        @if ($transaksi->denda > 0)
                            <small class="text-muted fw-normal">
                                ({{ $terlambatHari }} hari × Rp 5.000)
                            </small>
                        @endif
                    </td>
                </tr>
                @if ($transaksi->keterangan)
                <tr>
                    <th class="table-light">Keterangan</th>
                    <td>{{ $transaksi->keterangan }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            @if ($transaksi->status === 'Dipinjam')
                <form action="{{ route('transaksi.kembalikan', $transaksi->id) }}"
                      method="POST"
                      onsubmit="return confirm('Konfirmasi pengembalian buku?\nDenda akan dihitung otomatis.')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-box-arrow-in-left"></i> Kembalikan Buku
                        @if ($terlambatHari > 0)
                            <span class="badge bg-danger ms-1">
                                Denda Rp {{ number_format($terlambatHari * 5000, 0, ',', '.') }}
                            </span>
                        @endif
                    </button>
                </form>
            @else
                <span class="text-muted fst-italic">
                    <i class="bi bi-check2-circle text-success"></i> Buku sudah dikembalikan
                </span>
            @endif
        </div>
    </div>

</div>
</div>
@endsection