@extends('layouts.admin')

@section('title', 'Kelola Posts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-newspaper me-2"></i>
        Kelola Posts
    </h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPostModal">
        <i class="fas fa-plus me-2"></i>
        Tambah Post
    </button>
</div>

<!-- Posts Table -->
<div class="card">
    <div class="card-body">
        @if($posts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Petugas</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td>
                                    <strong>{{ Str::limit($post->judul, 50) }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($post->isi, 100) }}</small>
                                </td>
                                <td>
                                    @if($post->kategori)
                                        <span class="badge bg-primary">{{ $post->kategori->judul }}</span>
                                    @else
                                        <span class="badge bg-secondary">Tanpa Kategori</span>
                                    @endif
                                </td>
                                <td>
                                    <i class="fas fa-user me-1"></i>
                                    {{ $post->petugas->username }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $post->status === 'published' ? 'success' : ($post->status === 'draft' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $post->created_at->format('d M Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="editPost({{ $post->id }}, '{{ $post->judul }}', '{{ $post->isi }}', '{{ $post->kategori_id }}', '{{ $post->status }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="deletePost({{ $post->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada posts</h5>
                <p class="text-muted">Mulai dengan menambahkan post pertama Anda.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPostModal">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Post Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Post Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Post Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createPostForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Post</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi Post</label>
                        <textarea class="form-control" id="isi" name="isi" rows="5" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kategori_id" class="form-label">Kategori</label>
                                <select class="form-select" id="kategori_id" name="kategori_id">
                                    <option value="">Pilih Kategori</option>
                                    <!-- Will be populated via AJAX -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Simpan Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Post
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPostForm">
                <input type="hidden" id="edit_post_id" name="post_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_judul" class="form-label">Judul Post</label>
                        <input type="text" class="form-control" id="edit_judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_isi" class="form-label">Isi Post</label>
                        <textarea class="form-control" id="edit_isi" name="isi" rows="5" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_kategori_id" class="form-label">Kategori</label>
                                <select class="form-select" id="edit_kategori_id" name="kategori_id">
                                    <option value="">Pilih Kategori</option>
                                    <!-- Will be populated via AJAX -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select" id="edit_status" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Update Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Load categories for select dropdowns
function loadCategories() {
    fetch('/api/kategori')
        .then(response => response.json())
        .then(data => {
            const kategoriSelects = ['kategori_id', 'edit_kategori_id'];
            kategoriSelects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    select.innerHTML = '<option value="">Pilih Kategori</option>';
                    data.data.forEach(kategori => {
                        select.innerHTML += `<option value="${kategori.id}">${kategori.judul}</option>`;
                    });
                }
            });
        });
}

// Create post
document.getElementById('createPostForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('/api/posts', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + '{{ session("admin_token") }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.data) {
            location.reload();
        } else {
            alert('Error: ' + JSON.stringify(data));
        }
    });
});

// Edit post
function editPost(id, judul, isi, kategori_id, status) {
    document.getElementById('edit_post_id').value = id;
    document.getElementById('edit_judul').value = judul;
    document.getElementById('edit_isi').value = isi;
    document.getElementById('edit_kategori_id').value = kategori_id;
    document.getElementById('edit_status').value = status;
    
    new bootstrap.Modal(document.getElementById('editPostModal')).show();
}

// Update post
document.getElementById('editPostForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const postId = document.getElementById('edit_post_id').value;
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    delete data.post_id;
    
    fetch(`/api/posts/${postId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + '{{ session("admin_token") }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.data) {
            location.reload();
        } else {
            alert('Error: ' + JSON.stringify(data));
        }
    });
});

// Delete post
function deletePost(id) {
    if (confirm('Apakah Anda yakin ingin menghapus post ini?')) {
        fetch(`/api/posts/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + '{{ session("admin_token") }}'
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error menghapus post');
            }
        });
    }
}

// Load categories when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
});
</script>
@endsection
