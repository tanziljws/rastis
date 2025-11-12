@extends('layouts.admin')

@section('title', 'Kelola Galeri Foto')

@section('content')
<!-- Hero Section for Gallery -->
<section class="gallery-hero py-5" style="background: #2563eb;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-white">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-images me-3"></i>
                    Kelola Galeri Foto
            </h1>
                <p class="lead mb-0">Kelola dan atur semua foto galeri sekolah</p>
            </div>
        </div>
    </div>
</section>

<!-- Filter & Search Section -->
<section class="py-4 bg-light border-bottom">
    <div class="container">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="searchInput" class="form-label fw-semibold">
                    <i class="fas fa-search me-2"></i>Cari Foto
                </label>
                <input type="text" 
                       class="form-control form-control-lg" 
                       id="searchInput" 
                       placeholder="Cari berdasarkan judul foto...">
            </div>
            <div class="col-md-4">
                <label for="filterKategori" class="form-label fw-semibold">
                    <i class="fas fa-filter me-2"></i>Kategori
                </label>
                <select class="form-select form-select-lg" id="filterKategori">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris ?? [] as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->judul }}</option>
                        @endforeach
                    </select>
            </div>
            <div class="col-md-3">
                <label for="sortSelect" class="form-label fw-semibold">
                    <i class="fas fa-sort me-2"></i>Urutkan
                </label>
                <select class="form-select form-select-lg" id="sortSelect">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="title_asc">Judul A-Z</option>
                        <option value="title_desc">Judul Z-A</option>
                    </select>
                </div>
            </div>
        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-primary btn-lg" id="applyFilter">
                        <i class="fas fa-filter me-2"></i>Terapkan Filter
                </button>
            </div>
    <div>
                    <a href="{{ route('admin.fotos.create') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-plus me-2"></i>Tambah Foto
                    </a>
    </div>
</div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-5">
    <div class="container">
<!-- Alerts -->
<div id="fotoAlert" class="alert d-none" role="alert"></div>

        @if($fotos->count() > 0)
            <!-- Gallery Stats -->
            <div class="row mb-4">
                <div class="col-12">
                    <p class="text-muted mb-0">
                        Menampilkan <strong>{{ $fotos->firstItem() }}</strong> - <strong>{{ $fotos->lastItem() }}</strong> 
                        dari <strong>{{ $fotos->total() }}</strong> foto
                    </p>
                </div>
            </div>
            
            <!-- Gallery Grid -->
            <div class="row g-4" id="gallery-grid">
                @foreach($fotos as $foto)
                    <div class="col-6 col-md-4 col-lg-3" 
                         data-title="{{ strtolower($foto->judul ?? '') }}" 
                         data-kategori="{{ $foto->kategori_id ?? '' }}" 
                         data-created="{{ $foto->created_at->timestamp }}">
                        <div class="gallery-card h-100">
                            <div class="gallery-image-wrapper">
                                @php
                                    $imageUrl = '';
                                    if (str_contains($foto->file, '/')) {
                                        $imageUrl = asset('storage/' . $foto->file);
                                    } else {
                                        $imageUrl = asset('storage/fotos/' . $foto->file);
                                    }
                                @endphp
                                <div class="gallery-image-container">
                                    <img src="{{ $imageUrl }}" 
                                         alt="{{ $foto->judul ?? 'Foto Sekolah' }}"
                                         class="gallery-image"
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect fill=\'%232563eb\' width=\'400\' height=\'300\'/%3E%3Ctext fill=\'%23ffffff\' font-family=\'Arial\' font-size=\'16\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3EGambar Tidak Ditemukan%3C/text%3E%3C/svg%3E';">
                                    <div class="gallery-overlay">
                                        <div class="gallery-overlay-content">
                                            <div class="admin-actions-overlay">
                                                <button class="btn btn-sm btn-light mb-2" 
                                                        onclick="editFoto({{ $foto->id }}, '{{ addslashes($foto->judul ?? '') }}', {{ $foto->kategori_id ?? 'null' }})"
                                                        title="Edit Foto">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                                                <button class="btn btn-sm btn-danger" 
                                                        onclick="deleteFoto({{ $foto->id }})"
                                                        title="Hapus Foto">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
                                </div>
                            </div>
                            <div class="gallery-card-body">
                                <h5 class="gallery-card-title">{{ $foto->judul ?? 'Foto Sekolah' }}</h5>
                                @if($foto->kategori)
                                    <span class="badge bg-primary mb-2">{{ $foto->kategori->judul }}</span>
                                @endif
                                <p class="gallery-card-date text-muted mb-0">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ $foto->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
</div>

<!-- Pagination -->
@if($fotos->hasPages())
                <div class="row mt-5">
                    <div class="col-12 d-flex justify-content-center">
        {{ $fotos->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-images fa-5x text-muted"></i>
                </div>
                <h3 class="text-muted mb-3">Belum Ada Foto</h3>
                <p class="text-muted mb-4">Mulai dengan menambahkan foto baru ke galeri</p>
                <a href="{{ route('admin.fotos.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Tambah Foto Pertama
                </a>
    </div>
@endif
</div>
</section>

<!-- Edit Foto Modal -->
<div class="modal fade" id="editFotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Album Foto
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editFotoForm" enctype="multipart/form-data">
                <input type="hidden" id="edit_foto_id" name="foto_id">
                <input type="hidden" id="edit_judul_original" name="judul_original">
                <div class="modal-body">
                    <div id="editFotoAlert" class="alert d-none" role="alert"></div>
                    
                    <!-- Album Info Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Album</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="edit_judul" class="form-label fw-semibold">
                                        Judul Album <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           id="edit_judul" 
                                           name="judul" 
                                           placeholder="Masukkan judul album"
                                           required>
                                    <small class="text-muted">Judul ini akan diterapkan ke semua foto dalam album</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_kategori_id" class="form-label fw-semibold">
                                        Kategori <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg" id="edit_kategori_id" name="kategori_id" required>
                                        <option value="">Pilih Kategori</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Photos Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0"><i class="fas fa-images me-2"></i>Foto dalam Album</h6>
                            <button type="button" class="btn btn-sm btn-danger" id="deleteSelectedBtn" style="display: none;">
                                <i class="fas fa-trash me-1"></i>Hapus Terpilih
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="albumPhotosContainer" class="row g-3">
                                <div class="col-12 text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Memuat foto...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add New Photos Section -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Foto Baru</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="edit_new_files" class="form-label fw-semibold">
                                    Pilih Foto Baru
                                </label>
                                <input type="file" 
                                       class="form-control form-control-lg" 
                                       id="edit_new_files" 
                                       name="new_files[]" 
                                       accept="image/*" 
                                       multiple>
                                <small class="text-muted">Anda dapat memilih beberapa foto sekaligus</small>
                            </div>
                            <div id="newFilesPreview" class="row g-3 mt-3"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg" id="updateFotoBtn">
                        <i class="fas fa-save me-2"></i>
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Gallery Hero */
.gallery-hero {
    position: relative;
    overflow: hidden;
}

.gallery-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

/* Gallery Card */
.gallery-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.gallery-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

/* Gallery Image Wrapper */
.gallery-image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 75%; /* 4:3 Aspect Ratio */
    overflow: hidden;
    background: #f8f9fa;
}

.gallery-image-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(37, 99, 235, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-card:hover .gallery-overlay {
    opacity: 1;
}

.admin-actions-overlay {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: center;
}

.admin-actions-overlay .btn {
    min-width: 100px;
    font-weight: 600;
}

.gallery-card-body {
    padding: 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.gallery-card-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 2.5rem;
}

.gallery-card-date {
    font-size: 0.875rem;
    margin-top: auto;
}

/* Empty State */
.empty-state-icon {
    opacity: 0.5;
}

/* Loading Animation */
.gallery-image-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    z-index: 1;
}

.gallery-image-container.loaded::before {
    display: none;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Edit Modal Styles */
.album-photo-edit-card {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s ease;
    cursor: pointer;
}

.album-photo-edit-card:hover {
    border-color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

.album-photo-edit-card .photo-checkbox:checked ~ .photo-preview-wrapper,
.album-photo-edit-card:has(.photo-checkbox:checked) {
    border-color: #2563eb;
    background: rgba(37, 99, 235, 0.05);
}

.photo-preview-wrapper {
    position: relative;
    overflow: hidden;
}

.new-photo-preview-card {
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    padding: 0.5rem;
    background: #f9fafb;
    transition: all 0.2s ease;
}

.new-photo-preview-card:hover {
    border-color: #2563eb;
    background: #f0f4ff;
}

.form-check-input.photo-checkbox {
    width: 1.5rem;
    height: 1.5rem;
    cursor: pointer;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.form-check-input.photo-checkbox:checked {
    background-color: #2563eb;
    border-color: #2563eb;
}

/* Modal enhancements */
.modal-xl {
    max-width: 1200px;
}

.modal-body .card {
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.modal-body .card-header {
    border-bottom: 1px solid #e5e7eb;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .gallery-hero h1 {
        font-size: 2rem;
    }
    
    .gallery-image-wrapper {
        padding-top: 100%; /* Square on mobile */
    }
    
    .admin-actions-overlay {
        flex-direction: row;
    }
    
    .admin-actions-overlay .btn {
        min-width: auto;
        flex: 1;
    }
    
    .modal-xl {
        max-width: 95%;
    }
}
</style>

<script>
// Toolbar interactions
const searchInput = document.getElementById('searchInput');
const filterKategori = document.getElementById('filterKategori');
const sortSelect = document.getElementById('sortSelect');
const grid = document.querySelector('#gallery-grid');
const applyFilterBtn = document.getElementById('applyFilter');

function applyFilters() {
    if (!grid) return;
    
    const term = (searchInput?.value || '').toLowerCase();
    const kategori = filterKategori?.value || '';
    const items = Array.from(grid.querySelectorAll('.col-6, .col-md-4, .col-lg-3'));

    items.forEach(item => {
        const title = item.dataset.title || '';
        const kategoriId = item.dataset.kategori || '';
        
        const matchTitle = !term || title.includes(term);
        const matchKategori = !kategori || kategoriId === kategori;
        
        item.style.display = (matchTitle && matchKategori) ? '' : 'none';
    });

    // Sort visible items
    const visible = items.filter(i => i.style.display !== 'none');
    visible.sort((a,b) => {
        const s = sortSelect?.value || 'newest';
        const aTitle = a.dataset.title || '';
        const bTitle = b.dataset.title || '';
        const aDate = parseInt(a.dataset.created) || 0;
        const bDate = parseInt(b.dataset.created) || 0;
        
        if (s === 'newest') return bDate - aDate;
        if (s === 'oldest') return aDate - bDate;
        if (s === 'title_asc') return aTitle.localeCompare(bTitle);
        if (s === 'title_desc') return bTitle.localeCompare(aTitle);
        return 0;
    });
    visible.forEach(el => grid.appendChild(el));
}

if (searchInput) searchInput.addEventListener('input', applyFilters);
if (filterKategori) filterKategori.addEventListener('change', applyFilters);
if (sortSelect) sortSelect.addEventListener('change', applyFilters);
if (applyFilterBtn) applyFilterBtn.addEventListener('click', applyFilters);

function showAlert(el, type, message) {
    if (!el) return;
    el.className = `alert alert-${type} alert-dismissible fade show`;
    el.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    setTimeout(() => {
        if (el) {
            el.classList.remove('show');
            setTimeout(() => el.className = 'alert d-none', 150);
        }
    }, 5000);
}

function hideAlert(el) {
    if (!el) return;
    el.className = 'alert d-none';
    el.innerHTML = '';
}

// Load categories for select dropdowns
function loadCategories() {
    return fetch('{{ route("admin.api.categories") }}')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('edit_kategori_id');
            if (select) {
                select.innerHTML = '<option value="">Pilih Kategori</option>';
                data.data.forEach(kategori => {
                    select.innerHTML += `<option value="${kategori.id}">${kategori.judul}</option>`;
                });
            }
            return data;
        })
        .catch(() => {
            const select = document.getElementById('edit_kategori_id');
            if (select) {
                select.innerHTML = '<option value="">Pilih Kategori</option>';
            }
            return { data: [] };
        });
}

// Edit foto - Load album photos
let selectedPhotoIds = [];
let albumPhotosData = [];

function editFoto(id, judul, kategori_id) {
    const fotoId = id;
    const originalJudul = judul || '';
    
    // Reset form
    document.getElementById('edit_foto_id').value = fotoId;
    document.getElementById('edit_judul_original').value = originalJudul;
    document.getElementById('edit_judul').value = originalJudul;
    document.getElementById('edit_kategori_id').value = kategori_id && kategori_id !== 'null' ? kategori_id : '';
    document.getElementById('edit_new_files').value = '';
    document.getElementById('newFilesPreview').innerHTML = '';
    selectedPhotoIds = [];
    albumPhotosData = [];
    
    // Show loading
    document.getElementById('albumPhotosContainer').innerHTML = `
        <div class="col-12 text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat foto...</p>
        </div>
    `;
    
    // Load categories
    loadCategories().then(() => {
        // Set kategori after categories loaded
        if (kategori_id && kategori_id !== 'null') {
            document.getElementById('edit_kategori_id').value = kategori_id;
        }
    });
    
    // Load album photos
    loadAlbumPhotos(fotoId);
    
    // Show modal
    new bootstrap.Modal(document.getElementById('editFotoModal')).show();
}

// Load album photos
function loadAlbumPhotos(fotoId) {
    fetch(`{{ route("admin.api.fotos.album", ":id") }}`.replace(':id', fotoId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                albumPhotosData = data.data;
                renderAlbumPhotos(data.data);
                
                // Set judul and kategori if not set
                if (!document.getElementById('edit_judul').value && data.judul) {
                    document.getElementById('edit_judul').value = data.judul;
                }
                if (!document.getElementById('edit_kategori_id').value && data.kategori_id) {
                    document.getElementById('edit_kategori_id').value = data.kategori_id;
                }
            } else {
                document.getElementById('albumPhotosContainer').innerHTML = `
                    <div class="col-12 text-center py-4">
                        <p class="text-danger">Gagal memuat foto album</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading album photos:', error);
            document.getElementById('albumPhotosContainer').innerHTML = `
                <div class="col-12 text-center py-4">
                    <p class="text-danger">Terjadi kesalahan saat memuat foto</p>
                </div>
            `;
        });
}

// Render album photos with checkboxes
function renderAlbumPhotos(photos) {
    if (photos.length === 0) {
        document.getElementById('albumPhotosContainer').innerHTML = `
            <div class="col-12 text-center py-4">
                <p class="text-muted">Tidak ada foto dalam album ini</p>
            </div>
        `;
        return;
    }
    
    const html = photos.map(photo => `
        <div class="col-6 col-md-4 col-lg-3">
            <div class="album-photo-edit-card position-relative">
                <div class="form-check position-absolute top-0 start-0 m-2" style="z-index: 10;">
                    <input class="form-check-input photo-checkbox" 
                           type="checkbox" 
                           value="${photo.id}" 
                           id="photo_${photo.id}"
                           onchange="togglePhotoSelection(${photo.id})">
                    <label class="form-check-label" for="photo_${photo.id}"></label>
                </div>
                <div class="photo-preview-wrapper">
                    <img src="${photo.thumbnail_url}" 
                         alt="${photo.judul || 'Foto'}"
                         class="img-fluid rounded"
                         style="width: 100%; height: 200px; object-fit: cover;"
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect fill=\'%232563eb\' width=\'400\' height=\'300\'/%3E%3Ctext fill=\'%23ffffff\' font-family=\'Arial\' font-size=\'16\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3EGambar Tidak Ditemukan%3C/text%3E%3C/svg%3E';">
                </div>
                <div class="photo-info p-2 bg-light rounded-bottom">
                    <small class="text-muted d-block">ID: ${photo.id}</small>
                    <small class="text-muted d-block">${photo.created_at}</small>
                </div>
            </div>
        </div>
    `).join('');
    
    document.getElementById('albumPhotosContainer').innerHTML = html;
    updateDeleteButton();
}

// Toggle photo selection
function togglePhotoSelection(photoId) {
    const index = selectedPhotoIds.indexOf(photoId);
    if (index > -1) {
        selectedPhotoIds.splice(index, 1);
    } else {
        selectedPhotoIds.push(photoId);
    }
    updateDeleteButton();
}

// Update delete button visibility
function updateDeleteButton() {
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    if (deleteBtn) {
        deleteBtn.style.display = selectedPhotoIds.length > 0 ? 'block' : 'none';
    }
}

// Delete selected photos
document.getElementById('deleteSelectedBtn')?.addEventListener('click', function() {
    if (selectedPhotoIds.length === 0) return;
    
    if (!confirm(`Apakah Anda yakin ingin menghapus ${selectedPhotoIds.length} foto yang dipilih?`)) {
        return;
    }
    
    fetch('{{ route("admin.api.fotos.bulk-delete") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ photo_ids: selectedPhotoIds })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(document.getElementById('editFotoAlert'), 'success', data.message);
            // Reload album photos
            const fotoId = document.getElementById('edit_foto_id').value;
            loadAlbumPhotos(fotoId);
            selectedPhotoIds = [];
            updateDeleteButton();
        } else {
            showAlert(document.getElementById('editFotoAlert'), 'danger', data.message || 'Gagal menghapus foto');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert(document.getElementById('editFotoAlert'), 'danger', 'Terjadi kesalahan saat menghapus foto');
    });
});

// Preview new files
document.getElementById('edit_new_files')?.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const previewContainer = document.getElementById('newFilesPreview');
    
    if (files.length === 0) {
        previewContainer.innerHTML = '';
        return;
    }
    
    previewContainer.innerHTML = '<div class="col-12"><h6 class="mb-3">Preview Foto Baru:</h6></div>';
    
    files.forEach((file, index) => {
        if (!file.type.startsWith('image/')) return;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-6 col-md-4 col-lg-3';
            col.innerHTML = `
                <div class="new-photo-preview-card">
                    <img src="${e.target.result}" 
                         alt="Preview ${index + 1}"
                         class="img-fluid rounded"
                         style="width: 100%; height: 150px; object-fit: cover;">
                    <small class="d-block text-center mt-2 text-muted">${file.name}</small>
                </div>
            `;
            previewContainer.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
});

// Update foto - Enhanced with album support
document.getElementById('editFotoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fotoId = document.getElementById('edit_foto_id').value;
    const alertBox = document.getElementById('editFotoAlert');
    const updateBtn = document.getElementById('updateFotoBtn');
    const spinner = updateBtn.querySelector('.spinner-border');
    
    hideAlert(alertBox);
    
    // Validation
    const judul = document.getElementById('edit_judul').value.trim();
    const kategoriId = document.getElementById('edit_kategori_id').value;
    const newFiles = document.getElementById('edit_new_files').files;
    
    if (!judul) {
        showAlert(alertBox, 'warning', 'Judul album wajib diisi.');
        return;
    }
    
    if (!kategoriId) {
        showAlert(alertBox, 'warning', 'Kategori wajib dipilih.');
        return;
    }
    
    // Show loading
    updateBtn.disabled = true;
    spinner.classList.remove('d-none');
    
    // Step 1: Update album info (judul and kategori for all photos in album)
    // Only update the representative photo, backend will handle updating all photos in album
    const updatePromises = [];
    const originalJudul = document.getElementById('edit_judul_original').value;
    
    // Update representative photo (first photo in album) - backend will update all photos in album
    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('kategori_id', kategoriId);
    formData.append('judul', judul);
    formData.append('judul_original', originalJudul); // Send original judul for album update
    
    updatePromises.push(
        fetch(`{{ route("admin.fotos.update", ":id") }}`.replace(':id', fotoId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
    );
    
    // Step 2: Add new photos if any
    if (newFiles.length > 0) {
        const addFormData = new FormData();
        Array.from(newFiles).forEach(file => {
            addFormData.append('files[]', file);
        });
        addFormData.append('judul', judul);
        addFormData.append('kategori_id', kategoriId);
        
        updatePromises.push(
            fetch(`{{ route("admin.api.fotos.add-photos", ":id") }}`.replace(':id', fotoId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: addFormData
            })
        );
    }
    
    // Execute all updates
    Promise.all(updatePromises)
        .then(async responses => {
            const results = await Promise.all(responses.map(r => r.json().catch(() => ({}))));
            const allSuccess = results.every(r => r.success !== false);
            
            if (allSuccess) {
                showAlert(document.getElementById('fotoAlert'), 'success', 'Album berhasil diperbarui! Memuat ulang...');
                setTimeout(() => location.reload(), 1000);
            } else {
                const errors = results.filter(r => !r.success).map(r => r.message || 'Error').join(', ');
                showAlert(alertBox, 'danger', `Gagal memperbarui: ${errors}`);
                updateBtn.disabled = false;
                spinner.classList.add('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert(alertBox, 'danger', 'Terjadi kesalahan saat memperbarui album.');
            updateBtn.disabled = false;
            spinner.classList.add('d-none');
        });
});

// Delete foto
function deleteFoto(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus foto ini?')) return;
    
        fetch(`{{ route("admin.fotos.destroy", ":id") }}`.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const json = await response.json().catch(() => ({}));
            if (response.ok && json.success) {
                showAlert(document.getElementById('fotoAlert'), 'success', 'Foto berhasil dihapus. Memuat ulang...');
                setTimeout(() => location.reload(), 600);
            } else {
                const msg = json.message || 'Error menghapus foto';
                showAlert(document.getElementById('fotoAlert'), 'danger', msg);
            }
        })
        .catch(() => showAlert(document.getElementById('fotoAlert'), 'danger', 'Terjadi kesalahan jaringan.'));
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();

// Image loading handler
    const images = document.querySelectorAll('.gallery-image');
    images.forEach(img => {
        const container = img.closest('.gallery-image-container');
        if (img.complete) {
            container?.classList.add('loaded');
        } else {
            img.addEventListener('load', () => {
                container?.classList.add('loaded');
            });
            img.addEventListener('error', () => {
                container?.classList.add('loaded');
            });
        }
    });
});
</script>
@endsection
