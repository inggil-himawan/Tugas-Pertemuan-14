@extends('layouts.app')

@section('title', 'Hasil Pencarian — ' . $keyword)

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori Buku</a></li>
        <li class="breadcrumb-item active">Pencarian</li>
    </ol>
</nav>

{{-- Header Hasil --}}
<div class="mb-4">
    <h2 class="fw-bold mb-1">
        <i class="bi bi-search text-primary me-2"></i>Hasil Pencarian
    </h2>
    <p class="text-muted mb-0">
        Menampilkan hasil untuk kata kunci:
        <strong class="text-primary">"{{ $keyword }}"</strong>
        — ditemukan <strong>{{ count($hasil) }}</strong> kategori
    </p>
</div>

{{-- Search lagi --}}
<div class="card border-0 bg-light mb-4">
    <div class="card-body py-3">
        <form class="d-flex gap-2" method="GET"
              onsubmit="event.preventDefault(); window.location = '{{ url('/kategori/search') }}/' + encodeURIComponent(this.q.value);">
            <input type="text" name="q" class="form-control" value="{{ $keyword }}"
                   placeholder="Cari kategori..." required>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-search me-1"></i>Cari
            </button>
            <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">Reset</a>
        </form>
    </div>
</div>

{{-- Hasil --}}
@if ($hasil->isNotEmpty())
    <div class="row g-4">
        @foreach ($hasil as $kategori)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-2 border-primary-subtle">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-3 bg-{{ $kategori['warna'] }} bg-opacity-10 text-{{ $kategori['warna'] }} d-flex align-items-center justify-content-center"
                             style="width:48px;height:48px;flex-shrink:0;">
                            <i class="bi {{ $kategori['ikon'] }} fs-5"></i>
                        </div>
                        <div>
                            {{-- Highlight keyword dalam nama --}}
                            <h5 class="card-title fw-bold mb-1">
                                {!! preg_replace('/(' . preg_quote(e($keyword), '/') . ')/i',
                                    '<mark class="px-0 bg-warning">$1</mark>',
                                    e($kategori['nama'])) !!}
                            </h5>
                            <span class="badge bg-{{ $kategori['warna'] }}-subtle text-{{ $kategori['warna'] }} border border-{{ $kategori['warna'] }}-subtle">
                                {{ $kategori['jumlah_buku'] }} buku
                            </span>
                        </div>
                    </div>
                    <p class="card-text text-muted small">
                        {!! preg_replace('/(' . preg_quote(e($keyword), '/') . ')/i',
                            '<mark class="px-0 bg-warning">$1</mark>',
                            e($kategori['deskripsi'])) !!}
                    </p>
                </div>
                <div class="card-footer bg-transparent border-top-0 pb-3 px-4">
                    <a href="{{ route('kategori.show', $kategori['id']) }}" class="btn btn-{{ $kategori['warna'] }} btn-sm w-100">
                        <i class="bi bi-grid-3x3-gap me-1"></i>Lihat Buku
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5 text-muted">
        <i class="bi bi-search display-3 d-block mb-3 opacity-25"></i>
        <h5 class="fw-semibold">Tidak Ditemukan</h5>
        <p>Tidak ada kategori yang cocok dengan kata kunci <strong>"{{ $keyword }}"</strong>.</p>
        <a href="{{ route('kategori.index') }}" class="btn btn-primary mt-2">
            <i class="bi bi-grid me-1"></i>Lihat Semua Kategori
        </a>
    </div>
@endif

@endsection
