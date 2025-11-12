@extends('layouts.app')

@section('title', $judul . ' - Album Foto')

@section('content')
<!-- Hero Section -->
<section class="album-hero py-4" style="background: #2563eb;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-modern">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('galeri.index') }}">Galeri</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($judul, 30) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Album Detail Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Album Header -->
                <div class="album-header mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="album-title-main mb-2">{{ $displayJudul ?? $judul }}</h1>
                            <div class="album-meta mb-3">
                                @if($firstFoto->kategori)
                                    <span class="badge bg-primary me-2">
                                        <i class="fas fa-tag me-1"></i>{{ $firstFoto->kategori->judul }}
                                    </span>
                                @endif
                                <span class="badge bg-success me-2">
                                    <i class="fas fa-images me-1"></i>{{ $fotos->count() }} Foto
                                </span>
                                <span class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ $firstFoto->created_at->format('d F Y') }}
                                </span>
                            </div>
                            
                            <!-- Album Like & Comment Actions (Outside Modal) -->
                            <div class="album-actions mb-3">
                                @auth
                                    <button type="button" class="btn {{ $albumLiked ? 'btn-danger' : 'btn-outline-danger' }} like-btn-album" id="likeBtnAlbum">
                                        <i class="{{ $albumLiked ? 'fas' : 'far' }} fa-heart me-2" id="likeIconAlbum"></i>
                                        <span id="likeTextAlbum">{{ $albumLiked ? 'Disukai' : 'Suka' }}</span>
                                        <span id="likeCountAlbum" class="ms-2">{{ $albumLikeCount }}</span>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-danger">
                                        <i class="far fa-heart me-2"></i>
                                        Suka
                                        <span class="ms-2">{{ $albumLikeCount }}</span>
                                    </a>
                                @endauth
                                <span class="ms-3">
                                    <i class="fas fa-comment text-primary me-2"></i>
                                    <span id="commentCountHeader">{{ $albumCommentCount }}</span> Komentar
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="{{ route('galeri.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Galeri
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Photo Grid -->
                <div class="row g-4" id="album-photos">
                    @foreach($fotos as $index => $foto)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="album-photo-card">
                                <div class="album-photo-wrapper" 
                                     data-bs-toggle="modal" 
                                     data-bs-target="#photoModal" 
                                     data-photo-id="{{ $foto->id }}"
                                     data-photo-url="{{ $foto->file_url }}"
                                     data-photo-title="{{ $foto->judul ?? 'Foto Sekolah' }}"
                                     data-photo-date="{{ $foto->created_at->format('d F Y') }}"
                                     data-photo-kategori="{{ $foto->kategori->judul ?? '-' }}"
                                     data-photo-index="{{ $index }}"
                                     data-album-representative-id="{{ $albumRepresentativeFoto->id }}"
                                     data-album-liked="{{ $albumLiked ? 'true' : 'false' }}"
                                     data-album-like-count="{{ $albumLikeCount }}">
                                    <img src="{{ $foto->thumbnail_url }}" 
                                         alt="{{ $foto->judul ?? 'Foto Sekolah' }}"
                                         class="album-photo-image"
                                         loading="lazy"
                                         data-src="{{ $foto->file_url }}"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'300\'%3E%3Crect fill=\'%232563eb\' width=\'400\' height=\'300\'/%3E%3Ctext fill=\'%23ffffff\' font-family=\'Arial\' font-size=\'16\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3EGambar Tidak Ditemukan%3C/text%3E%3C/svg%3E';">
                                    <div class="album-photo-overlay">
                                        <div class="album-photo-overlay-content">
                                            <i class="fas fa-search-plus fa-2x mb-2"></i>
                                            <p class="mb-0">Klik untuk melihat</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Photo Modal with Gallery Navigation -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="photoModalLabel">
                    <span id="modalPhotoCounter"></span>
                    <span id="modalPhotoTitle"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 position-relative">
                <!-- Navigation Buttons -->
                <button class="gallery-nav-btn gallery-nav-prev" id="prevPhotoBtn" onclick="changePhoto(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="gallery-nav-btn gallery-nav-next" id="nextPhotoBtn" onclick="changePhoto(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Image Container -->
                <div class="text-center gallery-image-wrapper-modal">
                    <img id="modalPhotoImage" src="" alt="Foto" class="gallery-modal-image">
                </div>
                
                <!-- Photo Info & Actions -->
                <div class="p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <p class="mb-0">
                                <i class="far fa-calendar-alt text-primary me-2"></i>
                                <strong>Tanggal:</strong> <span id="modalPhotoDate"></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">
                                <i class="fas fa-tag text-primary me-2"></i>
                                <strong>Kategori:</strong> <span id="modalPhotoKategori"></span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Like & Download Buttons -->
                    <div class="d-flex gap-2 mb-4">
                        <button type="button" class="btn btn-outline-danger like-btn" id="likeBtn">
                            <i class="far fa-heart me-2" id="likeIcon"></i>
                            <span id="likeText">Suka</span>
                            <span id="likeCount" class="ms-2">0</span>
                        </button>
                        <button type="button" class="btn btn-primary" id="downloadBtn" data-bs-toggle="modal" data-bs-target="#downloadModal">
                            <i class="fas fa-download me-2"></i>Unduh Foto
                        </button>
                    </div>
                    
                    <!-- Comments Section -->
                    <div class="comments-section border-top pt-4">
                        <h6 class="mb-3">
                            <i class="fas fa-comments me-2"></i>Komentar
                            <span class="badge bg-primary ms-2" id="commentCountBadge">0</span>
                        </h6>
                        
                        @auth
                            <!-- Comment Form -->
                            <div class="mb-4">
                                <form id="commentForm">
                                    <div class="mb-2">
                                        <textarea class="form-control" 
                                                  id="commentContent" 
                                                  rows="3" 
                                                  placeholder="Tulis komentar..." 
                                                  required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Komentar
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <a href="{{ route('login') }}" class="alert-link">Login</a> untuk berkomentar
                            </div>
                        @endauth
                        
                        <!-- Comments List -->
                        <div id="commentsList" class="comments-list">
                            <!-- Comments will be loaded here via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Breadcrumb Modern */
.breadcrumb-modern {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-modern .breadcrumb-item a {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
}

.breadcrumb-modern .breadcrumb-item.active {
    color: white;
}

.breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
    content: '›';
    color: rgba(255,255,255,0.7);
    padding: 0 0.5rem;
}

/* Album Header */
.album-header {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.album-title-main {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.album-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

/* Album Photo Card */
.album-photo-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    cursor: pointer;
}

.album-photo-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.album-photo-wrapper {
    position: relative;
    width: 100%;
    padding-top: 100%; /* Square */
    overflow: hidden;
    background: #f8f9fa;
}

.album-photo-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.album-photo-card:hover .album-photo-image {
    transform: scale(1.05);
}

.album-photo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(37, 99, 235, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.album-photo-card:hover .album-photo-overlay {
    opacity: 1;
}

.album-photo-overlay-content {
    color: white;
    text-align: center;
}

/* Gallery Modal */
.gallery-image-wrapper-modal {
    background: #000;
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.gallery-modal-image {
    max-width: 100%;
    max-height: 70vh;
    object-fit: contain;
    border-radius: 8px;
}

.gallery-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    background: rgba(37, 99, 235, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    opacity: 0.8;
}

.gallery-nav-btn:hover {
    background: #2563eb;
    opacity: 1;
    transform: translateY(-50%) scale(1.1);
}

.gallery-nav-prev {
    left: 20px;
}

.gallery-nav-next {
    right: 20px;
}

#modalPhotoCounter {
    color: #6b7280;
    font-size: 0.9rem;
    font-weight: normal;
}

.btn-outline-primary {
    border: 2px solid #2563eb;
    color: #2563eb;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-outline-primary:hover {
    background: #2563eb;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .album-title-main {
        font-size: 1.5rem;
    }
    
    .gallery-nav-btn {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    
    .gallery-nav-prev {
        left: 10px;
    }
    
    .gallery-nav-next {
        right: 10px;
    }
    
    .gallery-modal-image {
        max-height: 50vh;
    }
}
</style>

<script>
// Album Photo Data
let albumPhotos = [];
let currentPhotoIndex = 0;
let currentPhotoId = null;
let albumRepresentativeFotoId = null; // ID of first photo representing the album for like/comment

// Collect all photos in album
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.album-photo-wrapper').forEach((wrapper, index) => {
        albumPhotos.push({
            id: parseInt(wrapper.getAttribute('data-photo-id')),
            url: wrapper.getAttribute('data-photo-url'),
            title: wrapper.getAttribute('data-photo-title'),
            date: wrapper.getAttribute('data-photo-date'),
            kategori: wrapper.getAttribute('data-photo-kategori'),
            index: parseInt(wrapper.getAttribute('data-photo-index'))
        });
        
        // Get album representative ID from first photo
        if (index === 0) {
            albumRepresentativeFotoId = parseInt(wrapper.getAttribute('data-album-representative-id'));
        }
    });
    
    // Sort by index
    albumPhotos.sort((a, b) => a.index - b.index);
    
    // Load initial like status for album (both in header and modal)
    if (albumRepresentativeFotoId) {
        loadAlbumLikeStatus(albumRepresentativeFotoId);
        loadCommentsCount(albumRepresentativeFotoId);
    }
    
    // Like button in header handler
    const likeBtnAlbum = document.getElementById('likeBtnAlbum');
    if (likeBtnAlbum) {
        likeBtnAlbum.addEventListener('click', function() {
            if (!albumRepresentativeFotoId) return;
            toggleLike(albumRepresentativeFotoId);
        });
    }
});

// Photo Modal Handler
document.addEventListener('DOMContentLoaded', function() {
    const photoModal = document.getElementById('photoModal');
    if (photoModal) {
        photoModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const photoIndex = parseInt(button.getAttribute('data-photo-index'));
            const photoId = parseInt(button.getAttribute('data-photo-id'));
            
            currentPhotoIndex = photoIndex;
            currentPhotoId = photoId;
            updateModalPhoto();
            
            // Load comments for album (using representative photo)
            if (albumRepresentativeFotoId) {
                loadComments(albumRepresentativeFotoId);
            }
        });
    }
    
    // Like button handler - uses album representative
    const likeBtn = document.getElementById('likeBtn');
    if (likeBtn) {
        likeBtn.addEventListener('click', function() {
            if (!albumRepresentativeFotoId) return;
            toggleLike(albumRepresentativeFotoId);
        });
    }
    
    // Comment form handler - uses album representative
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!albumRepresentativeFotoId) return;
            submitComment(albumRepresentativeFotoId);
        });
    }
});

// Update modal photo display
function updateModalPhoto() {
    if (albumPhotos.length === 0) return;
    
    const totalPhotos = albumPhotos.length;
    
    // Ensure index is within bounds
    if (currentPhotoIndex < 0) currentPhotoIndex = 0;
    if (currentPhotoIndex >= totalPhotos) currentPhotoIndex = totalPhotos - 1;
    
    const currentPhoto = albumPhotos[currentPhotoIndex];
    
    if (!currentPhoto) return;
    
    // Update image
    document.getElementById('modalPhotoImage').src = currentPhoto.url;
    document.getElementById('modalPhotoTitle').textContent = currentPhoto.title;
    document.getElementById('modalPhotoDate').textContent = currentPhoto.date;
    document.getElementById('modalPhotoKategori').textContent = currentPhoto.kategori;
    
    // Update download button
    const downloadBtn = document.getElementById('downloadBtn');
    if (downloadBtn) {
        downloadBtn.setAttribute('data-photo-url', currentPhoto.url);
    }
    
    // Update current photo ID
    currentPhotoId = currentPhoto.id;
    
    // Load like status and comments for album (using representative photo)
    if (albumRepresentativeFotoId) {
        loadAlbumLikeStatus(albumRepresentativeFotoId);
        loadComments(albumRepresentativeFotoId);
    }
    
    // Update counter
    if (totalPhotos > 1) {
        document.getElementById('modalPhotoCounter').textContent = `(${currentPhotoIndex + 1} / ${totalPhotos}) `;
    } else {
        document.getElementById('modalPhotoCounter').textContent = '';
    }
    
    // Show/hide navigation buttons
    const prevBtn = document.getElementById('prevPhotoBtn');
    const nextBtn = document.getElementById('nextPhotoBtn');
    
    if (totalPhotos > 1) {
        prevBtn.style.display = 'flex';
        nextBtn.style.display = 'flex';
    } else {
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
    }
}

// Change photo (prev/next)
function changePhoto(direction) {
    if (albumPhotos.length === 0) return;
    
    const totalPhotos = albumPhotos.length;
    
    currentPhotoIndex += direction;
    
    // Loop around
    if (currentPhotoIndex < 0) {
        currentPhotoIndex = totalPhotos - 1;
    } else if (currentPhotoIndex >= totalPhotos) {
        currentPhotoIndex = 0;
    }
    
    updateModalPhoto();
}

// Update like button UI (both modal and header)
function updateLikeButton(liked, likeCount) {
    // Update modal like button
    const likeBtn = document.getElementById('likeBtn');
    const likeIcon = document.getElementById('likeIcon');
    const likeText = document.getElementById('likeText');
    const likeCountEl = document.getElementById('likeCount');
    
    // Update header like button
    const likeBtnAlbum = document.getElementById('likeBtnAlbum');
    const likeIconAlbum = document.getElementById('likeIconAlbum');
    const likeTextAlbum = document.getElementById('likeTextAlbum');
    const likeCountAlbum = document.getElementById('likeCountAlbum');
    
    // Update modal button
    if (likeBtn) {
        if (liked) {
            likeBtn.classList.remove('btn-outline-danger');
            likeBtn.classList.add('btn-danger');
            if (likeIcon) {
                likeIcon.classList.remove('far');
                likeIcon.classList.add('fas');
            }
            if (likeText) likeText.textContent = 'Disukai';
        } else {
            likeBtn.classList.remove('btn-danger');
            likeBtn.classList.add('btn-outline-danger');
            if (likeIcon) {
                likeIcon.classList.remove('fas');
                likeIcon.classList.add('far');
            }
            if (likeText) likeText.textContent = 'Suka';
        }
        if (likeCountEl) {
            likeCountEl.textContent = likeCount;
        }
    }
    
    // Update header button
    if (likeBtnAlbum) {
        if (liked) {
            likeBtnAlbum.classList.remove('btn-outline-danger');
            likeBtnAlbum.classList.add('btn-danger');
            if (likeIconAlbum) {
                likeIconAlbum.classList.remove('far');
                likeIconAlbum.classList.add('fas');
            }
            if (likeTextAlbum) likeTextAlbum.textContent = 'Disukai';
        } else {
            likeBtnAlbum.classList.remove('btn-danger');
            likeBtnAlbum.classList.add('btn-outline-danger');
            if (likeIconAlbum) {
                likeIconAlbum.classList.remove('fas');
                likeIconAlbum.classList.add('far');
            }
            if (likeTextAlbum) likeTextAlbum.textContent = 'Suka';
        }
        if (likeCountAlbum) {
            likeCountAlbum.textContent = likeCount;
        }
    }
}

// Load album like status
function loadAlbumLikeStatus(fotoId) {
    fetch(`/api/fotos/${fotoId}/like/status`)
        .then(response => response.json())
        .then(data => {
            updateLikeButton(data.liked, data.like_count);
        })
        .catch(error => {
            console.error('Error loading like status:', error);
        });
}

// Toggle like (for album)
function toggleLike(fotoId) {
    fetch(`/api/fotos/${fotoId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.redirect) {
            window.location.href = data.redirect;
            return;
        }
        
        if (data.success) {
            updateLikeButton(data.liked, data.like_count);
            // Also update count in header
            if (albumRepresentativeFotoId) {
                loadAlbumLikeStatus(albumRepresentativeFotoId);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyukai album.');
    });
}

// Load comments
function loadComments(fotoId) {
    fetch(`/api/fotos/${fotoId}/comments`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayComments(data.comments);
                updateCommentCount(data.count);
            }
        })
        .catch(error => {
            console.error('Error loading comments:', error);
        });
}

// Display comments
function displayComments(comments) {
    const commentsList = document.getElementById('commentsList');
    if (!commentsList) return;
    
    if (comments.length === 0) {
        commentsList.innerHTML = '<p class="text-muted text-center">Belum ada komentar.</p>';
        return;
    }
    
    const currentUserId = {{ auth()->id() ?? 'null' }};
    commentsList.innerHTML = comments.map(comment => {
        const deleteBtn = comment.user_id === currentUserId ? `
            <button class="btn btn-sm btn-outline-danger delete-comment-btn" data-comment-id="${comment.id}">
                <i class="fas fa-trash"></i>
            </button>
        ` : '';
        return `
        <div class="comment-item mb-3 p-3 bg-light rounded" data-comment-id="${comment.id}">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <strong class="d-block mb-1">${comment.user_name}</strong>
                    <p class="mb-1">${comment.content}</p>
                    <small class="text-muted">${comment.created_at_human}</small>
                </div>
                ${deleteBtn}
            </div>
        </div>
        `;
    }).join('');
    
    // Add delete handlers
    document.querySelectorAll('.delete-comment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            deleteComment(commentId);
        });
    });
}

// Update comment count (both modal and header)
function updateCommentCount(count) {
    const badge = document.getElementById('commentCountBadge');
    const headerCount = document.getElementById('commentCountHeader');
    
    if (badge) {
        badge.textContent = count;
    }
    if (headerCount) {
        headerCount.textContent = count;
    }
}

// Load comments count only
function loadCommentsCount(fotoId) {
    fetch(`/api/fotos/${fotoId}/comments`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCommentCount(data.count);
            }
        })
        .catch(error => {
            console.error('Error loading comments count:', error);
        });
}

// Submit comment
function submitComment(fotoId) {
    const content = document.getElementById('commentContent').value.trim();
    if (!content) return;
    
    fetch(`/api/fotos/${fotoId}/comments`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ content: content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.redirect) {
            window.location.href = data.redirect;
            return;
        }
        
        if (data.success) {
            document.getElementById('commentContent').value = '';
            loadComments(fotoId);
            // Also update count in header
            loadCommentsCount(fotoId);
        } else {
            alert(data.message || 'Gagal menambahkan komentar.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan komentar.');
    });
}

// Delete comment
function deleteComment(commentId) {
    if (!confirm('Hapus komentar ini?')) return;
    
    fetch(`/api/comments/${commentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload comments for album (using representative photo)
            if (albumRepresentativeFotoId) {
                loadComments(albumRepresentativeFotoId);
            }
        } else {
            alert(data.message || 'Gagal menghapus komentar.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus komentar.');
    });
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('photoModal');
    if (modal && modal.classList.contains('show')) {
        if (e.key === 'ArrowLeft') {
            changePhoto(-1);
        } else if (e.key === 'ArrowRight') {
            changePhoto(1);
        } else if (e.key === 'Escape') {
            bootstrap.Modal.getInstance(modal).hide();
        }
    }
});
</script>

<!-- Download Modal with Captcha -->
<div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadModalLabel">
                    <i class="fas fa-download me-2"></i>Unduh Foto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Silakan verifikasi bahwa Anda bukan robot untuk mengunduh foto.</p>
                <div class="mb-3">
                    <label for="captcha" class="form-label">Captcha: <span id="captchaQuestion"></span></label>
                    <input type="text" class="form-control" id="captchaAnswer" placeholder="Jawaban" required>
                </div>
                <div id="captchaError" class="alert alert-danger d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmDownloadBtn">
                    <i class="fas fa-download me-2"></i>Unduh
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Captcha for download
let captchaAnswer = 0;

function generateCaptcha() {
    const num1 = Math.floor(Math.random() * 10) + 1;
    const num2 = Math.floor(Math.random() * 10) + 1;
    captchaAnswer = num1 + num2;
    document.getElementById('captchaQuestion').textContent = `${num1} + ${num2} = ?`;
    document.getElementById('captchaAnswer').value = '';
}

document.getElementById('downloadModal').addEventListener('show.bs.modal', function() {
    generateCaptcha();
});

document.getElementById('confirmDownloadBtn').addEventListener('click', function() {
    const userAnswer = parseInt(document.getElementById('captchaAnswer').value);
    const errorDiv = document.getElementById('captchaError');
    
    if (userAnswer !== captchaAnswer) {
        errorDiv.textContent = 'Jawaban captcha salah. Silakan coba lagi.';
        errorDiv.classList.remove('d-none');
        generateCaptcha();
        return;
    }
    
    // Get photo URL from download button
    const downloadBtn = document.getElementById('downloadBtn');
    const photoUrl = downloadBtn.getAttribute('data-photo-url');
    
    if (photoUrl) {
        // Create download link
        const link = document.createElement('a');
        link.href = photoUrl;
        link.download = '';
        link.click();
        
        // Close modal
        bootstrap.Modal.getInstance(document.getElementById('downloadModal')).hide();
    }
});
</script>
@endsection

