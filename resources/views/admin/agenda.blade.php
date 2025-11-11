@extends('layouts.admin')

@section('title', 'Manajemen Agenda')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">
            <i class="fas fa-calendar-alt me-2"></i>
            Manajemen Agenda
        </h2>
        <p class="text-muted mb-0">Kelola agenda dan jadwal kegiatan sekolah</p>
    </div>
    <a href="{{ route('admin.agenda.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>
        Tambah Agenda
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

<div class="card shadow-sm">
    <div class="card-body">
        @if($agendas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 30%">Judul</th>
                            <th style="width: 15%">Tanggal</th>
                            <th style="width: 15%">Waktu</th>
                            <th style="width: 20%">Lokasi</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agendas as $agenda)
                            <tr>
                                <td>
                                    <strong class="d-block mb-1">{{ $agenda->judul }}</strong>
                                    <small class="text-muted">{{ Str::limit($agenda->deskripsi, 60) }}</small>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="badge bg-info mb-1">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $agenda->tanggal_mulai->format('d M Y') }}
                                        </span>
                                        @if($agenda->tanggal_selesai && $agenda->tanggal_selesai != $agenda->tanggal_mulai)
                                            <small class="text-muted">
                                                Sampai: {{ $agenda->tanggal_selesai->format('d M Y') }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($agenda->waktu_mulai)
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $agenda->waktu_mulai }}
                                            @if($agenda->waktu_selesai)
                                                - {{ $agenda->waktu_selesai }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($agenda->lokasi)
                                        <span class="d-inline-flex align-items-center">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                            <span>{{ Str::limit($agenda->lokasi, 30) }}</span>
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($agenda->status === 'aktif')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Aktif
                                        </span>
                                    @elseif($agenda->status === 'selesai')
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-check me-1"></i>Selesai
                                        </span>
                                    @elseif($agenda->status === 'dibatalkan')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>Dibatalkan
                                        </span>
                                    @else
                                        <span class="badge bg-warning">{{ ucfirst($agenda->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.agenda.edit', $agenda) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Edit Agenda"
                                           data-bs-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.agenda.destroy', $agenda) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Hapus Agenda"
                                                    data-bs-toggle="tooltip">
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
            @if($agendas->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $agendas->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-calendar-alt fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted mb-3">Belum ada agenda</h5>
                <p class="text-muted mb-4">Mulai dengan menambahkan agenda pertama Anda.</p>
                <a href="{{ route('admin.agenda.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Agenda Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    font-size: 0.95rem;
}

.badge {
    font-size: 0.8rem;
    padding: 0.4rem 0.75rem;
    font-weight: 500;
}

.btn-group .btn {
    padding: 0.35rem 0.65rem;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #2563eb;
    border-color: #2563eb;
    transform: translateY(-2px);
}

.btn-outline-danger:hover {
    background: #dc3545;
    border-color: #dc3545;
    transform: translateY(-2px);
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
}
</style>
@endsection

@section('scripts')
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
