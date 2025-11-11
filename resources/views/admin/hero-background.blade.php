@extends('layouts.admin')

@section('title', 'Hero Background')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="fas fa-image me-2"></i>
            Kelola Hero Background
        </h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.hero-background.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <label for="hero_background" class="form-label">
                            <i class="fas fa-upload me-2"></i>
                            Upload Hero Background
                        </label>
                        <input type="file" 
                               class="form-control @error('hero_background') is-invalid @enderror" 
                               id="hero_background" 
                               name="hero_background" 
                               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                               onchange="previewImage(this)">
                        <div class="form-text">
                            Format yang didukung: JPEG, PNG, JPG, GIF, WEBP. Maksimal ukuran: 5MB
                        </div>
                        @error('hero_background')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Simpan Background
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Preview</h6>
                        </div>
                        <div class="card-body">
                            <div id="imagePreview" class="text-center">
                                @if($profil && $profil->hero_background)
                                    <img src="{{ asset('storage/' . $profil->hero_background) }}" 
                                         alt="Current Hero Background" 
                                         class="img-fluid rounded shadow-sm mb-3"
                                         style="max-height: 300px; width: 100%; object-fit: cover;">
                                    <p class="text-muted small mb-0">Background Saat Ini</p>
                                @else
                                    <div class="bg-light rounded p-5 mb-3" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                        <div class="text-center">
                                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada background</p>
                                        </div>
                                    </div>
                                @endif
                                
                                <div id="newImagePreview" style="display: none;">
                                    <img id="previewImg" 
                                         src="" 
                                         alt="Preview" 
                                         class="img-fluid rounded shadow-sm mb-3"
                                         style="max-height: 300px; width: 100%; object-fit: cover;">
                                    <p class="text-success small mb-0">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Preview Baru
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @if($profil && $profil->hero_background)
            <div class="mt-4">
                <div class="alert alert-info">
                    <h6 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Background
                    </h6>
                    <p class="mb-0">
                        <strong>Path:</strong> <code>{{ $profil->hero_background }}</code><br>
                        <strong>URL:</strong> <a href="{{ asset('storage/' . $profil->hero_background) }}" target="_blank">{{ asset('storage/' . $profil->hero_background) }}</a>
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('previewImg');
    const previewContainer = document.getElementById('newImagePreview');
    const currentPreview = document.getElementById('imagePreview').querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
            if (currentPreview && currentPreview.id !== 'previewImg') {
                currentPreview.style.opacity = '0.5';
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.style.display = 'none';
        if (currentPreview && currentPreview.id !== 'previewImg') {
            currentPreview.style.opacity = '1';
        }
    }
}
</script>
@endsection

