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
                                         onerror="this.src='https://via.placeholder.com/400x300/2563eb/ffffff?text=Gambar+Tidak+Ditemukan'">
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Foto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editFotoForm" enctype="multipart/form-data">
                <input type="hidden" id="edit_foto_id" name="foto_id">
                <div class="modal-body">
                    <div id="editFotoAlert" class="alert d-none" role="alert"></div>
                    <div class="mb-3">
                        <label for="edit_kategori_id" class="form-label">Pilih Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_kategori_id" name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_file" class="form-label">File Foto (Opsional)</label>
                        <input type="file" class="form-control" id="edit_file" name="file" accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_judul" class="form-label">Judul Foto</label>
                        <input type="text" class="form-control" id="edit_judul" name="judul" placeholder="Masukkan judul foto">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Update Foto
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
    fetch('{{ route("admin.api.categories") }}')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('edit_kategori_id');
                if (select) {
                    select.innerHTML = '<option value="">Pilih Kategori</option>';
                    data.data.forEach(kategori => {
                        select.innerHTML += `<option value="${kategori.id}">${kategori.judul}</option>`;
                });
            }
        })
        .catch(() => {
            const select = document.getElementById('edit_kategori_id');
                if (select) {
                select.innerHTML = '<option value="">Pilih Kategori</option>';
                }
        });
}

// Edit foto
function editFoto(id, judul, kategori_id) {
    document.getElementById('edit_foto_id').value = id;
    document.getElementById('edit_judul').value = judul || '';
    if (kategori_id && kategori_id !== 'null') {
    document.getElementById('edit_kategori_id').value = kategori_id;
    }
    loadCategories();
    new bootstrap.Modal(document.getElementById('editFotoModal')).show();
}

// Update foto
document.getElementById('editFotoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fotoId = document.getElementById('edit_foto_id').value;
    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    const alertBox = document.getElementById('editFotoAlert');
    hideAlert(alertBox);

    const kategoriId = formData.get('kategori_id');
    if (!kategoriId) {
        showAlert(alertBox, 'warning', 'Kategori wajib dipilih.');
        return;
    }
    
    const file = document.getElementById('edit_file').files[0];
    if (file && !file.type.startsWith('image/')) {
            showAlert(alertBox, 'danger', 'File harus berupa gambar (jpeg, jpg, png, gif, webp).');
            return;
    }
    
    fetch(`{{ route("admin.fotos.update", ":id") }}`.replace(':id', fotoId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        const json = await response.json().catch(() => ({}));
        if (response.ok && json.success) {
            showAlert(document.getElementById('fotoAlert'), 'success', 'Foto berhasil diperbarui. Memuat ulang...');
            setTimeout(() => location.reload(), 800);
        } else {
            const msg = json.message || 'Gagal memperbarui foto';
            const firstErr = json.errors ? Object.values(json.errors)[0][0] : '';
            showAlert(alertBox, 'danger', `${msg}${firstErr ? ' - ' + firstErr : ''}`);
        }
    })
    .catch(() => showAlert(alertBox, 'danger', 'Terjadi kesalahan jaringan.'));
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
