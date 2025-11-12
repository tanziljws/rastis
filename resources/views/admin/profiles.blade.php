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
        <form action="{{ route('admin.profiles.update') }}" method="POST">
            @csrf
            
            <div class="row g-4">
                <!-- Deskripsi / Tentang Sekolah -->
                <div class="col-md-12">
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label fw-semibold">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            Tentang Sekolah <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="6" 
                                  required
                                  placeholder="Masukkan deskripsi tentang sekolah...">{{ old('deskripsi', $profil->deskripsi ?? '') }}</textarea>
                        <small class="form-text text-muted">Deskripsi ini akan ditampilkan di bagian "Tentang Sekolah" di homepage</small>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Visi -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label for="visi" class="form-label fw-semibold">
                            <i class="fas fa-eye me-2 text-primary"></i>
                            Visi Sekolah
                        </label>
                        <textarea class="form-control @error('visi') is-invalid @enderror" 
                                  id="visi" 
                                  name="visi" 
                                  rows="6"
                                  placeholder="Masukkan visi sekolah...">{{ old('visi', $profil->visi ?? '') }}</textarea>
                        <small class="form-text text-muted">Visi sekolah akan ditampilkan di homepage</small>
                        @error('visi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Misi -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label for="misi" class="form-label fw-semibold">
                            <i class="fas fa-bullseye me-2 text-primary"></i>
                            Misi Sekolah
                        </label>
                        <textarea class="form-control @error('misi') is-invalid @enderror" 
                                  id="misi" 
                                  name="misi" 
                                  rows="6"
                                  placeholder="Masukkan misi sekolah...">{{ old('misi', $profil->misi ?? '') }}</textarea>
                        <small class="form-text text-muted">Misi sekolah akan ditampilkan di homepage</small>
                        @error('misi')
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
