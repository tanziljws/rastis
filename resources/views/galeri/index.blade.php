@extends('layouts.app')

@section('title', 'Galeri Foto - SMKN 4 Kota Bogor')

@section('content')
<!-- Hero Section for Gallery -->
<section class="gallery-hero py-5" style="background: #2563eb;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-white">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-images me-3"></i>
                    Galeri Foto Sekolah
                </h1>
                <p class="lead mb-0">Kumpulan momen berharga dan dokumentasi kegiatan SMKN 4 Kota Bogor</p>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Search Section -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <form method="GET" action="{{ route('galeri.index') }}" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="search" class="form-label">
                        <i class="fas fa-search me-2"></i>Cari Foto
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           placeholder="Cari berdasarkan judul foto..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label for="kategori" class="form-label">
                        <i class="fas fa-tag me-2"></i>Kategori
                    </label>
                    <select class="form-select" id="kategori" name="kategori">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-5">
    <div class="container">
        @if($albumsPaginated->count() > 0)
            <!-- Gallery Stats -->
            <div class="row mb-4">
                <div class="col-12">
                    <p class="text-muted mb-0">
                        Menampilkan <strong>{{ $albumsPaginated->firstItem() }}</strong> - <strong>{{ $albumsPaginated->lastItem() }}</strong> 
                        dari <strong>{{ $albumsPaginated->total() }}</strong> album
                    </p>
                </div>
            </div>

            <!-- Album Grid -->
            <div class="row g-4" id="gallery-grid">
                @foreach($albumsPaginated as $album)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ route('galeri.album', ['judul' => urlencode($album['judul'])]) }}" 
                           class="album-card-link"
                           title="{{ $album['judul'] === 'photo_' . $album['first_foto']->id ? 'Tanpa Judul' : $album['judul'] }}">
                            <div class="album-card h-100">
                                <!-- Album Thumbnail -->
                                <div class="album-thumbnail-wrapper">
                                    <img src="{{ $album['first_foto']->thumbnail_url }}" 
                                         alt="{{ $album['judul'] }}"
                                         class="album-thumbnail"
                                         loading="lazy"
                                         data-src="{{ $album['first_foto']->file_url }}"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect fill=\'%232563eb\' width=\'400\' height=\'300\'/%3E%3Ctext fill=\'%23ffffff\' font-family=\'Arial\' font-size=\'16\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3EGambar Tidak Ditemukan%3C/text%3E%3C/svg%3E';">
                                    <div class="album-overlay">
                                        <div class="album-overlay-content">
                                            <i class="fas fa-images fa-2x mb-2"></i>
                                            <p class="mb-0">Lihat Album</p>
                                        </div>
                                    </div>
                                    <!-- Photo Count Badge -->
                                    <div class="album-count-badge">
                                        <i class="fas fa-images me-1"></i>
                                        {{ $album['count'] }} Foto
                                    </div>
                                </div>
                                
                                <!-- Album Info -->
                                <div class="album-card-body">
                                    <h5 class="album-title">{{ $album['judul'] === 'photo_' . $album['first_foto']->id ? 'Tanpa Judul' : $album['judul'] }}</h5>
                                    @if($album['kategori'])
                                        <span class="badge bg-primary mb-2">{{ $album['kategori']->judul }}</span>
                                    @endif
                                    
                                    <!-- Like & Comment Counters -->
                                    <div class="album-stats mb-2">
                                        <span class="album-stat-item">
                                            <i class="fas fa-heart text-danger me-1"></i>
                                            <small>{{ $album['total_likes'] ?? 0 }}</small>
                                        </span>
                                        <span class="album-stat-item ms-3">
                                            <i class="fas fa-comment text-primary me-1"></i>
                                            <small>{{ $album['total_comments'] ?? 0 }}</small>
                                        </span>
                                    </div>
                                    
                                    <p class="album-date text-muted mb-0">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $album['created_at']->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($albumsPaginated->hasPages())
                <div class="row mt-5">
                    <div class="col-12 d-flex justify-content-center">
                        <div class="simple-pagination">
                            {{ $albumsPaginated->appends(request()->query())->links('pagination::simple') }}
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-images fa-5x text-muted"></i>
                </div>
                <h3 class="text-muted mb-3">Belum Ada Album</h3>
                <p class="text-muted mb-4">
                    @if(request('search') || request('kategori'))
                        Tidak ada album yang sesuai dengan filter yang Anda pilih.
                    @else
                        Album foto sekolah akan ditampilkan di sini.
                    @endif
                </p>
                @if(request('search') || request('kategori'))
                    <a href="{{ route('galeri.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Semua Album
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>

<style>
/* Gallery Hero */
.gallery-hero {
    position: relative;
    overflow: hidden;
}

.gallery-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

/* Album Card */
.album-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.album-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    flex-direction: column;
}

.album-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}

.album-thumbnail-wrapper {
    position: relative;
    width: 100%;
    padding-top: 75%; /* 4:3 Aspect Ratio */
    overflow: hidden;
    background: #f8f9fa;
}

.album-thumbnail {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.album-card:hover .album-thumbnail {
    transform: scale(1.1);
}

.album-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(37, 99, 235, 0.85);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.album-card:hover .album-overlay {
    opacity: 1;
}

.album-overlay-content {
    color: white;
    text-align: center;
}

.album-count-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(37, 99, 235, 0.95);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    display: flex;
    align-items: center;
}

.album-card-body {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.album-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 3rem;
}

.album-stats {
    display: flex;
    align-items: center;
}

.album-stat-item {
    display: inline-flex;
    align-items: center;
    font-size: 0.875rem;
    color: #6b7280;
}

.album-date {
    font-size: 0.875rem;
    margin-top: auto;
}

/* Empty State */
.empty-state-icon {
    opacity: 0.5;
}

/* Simple Pagination - No Arrows, Just Text */
.simple-pagination {
    width: 100%;
    max-width: 400px;
}

.simple-pagination .pagination {
    justify-content: center;
    margin: 0;
    gap: 0.3rem;
    font-size: 0.875rem;
}

.simple-pagination .pagination .page-link {
    padding: 0.4rem 0.6rem;
    font-size: 0.875rem;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    background: white;
    transition: all 0.15s ease;
    text-align: center;
    text-decoration: none;
}

.simple-pagination .pagination .page-link:hover {
    background: #f3f4f6;
    border-color: #2563eb;
    color: #2563eb;
}

.simple-pagination .pagination .page-item.active .page-link {
    background: #2563eb;
    border-color: #2563eb;
    color: white;
    font-weight: 500;
}

.simple-pagination .pagination .page-item.disabled .page-link {
    background: #f9fafb;
    border-color: #e5e7eb;
    color: #d1d5db;
    cursor: not-allowed;
    opacity: 0.6;
}

.simple-pagination .pagination .page-item.disabled .page-link:hover {
    background: #f9fafb;
    border-color: #e5e7eb;
    color: #d1d5db;
}

/* Hide all icons/arrows - show only text */
.simple-pagination .pagination .page-link svg,
.simple-pagination .pagination .page-link i,
.simple-pagination .pagination .page-link span[aria-hidden="true"] {
    display: none !important;
}

/* Show only text for Previous/Next */
.simple-pagination .pagination .page-link[aria-label*="Previous"]::before {
    content: "Prev";
    display: inline;
}

.simple-pagination .pagination .page-link[aria-label*="Next"]::after {
    content: "Next";
    display: inline;
}

/* Responsive */
@media (max-width: 768px) {
    .gallery-hero h1 {
        font-size: 2rem;
    }
    
    .album-thumbnail-wrapper {
        padding-top: 100%; /* Square on mobile */
    }
    
    .simple-pagination .pagination {
        gap: 0.2rem;
        font-size: 0.8rem;
    }
    
    .simple-pagination .pagination .page-link {
        padding: 0.35rem 0.5rem;
        font-size: 0.8rem;
    }
}
</style>
@endsection
