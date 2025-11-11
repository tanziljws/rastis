@extends('layouts.app')

@section('title', 'Informasi Sekolah - SMKN 4 Kota Bogor')

@section('content')
<!-- Hero Section for Informasi -->
<section class="informasi-hero py-5" style="background: #2563eb;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-white">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-newspaper me-3"></i>
                    Informasi Sekolah
                </h1>
                <p class="lead mb-0">Berita, pengumuman, dan informasi terbaru dari SMKN 4 Kota Bogor</p>
            </div>
        </div>
    </div>
</section>

<!-- Informasi Section -->
<section class="py-5">
    <div class="container">
        @if($informasis->count() > 0)
            <!-- Informasi Stats -->
            <div class="row mb-4">
                <div class="col-12">
                    <p class="text-muted mb-0">
                        Menampilkan <strong>{{ $informasis->firstItem() }}</strong> - <strong>{{ $informasis->lastItem() }}</strong> 
                        dari <strong>{{ $informasis->total() }}</strong> informasi
                    </p>
                </div>
            </div>

            <!-- Informasi Grid -->
            <div class="row g-4">
                @foreach($informasis as $informasi)
                    <div class="col-md-6 col-lg-4">
                        <div class="informasi-card h-100">
                            @if($informasi->gambar)
                                <div class="informasi-image-wrapper">
                                    <img src="{{ asset('storage/' . $informasi->gambar) }}" 
                                         class="informasi-image" 
                                         alt="{{ $informasi->judul }}">
                                </div>
                            @else
                                <div class="informasi-image-placeholder">
                                    <i class="fas fa-newspaper fa-3x"></i>
                                </div>
                            @endif
                            
                            <div class="informasi-card-body">
                                <div class="informasi-meta mb-2">
                                    @if($informasi->kategori)
                                        <span class="badge bg-primary mb-2">{{ $informasi->kategori }}</span>
                                    @endif
                                    <span class="badge bg-{{ $informasi->status === 'published' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($informasi->status) }}
                                    </span>
                                </div>
                                
                                <h5 class="informasi-card-title">{{ $informasi->judul }}</h5>
                                
                                <p class="informasi-card-excerpt">
                                    {{ Str::limit(strip_tags($informasi->konten), 120) }}
                                </p>
                                
                                <div class="informasi-card-footer">
                                    <small class="text-muted d-block mb-2">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $informasi->tanggal_publish ? $informasi->tanggal_publish->format('d M Y') : $informasi->created_at->format('d M Y') }}
                                    </small>
                                    <a href="{{ route('informasi.show', $informasi) }}" class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-eye me-2"></i>Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($informasis->hasPages())
                <div class="row mt-5">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $informasis->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-newspaper fa-5x text-muted"></i>
                </div>
                <h3 class="text-muted mb-3">Belum Ada Informasi</h3>
                <p class="text-muted mb-0">Belum ada informasi yang tersedia saat ini.</p>
            </div>
        @endif
    </div>
</section>

<style>
/* Informasi Hero */
.informasi-hero {
    position: relative;
    overflow: hidden;
}

.informasi-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

/* Informasi Card */
.informasi-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.informasi-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}

.informasi-image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 60%; /* 5:3 Aspect Ratio */
    overflow: hidden;
    background: #f8f9fa;
}

.informasi-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.informasi-card:hover .informasi-image {
    transform: scale(1.05);
}

.informasi-image-placeholder {
    width: 100%;
    padding-top: 60%;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.informasi-card-body {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.informasi-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.informasi-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 3rem;
}

.informasi-card-excerpt {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 1rem;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.informasi-card-footer {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.btn-primary {
    background: #2563eb;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}

/* Empty State */
.empty-state-icon {
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 768px) {
    .informasi-hero h1 {
        font-size: 2rem;
    }
    
    .informasi-image-wrapper {
        padding-top: 75%;
    }
}
</style>
@endsection
