@extends('layouts.app')

@section('title', 'Beranda - Sekolah Galeri')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Selamat Datang di Sekolah Galeri</h1>
                <p class="lead mb-4">Platform galeri foto sekolah yang memungkinkan Anda melihat berbagai kegiatan dan momen berharga di sekolah kami.</p>
                <a href="#gallery" class="btn btn-light btn-lg">Lihat Galeri</a>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-camera fa-8x opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Profile Section -->
<section id="profile" class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Profil Sekolah</h2>
                @if($profile)
                    <div class="card card-hover">
                        <div class="card-body">
                            <h3 class="card-title">{{ $profile->judul }}</h3>
                            <div class="card-text">
                                {!! nl2br(e($profile->isi)) !!}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Profil sekolah belum tersedia.
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section id="gallery" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2>Galeri Foto</h2>
                <p class="lead">Momen-momen berharga di sekolah kami</p>
            </div>
        </div>
        
        @if($photos->count() > 0)
            <div class="row g-4">
                @foreach($photos as $photo)
                    <div class="col-md-4 col-lg-3">
                        <div class="card gallery-item h-100">
                            <img src="{{ asset('storage/' . $photo->file) }}" 
                                 class="card-img-top" 
                                 alt="{{ $photo->judul ?? 'Foto Sekolah' }}"
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title">{{ $photo->judul ?? 'Foto Sekolah' }}</h6>
                                @if($photo->galery && $photo->galery->post)
                                    <p class="card-text small text-muted">
                                        {{ $photo->galery->post->judul }}
                                    </p>
                                @endif
                                <small class="text-muted">
                                    {{ $photo->created_at->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('admin.login') }}" class="btn btn-primary">
                        <i class="fas fa-images me-2"></i>
                        Lihat Semua Foto
                    </a>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-images me-2"></i>
                        Belum ada foto yang tersedia.
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2>Fitur Unggulan</h2>
                <p class="lead">Apa yang membuat Sekolah Galeri istimewa</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-hover h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-camera fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Galeri Foto</h5>
                        <p class="card-text">Lihat berbagai momen berharga dan kegiatan sekolah dalam bentuk galeri foto yang menarik.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card card-hover h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-newspaper fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Berita & Artikel</h5>
                        <p class="card-text">Baca berita terbaru dan artikel informatif tentang kegiatan dan prestasi sekolah.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card card-hover h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Profil Sekolah</h5>
                        <p class="card-text">Pelajari lebih lanjut tentang sejarah, visi, misi, dan prestasi sekolah kami.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
