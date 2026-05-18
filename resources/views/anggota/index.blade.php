@extends('layouts.app')

@section('title', 'Daftar Anggota Perpustakaan')

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active">Anggota</li>
    </ol>
</nav>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            <i class="bi bi-people-fill text-primary me-2"></i>Daftar Anggota Perpustakaan
        </h2>
        <p class="text-muted mb-0">Total: <strong>{{ count($anggota_list) }}</strong> anggota terdaftar</p>
    </div>
    <div>
    <span class="badge bg-primary fs-6 px-3 py-2">
        <i class="bi bi-person-check me-1"></i>
        {{-- Bungkus dengan collect() agar aman --}}
        {{ collect($anggota_list)->where('status', 'Aktif')->count() }} Aktif
    </span>
</div>
</div>

{{-- Tabel Anggota --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-3" width="50">No</th>
                        <th>Kode Anggota</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anggota_list as $index => $anggota)
                    <tr>
                        <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                        <td>
                            <span class="badge bg-secondary font-monospace">{{ $anggota['kode'] }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                     style="width:36px;height:36px;font-size:0.85rem;flex-shrink:0;">
                                    {{ strtoupper(substr($anggota['nama'], 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $anggota['nama'] }}</div>
                                    <div class="text-muted small">{{ $anggota['alamat'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted small">{{ $anggota['email'] }}</td>
                        <td class="text-muted small">{{ $anggota['telepon'] }}</td>
                        <td class="text-center">
                            @if ($anggota['status'] === 'Aktif')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3">
                                    <i class="bi bi-check-circle me-1"></i>Aktif
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3">
                                    <i class="bi bi-x-circle me-1"></i>Non-Aktif
                                </span>
                            @endif
                        </td>
                        <td class="text-center pe-3">
                            <a href="{{ route('anggota.show', $anggota['id']) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
