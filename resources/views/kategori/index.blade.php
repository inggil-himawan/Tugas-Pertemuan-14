@extends('layouts.app')

@section('title', 'Kategori Buku')

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
        <li class="breadcrumb-item active">Kategori Buku</li>
    </ol>
</nav>

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">
            <i class="bi bi-tags-fill text-primary me-2"></i>Kategori Buku
        </h2>
        <p class="text-muted mb-0">Temukan buku berdasarkan kategori yang tersedia</p>
    </div>
    {{-- Search Form --}}
    <form id="formCariKategori" class="d-flex gap-2">
        <input type="text" name="q" id="inputCariKategori"
               class="form-control" placeholder="Cari kategori..."
               style="width:220px;" required>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search"></i>
        </button>
    </form>

   
</div>

{{-- Kartu Kategori --}}
<div class="row g-4">
    @foreach ($kategori_list as $kategori)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0 hover-shadow" style="transition: transform .2s;">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="rounded-3 bg-{{ $kategori['warna'] }} bg-opacity-10 text-{{ $kategori['warna'] }} d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;flex-shrink:0;">
                        <i class="bi {{ $kategori['ikon'] }} fs-4"></i>
                    </div>
                    <div>
                        <h5 class="card-title fw-bold mb-1">{{ $kategori['nama'] }}</h5>
                        <span class="badge bg-{{ $kategori['warna'] }}-subtle text-{{ $kategori['warna'] }} border border-{{ $kategori['warna'] }}-subtle">
                            {{ $kategori['jumlah_buku'] }} buku
                        </span>
                    </div>
                </div>
                <p class="card-text text-muted small">{{ $kategori['deskripsi'] }}</p>
            </div>
            <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-4">
                <a href="{{ route('kategori.show', $kategori['id']) }}" class="btn btn-{{ $kategori['warna'] }} btn-sm w-100">
                    <i class="bi bi-grid-3x3-gap me-1"></i>Lihat Buku
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>



@endsection

@push('scripts')
<script>
    var searchUrl = '{{ url("/kategori/search") }}';

    document.getElementById('formCariKategori').addEventListener('submit', function(e) {
        e.preventDefault();
        var q = document.getElementById('inputCariKategori').value.trim();
        if (q) {
            window.location = searchUrl + '/' + encodeURIComponent(q);
        }
    });
</script>
@endpush

@push('styles')
<style>
    .hover-shadow:hover { transform: translateY(-4px); box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.12) !important; }
</style>
@endpush