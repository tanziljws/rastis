@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
<style>
.hero-dashboard {
    background: linear-gradient(rgba(51, 51, 255, 0.8), rgba(51, 51, 255, 0.9)), url('https://images.unsplash.com/photo-1562774053-701939374585?w=1200&q=80');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-align: center;
    margin: -20px -20px 30px -20px;
    border-radius: 0 0 15px 15px;
    position: relative;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-content h1 {
    font-size: 3rem;
    font-weight: bold;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
}

.hero-content h2 {
    font-size: 2.5rem;
    font-weight: bold;
    color: #ffcc00;
    margin-bottom: 15px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
}

.hero-content p {
    font-size: 1.2rem;
    margin-bottom: 0;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
}

.school-logo-hero {
    width: 60px;
    height: 60px;
    background: #ffcc00;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 24px;
    color: #3333ff;
    font-weight: bold;
}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-dashboard">
    <div class="hero-content">
        <div class="school-logo-hero">
            <i class="fas fa-school"></i>
        </div>
        <h1>Selamat Datang di</h1>
        <h2>SMKN 4 Kota Bogor</h2>
        <p>Mencetak Generasi Unggul, Berkarakter, dan Siap Kerja</p>
    </div>
</div>

<!-- Quick Stats Cards -->
<div class="row mt-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-stats bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_fotos'] }}</h3>
                        <p class="mb-0">Total Foto</p>
                    </div>
                    <div class="stats-icon bg-white bg-opacity-25">
                        <i class="fas fa-images"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-stats bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_galeries'] }}</h3>
                        <p class="mb-0">Galeri</p>
                    </div>
                    <div class="stats-icon bg-white bg-opacity-25">
                        <i class="fas fa-folder"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-stats bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_kategori'] }}</h3>
                        <p class="mb-0">Kategori</p>
                    </div>
                    <div class="stats-icon bg-white bg-opacity-25">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-stats bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_fotos'] }}</h3>
                        <p class="mb-0">Fotos</p>
                    </div>
                    <div class="stats-icon bg-white bg-opacity-25">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card card-stats bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_profiles'] }}</h3>
                        <p class="mb-0">Profiles</p>
                    </div>
                    <div class="stats-icon bg-white bg-opacity-25">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.kategori') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-tags me-2"></i>
                            Kelola Kategori
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.posts') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-newspaper me-2"></i>
                            Kelola Posts
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.fotos') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-camera me-2"></i>
                            Kelola Gallery
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.profiles') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-user me-2"></i>
                            Kelola Profiles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
