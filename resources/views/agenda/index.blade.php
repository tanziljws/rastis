@extends('layouts.app')

@section('title', 'Agenda Sekolah - SMKN 4 Kota Bogor')

@section('content')
<!-- Hero Section for Agenda -->
<section class="agenda-hero py-5" style="background: #2563eb;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center text-white">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-calendar-alt me-3"></i>
                    Agenda Sekolah
                </h1>
                <p class="lead mb-0">Jadwal kegiatan dan acara SMKN 4 Kota Bogor</p>
            </div>
        </div>
    </div>
</section>

<!-- Agenda Section -->
<section class="py-5">
    <div class="container">
        @if($agendas->count() > 0)
            <!-- Agenda Stats -->
            <div class="row mb-4">
                <div class="col-12">
                    <p class="text-muted mb-0">
                        Menampilkan <strong>{{ $agendas->firstItem() }}</strong> - <strong>{{ $agendas->lastItem() }}</strong> 
                        dari <strong>{{ $agendas->total() }}</strong> agenda aktif
                    </p>
                </div>
            </div>

            <!-- Agenda Grid -->
            <div class="row g-4">
                @foreach($agendas as $agenda)
                    <div class="col-md-6 col-lg-4">
                        <div class="agenda-card h-100">
                            <div class="agenda-card-header">
                                <div class="agenda-date-badge">
                                    <span class="agenda-day">{{ $agenda->tanggal_mulai->format('d') }}</span>
                                    <span class="agenda-month">{{ $agenda->tanggal_mulai->format('M') }}</span>
                                </div>
                                <div class="agenda-status-badge">
                                    @if($agenda->status === 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($agenda->status === 'selesai')
                                        <span class="badge bg-secondary">Selesai</span>
                                    @else
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                </div>
                            </div>
                            <div class="agenda-card-body">
                                <h5 class="agenda-card-title">{{ $agenda->judul }}</h5>
                                <p class="agenda-card-description">{{ Str::limit($agenda->deskripsi, 120) }}</p>
                                
                                <div class="agenda-details">
                                    @if($agenda->waktu_mulai)
                                        <div class="agenda-detail-item">
                                            <i class="fas fa-clock text-primary"></i>
                                            <span>
                                                {{ $agenda->waktu_mulai }}
                                                @if($agenda->waktu_selesai)
                                                    - {{ $agenda->waktu_selesai }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    
                                    @if($agenda->lokasi)
                                        <div class="agenda-detail-item">
                                            <i class="fas fa-map-marker-alt text-primary"></i>
                                            <span>{{ $agenda->lokasi }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($agenda->tanggal_selesai && $agenda->tanggal_selesai != $agenda->tanggal_mulai)
                                        <div class="agenda-detail-item">
                                            <i class="fas fa-calendar-check text-primary"></i>
                                            <span>Sampai {{ $agenda->tanggal_selesai->format('d M Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="agenda-card-footer">
                                <a href="{{ route('agenda.show', $agenda) }}" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-eye me-2"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($agendas->hasPages())
                <div class="row mt-5">
                    <div class="col-12 d-flex justify-content-center">
                        {{ $agendas->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-calendar-alt fa-5x text-muted"></i>
                </div>
                <h3 class="text-muted mb-3">Belum Ada Agenda</h3>
                <p class="text-muted mb-0">Belum ada agenda yang tersedia saat ini.</p>
            </div>
        @endif
    </div>
</section>

<style>
/* Agenda Hero */
.agenda-hero {
    position: relative;
    overflow: hidden;
}

.agenda-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

/* Agenda Card */
.agenda-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.agenda-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}

.agenda-card-header {
    background: #2563eb;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.agenda-date-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: rgba(255,255,255,0.2);
    padding: 0.75rem 1rem;
    border-radius: 12px;
    min-width: 70px;
}

.agenda-day {
    font-size: 2rem;
    font-weight: 700;
    color: white;
    line-height: 1;
}

.agenda-month {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.9);
    text-transform: uppercase;
    font-weight: 600;
    margin-top: 0.25rem;
}

.agenda-status-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.agenda-card-body {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.agenda-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 3rem;
}

.agenda-card-description {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 1rem;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.agenda-details {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.agenda-detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: #666;
}

.agenda-detail-item:last-child {
    margin-bottom: 0;
}

.agenda-detail-item i {
    width: 20px;
    text-align: center;
}

.agenda-card-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #eee;
}

.btn-primary {
    background: #2563eb;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}

/* Empty State */
.empty-state-icon {
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 768px) {
    .agenda-hero h1 {
        font-size: 2rem;
    }
    
    .agenda-card-header {
        padding: 1rem;
    }
    
    .agenda-date-badge {
        min-width: 60px;
        padding: 0.5rem 0.75rem;
    }
    
    .agenda-day {
        font-size: 1.5rem;
    }
}
</style>
@endsection
