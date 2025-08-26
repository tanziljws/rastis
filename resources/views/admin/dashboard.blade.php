@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card card-stats bg-primary text-white">
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
    
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card card-stats bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_posts'] }}</h3>
                        <p class="mb-0">Posts</p>
                    </div>
                    <div class="stats-icon bg-white bg-opacity-25">
                        <i class="fas fa-newspaper"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card card-stats bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $stats['total_galeries'] }}</h3>
                        <p class="mb-0">Galeries</p>
                    </div>
                    <div class="stats-icon bg-white bg-opacity-25">
                        <i class="fas fa-images"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-2 col-sm-6 mb-3">
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

<!-- Recent Posts & Photos -->
<div class="row">
    <!-- Recent Posts -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-newspaper me-2"></i>
                    Posts Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if($recentPosts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentPosts as $post)
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $post->judul }}</h6>
                                        <p class="mb-1 text-muted small">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $post->kategori->judul ?? 'Tanpa Kategori' }}
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $post->petugas->username }}
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $post->created_at->format('d M Y') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $post->status === 'published' ? 'success' : ($post->status === 'draft' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.posts') }}" class="btn btn-outline-primary btn-sm">
                            Lihat Semua Posts
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada posts</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent Photos -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-camera me-2"></i>
                    Foto Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if($recentPhotos->count() > 0)
                    <div class="row g-3">
                        @foreach($recentPhotos as $photo)
                            <div class="col-md-4">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $photo->file) }}" 
                                         class="img-fluid rounded" 
                                         alt="{{ $photo->judul ?? 'Foto' }}"
                                         style="height: 100px; width: 100%; object-fit: cover;">
                                    @if($photo->galery && $photo->galery->post)
                                        <div class="position-absolute bottom-0 start-0 w-100 p-2" 
                                             style="background: rgba(0,0,0,0.7); color: white; font-size: 0.75rem;">
                                            {{ Str::limit($photo->galery->post->judul, 20) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.fotos') }}" class="btn btn-outline-primary btn-sm">
                            Lihat Semua Fotos
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada foto</p>
                    </div>
                @endif
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
                            Kelola Fotos
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
