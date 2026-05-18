@extends('layouts.app')

@section('title', 'Detail Anggota — ' . $anggota['nama'])

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('anggota.index') }}">Anggota</a></li>
        <li class="breadcrumb-item active">{{ $anggota['nama'] }}</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Card Header --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold"
                         style="width:52px;height:52px;font-size:1.4rem;flex-shrink:0;">
                        {{ strtoupper(substr($anggota['nama'], 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">{{ $anggota['nama'] }}</h4>
                        <small class="opacity-75 font-monospace">{{ $anggota['kode'] }}</small>
                    </div>
                    <div class="ms-auto">
                        @if ($anggota['status'] === 'Aktif')
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>Aktif
                            </span>
                        @else
                            <span class="badge bg-danger fs-6 px-3 py-2">
                                <i class="bi bi-x-circle me-1"></i>Non-Aktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-semibold mb-3 small">
                    <i class="bi bi-person-lines-fill me-1"></i>Informasi Anggota
                </h6>
                <table class="table table-borderless">
                    <tr>
                        <th width="160" class="text-muted fw-normal">Kode Anggota</th>
                        <td>: <span class="badge bg-secondary font-monospace">{{ $anggota['kode'] }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Nama Lengkap</th>
                        <td>: <strong>{{ $anggota['nama'] }}</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Email</th>
                        <td>: <a href="mailto:{{ $anggota['email'] }}">{{ $anggota['email'] }}</a></td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Telepon</th>
                        <td>: {{ $anggota['telepon'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Alamat</th>
                        <td>: {{ $anggota['alamat'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Pekerjaan</th>
                        <td>: {{ $anggota['pekerjaan'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Tanggal Daftar</th>
                        <td>: {{ $anggota['tanggal_daftar'] }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-normal">Status</th>
                        <td>:
                            @if ($anggota['status'] === 'Aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Non-Aktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="card-footer bg-light d-flex gap-2">
                <a href="{{ route('anggota.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                </a>
            </div>
        </div>

    </div>
</div>

@endsection
