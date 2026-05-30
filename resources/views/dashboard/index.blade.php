@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- ============================================================
     HEADER
     ============================================================ --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">
            <i class="bi bi-speedometer2 text-primary"></i>
            Dashboard
        </h1>
        <p class="text-muted mb-0">
            Ringkasan statistik Sistem Manajemen Perpustakaan
        </p>
    </div>
    <small class="text-muted">
        <i class="bi bi-clock"></i>
        {{ now()->format('d M Y, H:i') }} WIB
    </small>
</div>

{{-- ============================================================
     STATISTIK BUKU
     ============================================================ --}}
<h5 class="text-muted mb-3">
    <i class="bi bi-book"></i> Statistik Buku
</h5>

<div class="row g-3 mb-4">
    {{-- Total Buku --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-book-fill text-primary fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small">Total Koleksi Buku</p>
                    <h2 class="mb-0 fw-bold">{{ $totalBuku }}</h2>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('buku.index') }}" class="btn btn-sm btn-outline-primary w-100">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Buku Tersedia --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small">Buku Tersedia</p>
                    <h2 class="mb-0 fw-bold text-success">{{ $bukuTersedia }}</h2>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <div class="progress" style="height: 6px;">
                    @php
                        $persenTersedia = $totalBuku > 0 ? round(($bukuTersedia / $totalBuku) * 100) : 0;
                    @endphp
                    <!-- <div class="progress-bar bg-success"
                         style="width: {{ $persenTersedia }}%"
                         title="{{ $persenTersedia }}% tersedia">
                    </div> -->
                    <div class="progress-bar bg-success"
                        @style(['width: ' . $persenTersedia . '%'])
                        title="{{ $persenTersedia }}% tersedia">
                    </div>
                </div>
                <small class="text-muted">{{ $persenTersedia }}% dari total koleksi</small>
            </div>
        </div>
    </div>

    {{-- Buku Habis --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                    <i class="bi bi-x-circle-fill text-danger fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small">Stok Habis</p>
                    <h2 class="mb-0 fw-bold text-danger">{{ $bukuHabis }}</h2>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                @if ($bukuHabis > 0)
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                        <i class="bi bi-exclamation-triangle"></i>
                        Perlu restok segera
                    </span>
                @else
                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                        <i class="bi bi-check-circle"></i>
                        Semua stok aman
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     STATISTIK ANGGOTA
     ============================================================ --}}
<h5 class="text-muted mb-3">
    <i class="bi bi-people"></i> Statistik Anggota
</h5>

<div class="row g-3 mb-5">
    {{-- Total Anggota --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                    <i class="bi bi-people-fill text-info fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small">Total Anggota</p>
                    <h2 class="mb-0 fw-bold">{{ $totalAnggota }}</h2>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('anggota.index') }}" class="btn btn-sm btn-outline-info w-100">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Anggota Aktif --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-person-check-fill text-primary fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small">Anggota Aktif</p>
                    <h2 class="mb-0 fw-bold text-primary">{{ $anggotaAktif }}</h2>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <div class="progress" style="height: 6px;">
                    @php
                        $persenAktif = $totalAnggota > 0 ? round(($anggotaAktif / $totalAnggota) * 100) : 0;
                    @endphp
                    <!-- <div class="progress-bar bg-primary"
                        style="{{ 'width: ' . $persenAktif . '%' }}">
                    </div> -->
                    <div class="progress-bar bg-primary"
                        @style(['width: ' . $persenAktif . '%'])>
                    </div>
                </div>
                <small class="text-muted">{{ $persenAktif }}% dari total anggota</small>
            </div>
        </div>
    </div>

    {{-- Anggota Nonaktif --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-secondary bg-opacity-10 p-3">
                    <i class="bi bi-person-x-fill text-secondary fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small">Anggota Nonaktif</p>
                    <h2 class="mb-0 fw-bold text-secondary">{{ $anggotaNonaktif }}</h2>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i>
                    Perlu verifikasi ulang
                </small>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     TABEL DATA TERBARU (side-by-side)
     ============================================================ --}}
<div class="row g-4 mb-5">

    {{-- 5 Buku Terbaru --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="bi bi-book"></i> 5 Buku Terbaru
                </h6>
                <a href="{{ route('buku.index') }}" class="btn btn-sm btn-light text-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse ($bukuTerbaru as $buku)
                        <a href="{{ route('buku.show', $buku->id) }}"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                            <div>
                                <p class="mb-0 fw-semibold">
                                    {{ Str::limit($buku->judul, 35) }}
                                </p>
                                <small class="text-muted">
                                    <i class="bi bi-person"></i> {{ $buku->pengarang }}
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $buku->stok > 0 ? 'success' : 'danger' }}">
                                    {{ $buku->stok > 0 ? 'Tersedia' : 'Habis' }}
                                </span>
                                <div class="text-muted small mt-1">
                                    {{ $buku->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                            Belum ada data buku
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- 5 Anggota Terbaru --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="bi bi-people"></i> 5 Anggota Terbaru
                </h6>
                <a href="{{ route('anggota.index') }}" class="btn btn-sm btn-light text-success">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse ($anggotaTerbaru as $anggota)
                        <a href="{{ route('anggota.show', $anggota->id) }}"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-circle text-{{ $anggota->jenis_kelamin == 'Laki-laki' ? 'primary' : 'danger' }} fs-4"></i>
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $anggota->nama }}</p>
                                    <small class="text-muted">
                                        <i class="bi bi-envelope"></i> {{ $anggota->email }}
                                    </small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $anggota->status == 'Aktif' ? 'success' : 'secondary' }}">
                                    {{ $anggota->status }}
                                </span>
                                <div class="text-muted small mt-1">
                                    {{ $anggota->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                            Belum ada data anggota
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ============================================================
     QUICK LINKS
     ============================================================ --}}
<h5 class="text-muted mb-3">
    <i class="bi bi-lightning"></i> Quick Links
</h5>

<div class="row g-3">
    <div class="col-6 col-md-3">
        <a href="{{ route('buku.create') }}" class="card text-decoration-none border-0 shadow-sm h-100 text-center p-3">
            <i class="bi bi-plus-circle-fill text-primary fs-2 mb-2"></i>
            <span class="fw-semibold">Tambah Buku</span>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('anggota.create') }}" class="card text-decoration-none border-0 shadow-sm h-100 text-center p-3">
            <i class="bi bi-person-plus-fill text-success fs-2 mb-2"></i>
            <span class="fw-semibold">Tambah Anggota</span>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('buku.index') }}" class="card text-decoration-none border-0 shadow-sm h-100 text-center p-3">
            <i class="bi bi-book-fill text-info fs-2 mb-2"></i>
            <span class="fw-semibold">Kelola Buku</span>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('anggota.index') }}" class="card text-decoration-none border-0 shadow-sm h-100 text-center p-3">
            <i class="bi bi-people-fill text-warning fs-2 mb-2"></i>
            <span class="fw-semibold">Kelola Anggota</span>
        </a>
    </div>
</div>

@endsection