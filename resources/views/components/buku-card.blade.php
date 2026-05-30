{{--
    Komponen: <x-buku-card :buku="$buku" />
    Props   : $buku (App\Models\Buku), $showActions (bool, default true)
--}}

<div class="card h-100 border-0 shadow-sm">

    {{-- Header warna sesuai kategori --}}
    <div class="card-header bg-{{ $kategoriColor() }} bg-opacity-10 d-flex justify-content-between align-items-center py-2">
        <span class="badge bg-{{ $kategoriColor() }}">
            <i class="bi bi-tag"></i>
            {{ $buku->kategori }}
        </span>

        {{-- Status stok --}}
        @if ($buku->stok > 0)
            <span class="badge bg-success-subtle text-success border border-success-subtle">
                <i class="bi bi-check-circle"></i> Tersedia
            </span>
        @else
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                <i class="bi bi-x-circle"></i> Habis
            </span>
        @endif
    </div>

    <div class="card-body d-flex flex-column">

        {{-- Cover / icon buku --}}
        <div class="text-center mb-3">
            <i class="bi bi-book-fill text-{{ $kategoriColor() }}" style="font-size: 3.5rem;"></i>
        </div>

        {{-- Judul --}}
        <h5 class="card-title text-center mb-1">
            <a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none stretched-link">
                {{ Str::limit($buku->judul, 50) }}
            </a>
        </h5>

        {{-- Pengarang --}}
        <p class="card-text text-muted text-center small mb-2">
            <i class="bi bi-person"></i>
            {{ $buku->pengarang }}
        </p>

        {{-- Penerbit & Tahun --}}
        <p class="card-text text-center small text-muted mb-3">
            <i class="bi bi-building"></i> {{ $buku->penerbit }}
            &nbsp;|&nbsp;
            <i class="bi bi-calendar"></i> {{ $buku->tahun_terbit }}
        </p>

        {{-- Harga --}}
        <p class="card-text text-center fw-bold text-primary fs-5 mb-2">
            {{ $buku->harga_format }}
        </p>

        {{-- Stok --}}
        <p class="card-text text-center small mb-0">
            <i class="bi bi-boxes text-secondary"></i>
            Stok:
            <strong class="{{ $buku->stok == 0 ? 'text-danger' : ($buku->stok <= 5 ? 'text-warning' : 'text-success') }}">
                {{ $buku->stok }}
            </strong>
            buku
        </p>

    </div>

    {{-- Footer: tombol aksi (opsional) --}}
    @if ($showActions)
        <div class="card-footer bg-transparent d-flex gap-2">
            <a href="{{ route('buku.show', $buku->id) }}"
               class="btn btn-sm btn-info text-white flex-fill">
                <i class="bi bi-eye"></i> Detail
            </a>
            <a href="{{ route('buku.edit', $buku->id) }}"
               class="btn btn-sm btn-warning flex-fill">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    @endif

</div>