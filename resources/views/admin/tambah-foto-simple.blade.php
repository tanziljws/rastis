@extends('layouts.admin')

@section('title', 'Tambah Foto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-images me-2"></i>
        Upload Foto
    </h2>
    <a href="{{ route('admin.fotos') }}" class="btn btn-outline-secondary">
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

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom">
        <h5 class="card-title mb-0 fw-bold">
            <i class="fas fa-cloud-upload-alt me-2 text-primary"></i>
            Upload Foto Baru
        </h5>
        <p class="text-muted mb-0 small">Upload foto menggunakan form tradisional (lebih reliable)</p>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.fotos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">
                            <i class="fas fa-tag me-2"></i>
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" id="kategori_id" name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                            @if(isset($kategoris) && $kategoris->count() > 0)
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->judul }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="judul" class="form-label">
                            <i class="fas fa-heading me-2"></i>
                            Judul Foto
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="judul" 
                               name="judul" 
                               placeholder="Masukkan judul (opsional)">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="files" class="form-label">
                    <i class="fas fa-images me-2"></i>
                    Pilih Foto <span class="text-danger">*</span>
                </label>
                <input type="file" 
                       class="form-control" 
                       id="files" 
                       name="files[]" 
                       accept="image/jpeg,image/jpg,image/png,image/webp" 
                       multiple
                       required>
                <small class="form-text text-muted">Format: JPEG, JPG, PNG, WEBP | Maks: 10MB per foto | Bisa upload multiple foto sekaligus</small>
                <small class="form-text text-muted d-block mt-1">
                    <i class="fas fa-info-circle me-1"></i>
                    Pilih beberapa foto sekaligus dengan menahan <kbd>Ctrl</kbd> (Windows) atau <kbd>Cmd</kbd> (Mac) saat memilih file.
                </small>
                <div id="filePreview" class="mt-3"></div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload me-2"></i>
                    Upload Foto
                </button>
                <a href="{{ route('admin.fotos') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('files');
    const filePreview = document.getElementById('filePreview');
    
    fileInput.addEventListener('change', function(e) {
        const files = e.target.files;
        filePreview.innerHTML = '';
        
        if (files.length > 0) {
            const previewContainer = document.createElement('div');
            previewContainer.className = 'row g-2';
            
            Array.from(files).forEach((file, index) => {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const fileCard = document.createElement('div');
                fileCard.className = 'col-md-3';
                fileCard.innerHTML = `
                    <div class="card border">
                        <div class="card-body p-2">
                            <small class="d-block text-truncate" title="${file.name}">
                                <i class="fas fa-image me-1"></i>${file.name}
                            </small>
                            <small class="text-muted">${fileSize} MB</small>
                        </div>
                    </div>
                `;
                previewContainer.appendChild(fileCard);
            });
            
            const infoCard = document.createElement('div');
            infoCard.className = 'col-12 mt-2';
            infoCard.innerHTML = `
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>${files.length}</strong> foto dipilih. Total: <strong>${(Array.from(files).reduce((sum, f) => sum + f.size, 0) / 1024 / 1024).toFixed(2)} MB</strong>
                </div>
            `;
            previewContainer.appendChild(infoCard);
            
            filePreview.appendChild(previewContainer);
        }
    });
});
</script>
@endsection

