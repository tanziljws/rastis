@extends('layouts.app')

@section('title', 'Profil Sekolah - SMKN 4 Kota Bogor')

@section('content')
<!-- Hero Section -->
<section class="profil-hero py-5" style="background: #2563eb;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-white">
                @if($profil && $profil->logo)
                    <img src="{{ asset('storage/' . $profil->logo) }}" 
                         alt="{{ $profil->nama_sekolah }}" 
                         class="profil-logo mb-4">
                @endif
                <h1 class="display-4 fw-bold mb-3">{{ $profil->nama_sekolah ?? 'SMK NEGERI 4 BOGOR' }}</h1>
                <p class="lead mb-0">Mewujudkan Generasi Unggul dan Berkarakter</p>
            </div>
        </div>
    </div>
</section>

@if($profil)
    <!-- About Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="profil-card-modern shadow-sm">
                        <div class="profil-card-header-modern">
                            <h2 class="mb-0">
                                <i class="fas fa-school me-2"></i>
                                Tentang Sekolah
                            </h2>
                        </div>
                        <div class="profil-card-body-modern">
                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <div class="profil-description">
                                        {!! nl2br(e($profil->deskripsi ?? 'Deskripsi sekolah akan ditampilkan di sini.')) !!}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="profil-stats">
                                        <div class="stat-item">
                                            <div class="stat-icon">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div class="stat-content">
                                                <h3 class="stat-number">1000+</h3>
                                                <p class="stat-label">Siswa Aktif</p>
                                            </div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-icon">
                                                <i class="fas fa-chalkboard-teacher"></i>
                                            </div>
                                            <div class="stat-content">
                                                <h3 class="stat-number">50+</h3>
                                                <p class="stat-label">Guru & Staff</p>
                                            </div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-icon">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div class="stat-content">
                                                <h3 class="stat-number">10+</h3>
                                                <p class="stat-label">Program Keahlian</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                @if($profil->visi)
                <div class="col-md-6">
                    <div class="profil-card-modern shadow-sm h-100">
                        <div class="profil-card-header-modern bg-primary text-white">
                            <h3 class="mb-0">
                                <i class="fas fa-eye me-2"></i>
                                Visi
                            </h3>
                        </div>
                        <div class="profil-card-body-modern">
                            <div class="profil-content">
                                {!! nl2br(e($profil->visi)) !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($profil->misi)
                <div class="col-md-6">
                    <div class="profil-card-modern shadow-sm h-100">
                        <div class="profil-card-header-modern bg-primary text-white">
                            <h3 class="mb-0">
                                <i class="fas fa-bullseye me-2"></i>
                                Misi
                            </h3>
                        </div>
                        <div class="profil-card-body-modern">
                            <div class="profil-content">
                                {!! nl2br(e($profil->misi)) !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- History Section -->
    @if($profil->sejarah)
    <section class="py-5">
        <div class="container">
            <div class="col-lg-10 mx-auto">
                <div class="profil-card-modern shadow-sm">
                    <div class="profil-card-header-modern">
                        <h2 class="mb-0 text-center">
                            <i class="fas fa-history me-2"></i>
                            Sejarah Singkat
                        </h2>
                    </div>
                    <div class="profil-card-body-modern">
                        <div class="profil-content text-center">
                            {!! nl2br(e($profil->sejarah)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Contact Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="col-lg-10 mx-auto">
                <div class="profil-card-modern shadow-sm">
                    <div class="profil-card-header-modern bg-primary text-white">
                        <h2 class="mb-0 text-center">
                            <i class="fas fa-address-book me-2"></i>
                            Kontak Kami
                        </h2>
                    </div>
                    <div class="profil-card-body-modern">
                        <div class="row g-4">
                            <div class="col-md-4 text-center">
                                <div class="contact-item-modern">
                                    <div class="contact-icon-modern">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h4>Alamat</h4>
                                    <p>{{ $profil->alamat ?? 'Jl. Raya Tajur No. 24, Bogor' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="contact-item-modern">
                                    <div class="contact-icon-modern">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <h4>Telepon</h4>
                                    <p>{{ $profil->telepon ?? '(0251) 8321374' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="contact-item-modern">
                                    <div class="contact-icon-modern">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <h4>Email</h4>
                                    <p>{{ $profil->email ?? 'info@smkn4-bogor.sch.id' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($profil->website)
                        <div class="text-center mt-4">
                            <a href="{{ $profil->website }}" target="_blank" class="btn btn-primary-modern">
                                <i class="fas fa-globe me-2"></i> Kunjungi Website
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@else
    <!-- Empty State -->
    <section class="py-5">
        <div class="container">
            <div class="text-center py-5">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-school fa-5x text-muted"></i>
                </div>
                <h3 class="text-muted mb-3">Profil Sekolah Belum Tersedia</h3>
                <p class="text-muted mb-0">Kami sedang menyiapkan informasi terbaru tentang SMKN 4 Bogor.</p>
            </div>
        </div>
    </section>
@endif

<style>
/* Profil Hero */
.profil-hero {
    position: relative;
    overflow: hidden;
}

.profil-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.profil-logo {
    max-height: 120px;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
}

/* Profil Card Modern */
.profil-card-modern {
    background: white;
    border-radius: 16px;
    overflow: hidden;
}

.profil-card-header-modern {
    background: #2563eb;
    color: white;
    padding: 1.5rem;
}

.profil-card-header-modern.bg-primary {
    background: #2563eb !important;
}

.profil-card-body-modern {
    padding: 2rem;
}

.profil-description {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #374151;
}

/* Stats */
.profil-stats {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    border-left: 4px solid #2563eb;
    transition: all 0.3s ease;
}

.stat-item:hover {
    background: #eff6ff;
    transform: translateX(5px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: #2563eb;
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.stat-label {
    font-size: 0.9rem;
    color: #6b7280;
    margin: 0;
}

/* Profil Content */
.profil-content {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #374151;
}

/* Contact Items */
.contact-item-modern {
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
    height: 100%;
}

.contact-item-modern:hover {
    background: #eff6ff;
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.contact-icon-modern {
    width: 60px;
    height: 60px;
    background: #2563eb;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
}

.contact-item-modern h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
}

.contact-item-modern p {
    color: #6b7280;
    margin: 0;
}

.btn-primary-modern {
    background: #2563eb;
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}

.btn-primary-modern:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .profil-hero h1 {
        font-size: 2rem;
    }
    
    .profil-stats {
        margin-top: 2rem;
    }
}
</style>
@endsection
