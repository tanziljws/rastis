@extends('layouts.admin')

@section('title', 'Kelola Fotos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-camera me-2"></i>
        Kelola Fotos
    </h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadFotoModal">
        <i class="fas fa-upload me-2"></i>
        Upload Foto
    </button>
</div>

<!-- Fotos Grid -->
<div class="card">
    <div class="card-body">
        @if($fotos->count() > 0)
            <div class="row g-4">
                @foreach($fotos as $foto)
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . $foto->file) }}" 
                                 class="card-img-top" 
                                 alt="{{ $foto->judul ?? 'Foto' }}"
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h6 class="card-title">{{ $foto->judul ?? 'Foto Sekolah' }}</h6>
                                @if($foto->galery && $foto->galery->post)
                                    <p class="card-text small text-muted">
                                        {{ $foto->galery->post->judul }}
                                    </p>
                                @endif
                                <small class="text-muted">
                                    {{ $foto->created_at->format('d M Y') }}
                                </small>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="editFoto({{ $foto->id }}, '{{ $foto->judul }}', {{ $foto->galery_id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteFoto({{ $foto->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $fotos->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada foto</h5>
                <p class="text-muted">Mulai dengan mengupload foto pertama Anda.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadFotoModal">
                    <i class="fas fa-upload me-2"></i>
                    Upload Foto Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Upload Foto Modal -->
<div class="modal fade" id="uploadFotoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-upload me-2"></i>
                    Upload Foto Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadFotoForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="galery_id" class="form-label">Pilih Galery</label>
                        <select class="form-select" id="galery_id" name="galery_id" required>
                            <option value="">Pilih Galery</option>
                            <!-- Will be populated via AJAX -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">File Foto</label>
                        <input type="file" class="form-control" id="file" name="file" accept="image/*" required>
                        <small class="text-muted">Format: JPEG, JPG, PNG, GIF, WEBP. Maksimal 5MB.</small>
                    </div>
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Foto (Opsional)</label>
                        <input type="text" class="form-control" id="judul" name="judul">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>
                        Upload Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Foto Modal -->
<div class="modal fade" id="editFotoModal" tabindex="-1">
    <div class="modal-dialog">
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
                    <div class="mb-3">
                        <label for="edit_galery_id" class="form-label">Pilih Galery</label>
                        <select class="form-select" id="edit_galery_id" name="galery_id" required>
                            <option value="">Pilih Galery</option>
                            <!-- Will be populated via AJAX -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_file" class="form-label">File Foto (Opsional)</label>
                        <input type="file" class="form-control" id="edit_file" name="file" accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_judul" class="form-label">Judul Foto</label>
                        <input type="text" class="form-control" id="edit_judul" name="judul">
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
@endsection

@section('scripts')
<script>
// Load galeries for select dropdowns
function loadGaleries() {
    fetch('/api/galeries')
        .then(response => response.json())
        .then(data => {
            const galerySelects = ['galery_id', 'edit_galery_id'];
            galerySelects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    select.innerHTML = '<option value="">Pilih Galery</option>';
                    data.data.forEach(galery => {
                        select.innerHTML += `<option value="${galery.id}">${galery.post?.judul || 'Galery ' + galery.id}</option>`;
                    });
                }
            });
        });
}

// Upload foto
document.getElementById('uploadFotoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/api/fotos', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + '{{ session("admin_token") }}'
        },
        body: formData
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

// Edit foto
function editFoto(id, judul, galery_id) {
    document.getElementById('edit_foto_id').value = id;
    document.getElementById('edit_judul').value = judul;
    document.getElementById('edit_galery_id').value = galery_id;
    
    new bootstrap.Modal(document.getElementById('editFotoModal')).show();
}

// Update foto
document.getElementById('editFotoForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fotoId = document.getElementById('edit_foto_id').value;
    const formData = new FormData(this);
    
    fetch(`/api/fotos/${fotoId}`, {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + '{{ session("admin_token") }}'
        },
        body: formData
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

// Delete foto
function deleteFoto(id) {
    if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
        fetch(`/api/fotos/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + '{{ session("admin_token") }}'
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error menghapus foto');
            }
        });
    }
}

// Load galeries when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadGaleries();
});
</script>
@endsection
