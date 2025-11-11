@extends('layouts.app')

@section('title', $agenda->judul . ' - Agenda Sekolah')

@section('content')
<!-- Hero Section -->
<section class="agenda-detail-hero py-4" style="background: #2563eb;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-modern">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('agenda.index') }}">Agenda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($agenda->judul, 30) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Agenda Detail Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <article class="agenda-article">
                    <!-- Header -->
                    <div class="agenda-article-header mb-4">
                        <div class="agenda-meta-top mb-3">
                            @if($agenda->status === 'aktif')
                                <span class="badge bg-success me-2">
                                    <i class="fas fa-check-circle me-1"></i>Aktif
                                </span>
                            @elseif($agenda->status === 'selesai')
                                <span class="badge bg-secondary me-2">
                                    <i class="fas fa-check me-1"></i>Selesai
                                </span>
                            @else
                                <span class="badge bg-danger me-2">
                                    <i class="fas fa-times me-1"></i>Dibatalkan
                                </span>
                            @endif
                        </div>
                        
                        <h1 class="agenda-article-title">{{ $agenda->judul }}</h1>
                    </div>

                    <!-- Meta Information -->
                    <div class="agenda-meta-modern mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="meta-item-modern">
                                    <div class="meta-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="meta-content">
                                        <strong>Tanggal Mulai</strong>
                                        <p class="mb-0">{{ $agenda->tanggal_mulai->format('d F Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($agenda->tanggal_selesai && $agenda->tanggal_selesai != $agenda->tanggal_mulai)
                            <div class="col-md-6">
                                <div class="meta-item-modern">
                                    <div class="meta-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="meta-content">
                                        <strong>Tanggal Selesai</strong>
                                        <p class="mb-0">{{ $agenda->tanggal_selesai->format('d F Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($agenda->waktu_mulai)
                            <div class="col-md-6">
                                <div class="meta-item-modern">
                                    <div class="meta-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="meta-content">
                                        <strong>Waktu</strong>
                                        <p class="mb-0">
                                            {{ $agenda->waktu_mulai }}
                                            @if($agenda->waktu_selesai)
                                                - {{ $agenda->waktu_selesai }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($agenda->lokasi)
                            <div class="col-md-6">
                                <div class="meta-item-modern">
                                    <div class="meta-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="meta-content">
                                        <strong>Lokasi</strong>
                                        <p class="mb-0">{{ $agenda->lokasi }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="agenda-article-content">
                        <h4 class="content-title">
                            <i class="fas fa-align-left me-2"></i>
                            Deskripsi
                        </h4>
                        <div class="content-text">
                            {!! nl2br(e($agenda->deskripsi)) !!}
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="agenda-article-footer mt-5 pt-4 border-top">
                        <a href="{{ route('agenda.index') }}" class="btn btn-outline-primary-modern">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali ke Agenda
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

/* Agenda Article */
.agenda-article {
    background: white;
    border-radius: 16px;
    padding: 2.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.agenda-article-header {
    border-bottom: 2px solid #f0f2f5;
    padding-bottom: 1.5rem;
}

.agenda-meta-top {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.agenda-article-title {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 0;
    line-height: 1.3;
}

/* Meta Modern */
.agenda-meta-modern {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
    border-left: 4px solid #2563eb;
}

.meta-item-modern {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: white;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.meta-item-modern:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.meta-icon {
    width: 45px;
    height: 45px;
    background: #2563eb;
    color: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.meta-content {
    flex: 1;
}

.meta-content strong {
    display: block;
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.meta-content p {
    color: #111827;
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
}

/* Content */
.agenda-article-content {
    margin-top: 2rem;
}

.content-title {
    color: #111827;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e5e7eb;
}

.content-text {
    line-height: 1.8;
    font-size: 1.1rem;
    color: #374151;
}

.agenda-article-footer {
    display: flex;
    justify-content: flex-start;
}

.btn-outline-primary-modern {
    border: 2px solid #2563eb;
    color: #2563eb;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-outline-primary-modern:hover {
    background: #2563eb;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .agenda-article {
        padding: 1.5rem;
    }
    
    .agenda-article-title {
        font-size: 1.5rem;
    }
    
    .meta-item-modern {
        flex-direction: column;
        text-align: center;
    }
    
    .meta-icon {
        margin: 0 auto;
    }
}
</style>
@endsection
