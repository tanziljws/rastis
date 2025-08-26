@extends('layouts.admin')

@section('title', 'Kelola Profiles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-user me-2"></i>
        Kelola Profiles
    </h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProfileModal">
        <i class="fas fa-plus me-2"></i>
        Tambah Profile
    </button>
</div>

<!-- Profiles Table -->
<div class="card">
    <div class="card-body">
        @if($profiles->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Isi</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($profiles as $profile)
                            <tr>
                                <td>
                                    <strong>{{ $profile->judul }}</strong>
                                </td>
                                <td>
                                    <small>{{ Str::limit($profile->isi, 100) }}</small>
                                </td>
                                <td>
                                    <small>{{ $profile->created_at->format('d M Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="editProfile({{ $profile->id }}, '{{ $profile->judul }}', '{{ $profile->isi }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteProfile({{ $profile->id }})">
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
                {{ $profiles->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-user fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada profiles</h5>
                <p class="text-muted">Mulai dengan menambahkan profile pertama Anda.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProfileModal">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Profile Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Profile Modal -->
<div class="modal fade" id="createProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Profile Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createProfileForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Profile</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi Profile</label>
                        <textarea class="form-control" id="isi" name="isi" rows="8" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Simpan Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProfileForm">
                <input type="hidden" id="edit_profile_id" name="profile_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_judul" class="form-label">Judul Profile</label>
                        <input type="text" class="form-control" id="edit_judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_isi" class="form-label">Isi Profile</label>
                        <textarea class="form-control" id="edit_isi" name="isi" rows="8" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Create profile
document.getElementById('createProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('/api/profiles', {
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

// Edit profile
function editProfile(id, judul, isi) {
    document.getElementById('edit_profile_id').value = id;
    document.getElementById('edit_judul').value = judul;
    document.getElementById('edit_isi').value = isi;
    
    new bootstrap.Modal(document.getElementById('editProfileModal')).show();
}

// Update profile
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const profileId = document.getElementById('edit_profile_id').value;
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    delete data.profile_id;
    
    fetch(`/api/profiles/${profileId}`, {
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

// Delete profile
function deleteProfile(id) {
    if (confirm('Apakah Anda yakin ingin menghapus profile ini?')) {
        fetch(`/api/profiles/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + '{{ session("admin_token") }}'
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error menghapus profile');
            }
        });
    }
}
</script>
@endsection
