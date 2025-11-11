@extends('layouts.app')

@section('title', 'Beranda - Sekolah Galeri')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <div class="container">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-6">
                    <div class="school-logo-badge mb-4">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h1 class="display-3 fw-bold mb-4 text-shadow">Selamat Datang di <span class="text-warning">SMKN 4 Bogor</span></h1>
                    <p class="lead mb-4 fs-5">SMK Pusat Keunggulan - Mencetak Generasi Unggul, Berkarakter, dan Siap Kerja</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="#gallery" class="btn btn-warning btn-lg px-4 py-3 rounded-pill">
                            <i class="fas fa-images me-2"></i>Lihat Galeri
                        </a>
                        <a href="#profile" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill">
                            <i class="fas fa-info-circle me-2"></i>Profil Sekolah
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-stats">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stat-card">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <h3>1000+</h3>
                                    <p>Siswa Aktif</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                                    <h3>50+</h3>
                                    <p>Guru Profesional</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <i class="fas fa-trophy fa-2x mb-2"></i>
                                    <h3>100+</h3>
                                    <p>Prestasi</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <i class="fas fa-building fa-2x mb-2"></i>
                                    <h3>10+</h3>
                                    <p>Jurusan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

<!-- Program Keahlian Section -->
<section id="program-keahlian" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <div class="section-badge mb-3">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h2 class="display-5 fw-bold mb-3">Program Keahlian</h2>
                <p class="lead text-muted">Program unggulan di SMKN 4 Bogor</p>
                <div class="divider mx-auto"></div>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- PPLG -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-laptop-code fa-2x text-primary"></i>
                        </div>
                        <h5 class="card-title">PPLG</h5>
                        <p class="card-text text-muted small">Pengembangan Perangkat Lunak dan Gim</p>
                        <a href="#" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
            
            <!-- TJKT -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-network-wired fa-2x text-primary"></i>
                        </div>
                        <h5 class="card-title">TJKT</h5>
                        <p class="card-text text-muted small">Teknik Jaringan Komputer dan Telekomunikasi</p>
                        <a href="#" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
            
            <!-- TPFL -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-camera-retro fa-2x text-primary"></i>
                        </div>
                        <h5 class="card-title">TPFL</h5>
                        <p class="card-text text-muted small">Teknik Pengolahan Film</p>
                        <a href="#" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
            
            <!-- TO -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-broadcast-tower fa-2x text-primary"></i>
                        </div>
                        <h5 class="card-title">TO</h5>
                        <p class="card-text text-muted small">Teknik Otomasi</p>
                        <a href="#" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section id="gallery" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <div class="section-badge mb-3">
                    <i class="fas fa-camera"></i>
                </div>
                <h2 class="display-5 fw-bold mb-3">Galeri Foto</h2>
                <p class="lead text-muted">Momen-momen berharga di sekolah kami</p>
                <div class="divider mx-auto"></div>
            </div>
        </div>
        
        @if(($photos instanceof \Illuminate\Support\Collection ? $photos->count() : count($photos)) > 0)
            <div class="row g-3" id="lightbox-gallery">
                @foreach($photos as $photo)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ $photo->file_url }}" 
                           class="d-block position-relative" 
                           data-lightbox="sgallery" 
                           data-title="{{ $photo->judul ?? 'Foto Sekolah' }}">
                            <img src="{{ $photo->file_url }}" 
                                 class="img-fluid rounded shadow-sm" 
                                 alt="{{ $photo->judul ?? 'Foto Sekolah' }}"
                                 style="aspect-ratio:1/1; object-fit:cover; width:100%;">
                            <span class="position-absolute bottom-0 start-0 m-2 badge bg-dark bg-opacity-75">{{ $photo->judul ?? 'Foto Sekolah' }}</span>
                        </a>
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
<section class="py-5 bg-gradient">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <div class="section-badge mb-3">
                    <i class="fas fa-star"></i>
                </div>
                <h2 class="display-5 fw-bold mb-3">Keunggulan Kami</h2>
                <p class="lead text-muted">Apa yang membuat SMKN 4 Bogor istimewa</p>
                <div class="divider mx-auto"></div>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h5 class="feature-title">Galeri Foto</h5>
                    <p class="feature-text">Dokumentasi lengkap kegiatan sekolah, prestasi siswa, dan momen bersejarah SMKN 4 Bogor.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h5 class="feature-title">Pendidikan Berkualitas</h5>
                    <p class="feature-text">Program pembelajaran modern dengan kurikulum yang sesuai dengan kebutuhan industri masa depan.</p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h5 class="feature-title">Prestasi Gemilang</h5>
                    <p class="feature-text">Raih berbagai prestasi di tingkat daerah, nasional, dan internasional dalam bidang akademik dan non-akademik.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" />
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script>
    lightbox.option({
        'albumLabel': "Gambar %1 dari %2",
        'fadeDuration': 200,
        'imageFadeDuration': 200
    });
</script>
@endsection
