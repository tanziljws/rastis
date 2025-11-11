@extends('layouts.admin')

@section('title', 'Manajemen Informasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-newspaper me-2"></i>
        Manajemen Informasi
    </h2>
    <a href="{{ route('admin.informasi.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>
        Tambah Informasi
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($informasis->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tanggal Publish</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($informasis as $informasi)
                            <tr>
                                <td>
                                    <strong>{{ $informasi->judul }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit(strip_tags($informasi->konten), 60) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $informasi->kategori }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $informasi->status === 'published' ? 'success' : 'warning' }}">
                                        {{ ucfirst($informasi->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($informasi->tanggal_publish)
                                        <span class="badge bg-info">
                                            {{ \Carbon\Carbon::parse($informasi->tanggal_publish)->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.informasi.edit', $informasi) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.informasi.destroy', $informasi) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Yakin ingin menghapus informasi ini?')" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $informasis->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada informasi</h5>
                <p class="text-muted">Mulai dengan menambahkan informasi pertama.</p>
                <a href="{{ route('admin.informasi.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Informasi Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
</style>
@endsection
