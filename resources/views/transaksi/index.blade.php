@extends('layouts.app')
@section('title', 'Daftar Transaksi')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- TUGAS 3: Alert daftar anggota terlambat --}}
@php
    $terlambatList = $transaksis->filter(fn($t) =>
        $t->status === 'Dipinjam' && now()->gt($t->tanggal_kembali)
    );
@endphp

@if ($terlambatList->count() > 0)
    <div class="alert alert-danger">
        <strong><i class="bi bi-alarm me-1"></i> {{ $terlambatList->count() }} buku terlambat dikembalikan:</strong>
        <ul class="mb-0 mt-1">
            @foreach ($terlambatList as $t)
                <li>
                    <strong>{{ $t->anggota->nama }}</strong> — {{ $t->buku->judul }}
                    <span class="badge bg-danger">
                        Terlambat {{ (int) ceil($t->tanggal_kembali->floatDiffInDays(now())) }} hari
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3"><i class="bi bi-arrow-left-right me-2"></i> Daftar Transaksi</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('transaksi.laporan') }}" class="btn btn-outline-secondary">
            <i class="bi bi-file-earmark-bar-graph"></i> Laporan
        </a>
        <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Pinjam Buku
        </a>
    </div>
</div>

{{-- TUGAS 3: Card statistik termasuk card terlambat --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-primary text-center">
            <div class="card-body py-3">
                <h6 class="text-muted small">Total</h6>
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
                <h6 class="text-muted small">Terlambat</h6>
                <h2 class="mb-0 text-danger">{{ $terlambatList->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Batas Kembali</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $transaksi)
                        @php
                            $isLate = $transaksi->status === 'Dipinjam'
                                   && now()->gt($transaksi->tanggal_kembali);
                            $hariLate = $isLate ? (int) ceil($transaksi->tanggal_kembali->floatDiffInDays(now())) : 0;
                        @endphp
                        <tr class="{{ $isLate ? 'table-danger' : '' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ $transaksi->kode_transaksi }}</code></td>
                            <td>{{ $transaksi->anggota->nama }}</td>
                            <td>{{ $transaksi->buku->judul }}</td>
                            <td>{{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                            <td>
                                {{ $transaksi->tanggal_kembali->format('d M Y') }}
                                {{-- TUGAS 3: Badge terlambat --}}
                                @if ($isLate)
                                    <span class="badge bg-danger ms-1">
                                        <i class="bi bi-alarm"></i> Terlambat {{ $hariLate }} hari
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($transaksi->status === 'Dipinjam')
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                @else
                                    <span class="badge bg-success">Dikembalikan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('transaksi.show', $transaksi->id) }}"
                                   class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection