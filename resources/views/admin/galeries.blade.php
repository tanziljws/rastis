@extends('layouts.admin')

@section('title', 'Kelola Galeries')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-images me-2"></i>
        Kelola Galeries
    </h2>
</div>

<!-- Galeries Table -->
<div class="card">
    <div class="card-body">
        @if($galeries->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Post</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Jumlah Foto</th>
                            <th>Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($galeries as $galery)
                            <tr>
                                <td>
                                    <strong>{{ $galery->post->judul ?? 'Post tidak ditemukan' }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $galery->position }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $galery->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($galery->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $galery->fotos->count() }}</span>
                                </td>
                                <td>
                                    <small>{{ $galery->created_at->format('d M Y H:i') }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $galeries->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada galeries</h5>
                <p class="text-muted">Galeries akan dibuat otomatis saat membuat posts.</p>
            </div>
        @endif
    </div>
</div>
@endsection
