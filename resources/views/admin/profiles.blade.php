@extends('layouts.admin')

@section('title', 'Edit Profil Sekolah')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-school me-2"></i>
        Edit Profil Sekolah
    </h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Kembali
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Edit Profile Form -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom">
        <h5 class="card-title mb-0 fw-bold">
            <i class="fas fa-edit me-2 text-primary"></i>
            Form Edit Profil Sekolah
        </h5>
        <p class="text-muted mb-0 small">Data ini akan ditampilkan di halaman beranda user</p>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.profiles.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-4">
                <!-- Nama Sekolah -->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="nama_sekolah" class="form-label fw-semibold">
                            <i class="fas fa-school me-2 text-primary"></i>
                            Nama Sekolah <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg @error('nama_sekolah') is-invalid @enderror" 
                               id="nama_sekolah" 
                               name="nama_sekolah" 
                               value="{{ old('nama_sekolah', $profil->nama_sekolah ?? '') }}" 
                               required>
                        @error('nama_sekolah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi / Tentang Sekolah -->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-semibold">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            Tentang Sekolah / Deskripsi <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="5" 
                                  required>{{ old('deskripsi', $profil->deskripsi ?? '') }}</textarea>
                        <small class="form-text text-muted">Deskripsi ini akan ditampilkan di bagian "Tentang Sekolah" di homepage</small>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Visi -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="visi" class="form-label fw-semibold">
                            <i class="fas fa-eye me-2 text-primary"></i>
                            Visi Sekolah
                        </label>
                        <textarea class="form-control @error('visi') is-invalid @enderror" 
                                  id="visi" 
                                  name="visi" 
                                  rows="4">{{ old('visi', $profil->visi ?? '') }}</textarea>
                        <small class="form-text text-muted">Visi sekolah akan ditampilkan di homepage</small>
                        @error('visi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Misi -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="misi" class="form-label fw-semibold">
                            <i class="fas fa-bullseye me-2 text-primary"></i>
                            Misi Sekolah
                        </label>
                        <textarea class="form-control @error('misi') is-invalid @enderror" 
                                  id="misi" 
                                  name="misi" 
                                  rows="4">{{ old('misi', $profil->misi ?? '') }}</textarea>
                        <small class="form-text text-muted">Misi sekolah akan ditampilkan di homepage</small>
                        @error('misi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Alamat -->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-semibold">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                            Alamat <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('alamat') is-invalid @enderror" 
                               id="alamat" 
                               name="alamat" 
                               value="{{ old('alamat', $profil->alamat ?? '') }}" 
                               required>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Kontak -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="telepon" class="form-label fw-semibold">
                            <i class="fas fa-phone me-2 text-primary"></i>
                            Telepon
                        </label>
                        <input type="text" 
                               class="form-control @error('telepon') is-invalid @enderror" 
                               id="telepon" 
                               name="telepon" 
                               value="{{ old('telepon', $profil->telepon ?? '') }}">
                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            Email
                        </label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $profil->email ?? '') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="website" class="form-label fw-semibold">
                            <i class="fas fa-globe me-2 text-primary"></i>
                            Website
                        </label>
                        <input type="url" 
                               class="form-control @error('website') is-invalid @enderror" 
                               id="website" 
                               name="website" 
                               value="{{ old('website', $profil->website ?? '') }}">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Logo -->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="logo" class="form-label fw-semibold">
                            <i class="fas fa-image me-2 text-primary"></i>
                            Logo Sekolah
                        </label>
                        @if($profil && $profil->logo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $profil->logo) }}" 
                                     alt="Logo Sekolah" 
                                     class="img-thumbnail" 
                                     style="max-height: 100px;">
                                <small class="d-block text-muted mt-1">Logo saat ini</small>
                            </div>
                        @endif
                        <input type="file" 
                               class="form-control @error('logo') is-invalid @enderror" 
                               id="logo" 
                               name="logo" 
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                        <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF, WEBP. Maksimal: 2MB</small>
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Sejarah (Optional) -->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="sejarah" class="form-label fw-semibold">
                            <i class="fas fa-history me-2 text-primary"></i>
                            Sejarah Sekolah
                        </label>
                        <textarea class="form-control @error('sejarah') is-invalid @enderror" 
                                  id="sejarah" 
                                  name="sejarah" 
                                  rows="4">{{ old('sejarah', $profil->sejarah ?? '') }}</textarea>
                        @error('sejarah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-times me-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
