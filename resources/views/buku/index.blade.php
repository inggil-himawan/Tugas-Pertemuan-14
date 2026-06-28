@extends('layouts.app')

@section('title', 'Daftar Buku')

@push('styles')
<style>
    .buku-check-label {
        transition: background-color 0.2s, border-color 0.2s;
        user-select: none;
        cursor: pointer;
    }
    .buku-check-label:hover {
        background-color: rgba(13, 110, 253, 0.2) !important;
    }
    .card:has(.buku-checkbox:checked) {
        border: 2px solid #0d6efd !important;
        box-shadow: 0 0 0 3px rgba(13,110,253,0.12);
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-book"></i>
        Daftar Buku
    </h1>
    <a href="{{ route('buku.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Buku
    </a>
</div>

{{-- Statistik Cards --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Buku</h6>
                        <h2 class="mb-0">{{ $totalBuku }}</h2>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-book-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Tersedia</h6>
                        <h2 class="mb-0">{{ $bukuTersedia }}</h2>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Habis</h6>
                        <h2 class="mb-0">{{ $bukuHabis }}</h2>
                    </div>
                    <div class="text-danger">
                        <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FORM SEARCH & FILTER ADVANCED --}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-light">
        <h6 class="mb-0">
            <i class="bi bi-search"></i> Cari & Filter Buku
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('buku.search') }}" method="GET">
            <div class="row g-3">

                {{-- Keyword --}}
                <div class="col-md-4">
                    <label for="keyword" class="form-label small fw-semibold">
                        <i class="bi bi-search"></i> Kata Kunci
                    </label>
                    <input type="text"
                           id="keyword"
                           name="keyword"
                           class="form-control"
                           placeholder="Judul, pengarang, atau penerbit..."
                           value="{{ $searchInput['keyword'] ?? '' }}">
                </div>

                {{-- Filter Kategori --}}
                <div class="col-md-2">
                    <label for="kategori" class="form-label small fw-semibold">
                        <i class="bi bi-tag"></i> Kategori
                    </label>
                    <select id="kategori" name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @isset($kategoris)
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat }}"
                                    {{ isset($searchInput['kategori']) && $searchInput['kategori'] == $kat ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                            @endforeach
                        @else
                            @foreach (['Programming','Database','Web Design','Networking','Data Science'] as $kat)
                                <option value="{{ $kat }}"
                                    {{ isset($kategori) && $kategori == $kat ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                {{-- Filter Tahun --}}
                <div class="col-md-2">
                    <label for="tahun" class="form-label small fw-semibold">
                        <i class="bi bi-calendar"></i> Tahun Terbit
                    </label>
                    <select id="tahun" name="tahun" class="form-select">
                        <option value="">Semua Tahun</option>
                        @isset($tahuns)
                            @foreach ($tahuns as $thn)
                                <option value="{{ $thn }}"
                                    {{ isset($searchInput['tahun']) && $searchInput['tahun'] == $thn ? 'selected' : '' }}>
                                    {{ $thn }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                {{-- Filter Ketersediaan --}}
                <div class="col-md-2">
                    <label for="stok" class="form-label small fw-semibold">
                        <i class="bi bi-boxes"></i> Ketersediaan
                    </label>
                    <select id="stok" name="stok" class="form-select">
                        <option value="semua"    {{ isset($searchInput['stok']) && $searchInput['stok'] == 'semua'    ? 'selected' : '' }}>Semua</option>
                        <option value="tersedia" {{ isset($searchInput['stok']) && $searchInput['stok'] == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="habis"    {{ isset($searchInput['stok']) && $searchInput['stok'] == 'habis'    ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>

            </div>
        </form>

        {{-- Info hasil pencarian --}}
        @isset($searchInput)
            @if (array_filter($searchInput))
                <div class="mt-3 alert alert-info py-2 mb-0">
                    <i class="bi bi-info-circle"></i>
                    Hasil pencarian ditemukan <strong>{{ $totalBuku }}</strong> buku.
                    <a href="{{ route('buku.index') }}" class="ms-2">
                        <i class="bi bi-x"></i> Reset Filter
                    </a>
                </div>
            @endif
        @endisset
    </div>
</div>

{{-- Filter Kategori --}}
<div class="card mb-4">
    <div class="card-body">
        <h6 class="card-title">
            <i class="bi bi-funnel"></i> Filter Kategori:
        </h6>
        <div class="btn-group" role="group">
            <a href="{{ route('buku.index') }}"
               class="btn btn-sm {{ !isset($kategori) ? 'btn-primary' : 'btn-outline-primary' }}">
                Semua
            </a>
            @foreach (['Programming','Database','Web Design','Networking','Data Science'] as $kat)
                <a href="{{ route('buku.kategori', $kat) }}"
                   class="btn btn-sm {{ isset($kategori) && $kategori == $kat ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $kat }}
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- FORM BULK DELETE — semua card buku ada DI DALAM form ini --}}
<form action="{{ route('buku.bulk-delete') }}" method="POST" id="bulk-delete-form">
    @csrf

    {{-- Toolbar --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        {{-- Kiri: Export + Pilih Semua --}}
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('buku.export') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-arrow-down"></i> Export CSV
            </a>
            <label class="d-flex align-items-center gap-2 mb-0" style="cursor:pointer;">
                <input type="checkbox" id="select-all"
                       class="form-check-input mt-0"
                       style="width:1.2em;height:1.2em;cursor:pointer;">
                <span class="fw-semibold">Pilih Semua</span>
            </label>
        </div>

        {{-- Kanan: Tombol hapus massal (muncul jika ada yang dicentang) --}}
        <button type="button" id="btn-bulk-delete" class="btn btn-danger" style="display:none;">
            <i class="bi bi-trash"></i> Hapus yang Dipilih
            (<span id="selected-count">0</span>)
        </button>
    </div>

    {{-- Daftar Buku --}}
    @forelse ($bukus as $buku)
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-start">

                    {{-- Kolom 1: Checkbox + Ikon + Badge --}}
                    <div class="col-md-2 text-center">
                        <div class="mb-2">
                            <label class="buku-check-label d-inline-flex align-items-center gap-1 px-2 py-1 rounded-2 border border-primary bg-primary bg-opacity-10">
                                <input type="checkbox"
                                       name="buku_ids[]"
                                       value="{{ $buku->id }}"
                                       class="form-check-input buku-checkbox mt-0"
                                       style="width:1.1em;height:1.1em;cursor:pointer;">
                                <span class="small text-primary fw-semibold" style="font-size:0.75rem;">Pilih</span>
                            </label>
                        </div>
                        <i class="bi bi-book text-primary" style="font-size: 4rem;"></i>
                        <div class="mt-2">
                            <span class="badge bg-{{ $buku->kategori == 'Programming' ? 'primary' : ($buku->kategori == 'Database' ? 'success' : ($buku->kategori == 'Web Design' ? 'info' : ($buku->kategori == 'Networking' ? 'warning' : 'danger'))) }}">
                                {{ $buku->kategori }}
                            </span>
                        </div>
                    </div>

                    {{-- Kolom 2: Info Buku --}}
                    <div class="col-md-7">
                        <h5 class="card-title">
                            <a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none">
                                {{ $buku->judul }}
                            </a>
                        </h5>
                        <p class="card-text text-muted mb-2">
                            <i class="bi bi-person"></i> {{ $buku->pengarang }} |
                            <i class="bi bi-building"></i> {{ $buku->penerbit }} |
                            <i class="bi bi-calendar"></i> {{ $buku->tahun_terbit }}
                        </p>
                        @if ($buku->isbn)
                            <p class="card-text small text-muted mb-1">
                                <i class="bi bi-upc"></i> ISBN: {{ $buku->isbn }}
                            </p>
                        @endif
                        @if ($buku->deskripsi)
                            <p class="card-text">{{ Str::limit($buku->deskripsi, 150) }}</p>
                        @endif
                    </div>

                    {{-- Kolom 3: Harga + Stok + Aksi --}}
                    <div class="col-md-3 text-end">
                        <h4 class="text-primary mb-2">{{ $buku->harga_format }}</h4>
                        <div class="mb-3">
                            @if ($buku->stok > 0)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Tersedia
                                </span>
                                <div class="text-muted small mt-1">Stok: {{ $buku->stok }} buku</div>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle"></i> Habis
                                </span>
                            @endif
                        </div>
                        <div class="btn-group-vertical d-grid gap-2">
                            <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                            <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            {{-- Hapus per buku (form terpisah di dalam form bulk) --}}
                            <button type="button"
                                    class="btn btn-sm btn-danger btn-delete"
                                    data-id="{{ $buku->id }}"
                                    data-judul="{{ $buku->judul }}">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            Tidak ada data buku
            @isset($kategori)
                dengan kategori <strong>{{ $kategori }}</strong>
            @endisset
        </div>
    @endforelse

</form>
{{-- END FORM BULK DELETE --}}

{{-- Form hapus per buku — di LUAR form bulk, di-submit lewat JS --}}
<form id="single-delete-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@if ($bukus->count() > 0)
    <div class="text-center mt-4">
        <p class="text-muted">
            Menampilkan {{ $bukus->count() }} buku
            @isset($kategori)
                dari kategori <strong>{{ $kategori }}</strong>
            @endisset
        </p>
    </div>
@endif

@endsection

@push('scripts')
<script>
    // Select All
    document.getElementById('select-all').addEventListener('change', function () {
        document.querySelectorAll('.buku-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
        updateBulkBtn();
    });

    // Per-item checkbox 
    document.querySelectorAll('.buku-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkBtn);
    });

    function updateBulkBtn() {
        const checked = document.querySelectorAll('.buku-checkbox:checked').length;
        const total   = document.querySelectorAll('.buku-checkbox').length;
        document.getElementById('selected-count').textContent = checked;
        document.getElementById('btn-bulk-delete').style.display = checked > 0 ? 'inline-block' : 'none';
        document.getElementById('select-all').checked       = (checked === total && total > 0);
        document.getElementById('select-all').indeterminate = (checked > 0 && checked < total);
    }

    // Konfirmasi Bulk Delete
    document.getElementById('btn-bulk-delete').addEventListener('click', function () {
        const count = document.querySelectorAll('.buku-checkbox:checked').length;
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus ${count} buku yang dipilih?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('bulk-delete-form').submit();
            }
        });
    });

    // Hapus Per Buku (pakai form terpisah di luar bulk form) 
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const id    = this.getAttribute('data-id');
            const judul = this.getAttribute('data-judul');
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus buku "${judul}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.getElementById('single-delete-form');
                    form.action = `/buku/${id}`;
                    form.submit();
                }
            });
        });
    });
</script>
@endpush