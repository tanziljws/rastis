@extends('layouts.app')

@section('title', $informasi->judul . ' - Informasi Sekolah')

@section('content')
<!-- Hero Section -->
<section class="informasi-detail-hero py-4" style="background: #2563eb;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-modern">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('informasi.index') }}">Informasi</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($informasi->judul, 30) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Informasi Detail Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <article class="informasi-article">
                    <!-- Header -->
                    <div class="informasi-article-header mb-4">
                        <div class="informasi-meta-top mb-3">
                            @if($informasi->kategori)
                                <span class="badge bg-primary me-2">{{ $informasi->kategori }}</span>
                            @endif
                            <span class="badge bg-{{ $informasi->status === 'published' ? 'success' : 'secondary' }}">
                                {{ ucfirst($informasi->status) }}
                            </span>
                        </div>
                        
                        <h1 class="informasi-article-title">{{ $informasi->judul }}</h1>
                        
                        <div class="informasi-article-meta">
                            <span class="meta-item">
                                <i class="far fa-calendar-alt me-2"></i>
                                {{ $informasi->tanggal_publish ? $informasi->tanggal_publish->format('d F Y, H:i') : $informasi->created_at->format('d F Y, H:i') }}
                            </span>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    @if($informasi->gambar)
                        <div class="informasi-featured-image mb-4">
                            <img src="{{ asset('storage/' . $informasi->gambar) }}" 
                                 alt="{{ $informasi->judul }}" 
                                 class="img-fluid rounded-3 shadow-sm">
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="informasi-article-content">
                        {!! $informasi->konten !!}
                    </div>

                    <!-- Footer Actions -->
                    <div class="informasi-article-footer mt-5 pt-4 border-top">
                        <a href="{{ route('informasi.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali ke Informasi
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>

<style>
/* Breadcrumb Modern */
.breadcrumb-modern {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-modern .breadcrumb-item a {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
}

.breadcrumb-modern .breadcrumb-item.active {
    color: white;
}

.breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
    content: '›';
    color: rgba(255,255,255,0.7);
    padding: 0 0.5rem;
}

/* Informasi Article */
.informasi-article {
    background: white;
    border-radius: 16px;
    padding: 2.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.informasi-article-header {
    border-bottom: 2px solid #f0f2f5;
    padding-bottom: 1.5rem;
}

.informasi-meta-top {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.informasi-article-title {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.informasi-article-meta {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.meta-item {
    color: #6b7280;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
}

.meta-item i {
    color: #2563eb;
}

.informasi-featured-image {
    border-radius: 12px;
    overflow: hidden;
}

.informasi-featured-image img {
    width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: cover;
}

.informasi-article-content {
    line-height: 1.8;
    font-size: 1.1rem;
    color: #374151;
}

.informasi-article-content h1,
.informasi-article-content h2,
.informasi-article-content h3,
.informasi-article-content h4,
.informasi-article-content h5,
.informasi-article-content h6 {
    color: #111827;
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.informasi-article-content h2 {
    font-size: 1.75rem;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 0.5rem;
}

.informasi-article-content p {
    margin-bottom: 1.5rem;
}

.informasi-article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.5rem 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.informasi-article-content ul,
.informasi-article-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.informasi-article-content li {
    margin-bottom: 0.5rem;
}

.informasi-article-content blockquote {
    border-left: 4px solid #2563eb;
    padding-left: 1.5rem;
    margin: 1.5rem 0;
    color: #6b7280;
    font-style: italic;
}

.informasi-article-content a {
    color: #2563eb;
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: all 0.2s ease;
}

.informasi-article-content a:hover {
    border-bottom-color: #2563eb;
}

.informasi-article-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-outline-primary {
    border: 2px solid #2563eb;
    color: #2563eb;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #2563eb;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .informasi-article {
        padding: 1.5rem;
    }
    
    .informasi-article-title {
        font-size: 1.5rem;
    }
    
    .informasi-featured-image img {
        max-height: 300px;
    }
}
</style>
@endsection
