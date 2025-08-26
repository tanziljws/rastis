@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-tags me-2"></i>
        Kelola Kategori
    </h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createKategoriModal">
        <i class="fas fa-plus me-2"></i>
        Tambah Kategori
    </button>
</div>

<!-- Kategori Table -->
<div class="card">
    <div class="card-body">
        @if($kategoris->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Jumlah Posts</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kategoris as $kategori)
                            <tr>
                                <td>
                                    <strong>{{ $kategori->judul }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $kategori->posts_count }}</span>
                                </td>
                                <td>
                                    <small>{{ $kategori->created_at->format('d M Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="editKategori({{ $kategori->id }}, '{{ $kategori->judul }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteKategori({{ $kategori->id }})">
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
                {{ $kategoris->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada kategori</h5>
                <p class="text-muted">Mulai dengan menambahkan kategori pertama Anda.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createKategoriModal">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Kategori Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Kategori Modal -->
<div class="modal fade" id="createKategoriModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Kategori Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createKategoriForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Kategori</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Kategori Modal -->
<div class="modal fade" id="editKategoriModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Kategori
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editKategoriForm">
                <input type="hidden" id="edit_kategori_id" name="kategori_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_judul" class="form-label">Judul Kategori</label>
                        <input type="text" class="form-control" id="edit_judul" name="judul" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Update Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Create kategori
document.getElementById('createKategoriForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('/api/kategori', {
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

// Edit kategori
function editKategori(id, judul) {
    document.getElementById('edit_kategori_id').value = id;
    document.getElementById('edit_judul').value = judul;
    
    new bootstrap.Modal(document.getElementById('editKategoriModal')).show();
}

// Update kategori
document.getElementById('editKategoriForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const kategoriId = document.getElementById('edit_kategori_id').value;
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    delete data.kategori_id;
    
    fetch(`/api/kategori/${kategoriId}`, {
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

// Delete kategori
function deleteKategori(id) {
    if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
        fetch(`/api/kategori/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + '{{ session("admin_token") }}'
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error menghapus kategori');
            }
        });
    }
}
</script>
@endsection
