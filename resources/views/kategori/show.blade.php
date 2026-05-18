@extends('layouts.app')

@section('title', 'Kategori — ' . $kategori['nama'])

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori Buku</a></li>
        <li class="breadcrumb-item active">{{ $kategori['nama'] }}</li>
    </ol>
</nav>

{{-- Header Kategori --}}
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg,#0d6efd 0%,#0a58ca 100%);">
    <div class="card-body text-white p-4 d-flex align-items-center gap-4">
        <div class="rounded-3 bg-white bg-opacity-20 d-flex align-items-center justify-content-center"
             style="width:70px;height:70px;flex-shrink:0;">
            <i class="bi {{ $kategori['ikon'] }} text-primary" style="font-size:2rem;"></i>
        </div>
        <div>
            <h3 class="fw-bold mb-1">{{ $kategori['nama'] }}</h3>
            <p class="mb-1 opacity-75">{{ $kategori['deskripsi'] }}</p>
            <span class="badge bg-white text-primary fw-semibold">
                <i class="bi bi-journal-bookmark me-1"></i>{{ count($buku_list) }} judul buku
            </span>
        </div>
    </div>
</div>

{{-- Daftar Buku --}}
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-list-ul text-primary me-2"></i>Daftar Buku dalam Kategori Ini
        </h5>
        <span class="badge bg-primary rounded-pill">{{ count($buku_list) }}</span>
    </div>
    <div class="card-body p-0">
        @if (count($buku_list) > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" width="50">No</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center pe-4">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($buku_list as $i => $buku)
                    <tr>
                        <td class="ps-4 text-muted">{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $buku['judul'] }}</td>
                        <td class="text-muted">{{ $buku['pengarang'] }}</td>
                        <td class="text-center text-muted">{{ $buku['tahun'] }}</td>
                        <td class="text-center pe-4">
                            @if ($buku['stok'] > 5)
                                <span class="badge bg-success">{{ $buku['stok'] }}</span>
                            @elseif ($buku['stok'] > 0)
                                <span class="badge bg-warning text-dark">{{ $buku['stok'] }}</span>
                            @else
                                <span class="badge bg-danger">Habis</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox display-4 d-block mb-3"></i>
            <p>Belum ada buku dalam kategori ini.</p>
        </div>
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Kategori
    </a>
</div>

@endsection
