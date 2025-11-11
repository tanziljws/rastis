@extends('layouts.admin')

@section('title', 'Edit Agenda')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-edit me-2"></i>
        Edit Agenda
    </h2>
    <a href="{{ route('admin.agenda') }}" class="btn btn-outline-secondary-modern">
        <i class="fas fa-arrow-left me-2"></i>
        Kembali
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger-modern alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card-modern shadow-sm">
    <div class="card-header-modern">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-calendar-alt me-2"></i>
            Informasi Agenda
        </h5>
    </div>
    <div class="card-body-modern">
        <form action="{{ route('admin.agenda.update', $agenda) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="form-group-modern">
                        <label for="judul" class="form-label-modern">
                            <i class="fas fa-heading me-2"></i>
                            Judul Agenda <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control-modern @error('judul') is-invalid @enderror" 
                               id="judul" 
                               name="judul" 
                               value="{{ old('judul', $agenda->judul) }}" 
                               placeholder="Masukkan judul agenda"
                               required>
                        @error('judul')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group-modern">
                        <label for="status" class="form-label-modern">
                            <i class="fas fa-info-circle me-2"></i>
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-control-modern @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="aktif" {{ old('status', $agenda->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ old('status', $agenda->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status', $agenda->status) === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group-modern">
                <label for="deskripsi" class="form-label-modern">
                    <i class="fas fa-align-left me-2"></i>
                    Deskripsi <span class="text-danger">*</span>
                </label>
                <textarea class="form-control-modern @error('deskripsi') is-invalid @enderror" 
                          id="deskripsi" 
                          name="deskripsi" 
                          rows="5" 
                          placeholder="Masukkan deskripsi agenda"
                          required>{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label for="tanggal_mulai" class="form-label-modern">
                            <i class="fas fa-calendar me-2"></i>
                            Tanggal Mulai <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control-modern @error('tanggal_mulai') is-invalid @enderror" 
                               id="tanggal_mulai" 
                               name="tanggal_mulai" 
                               value="{{ old('tanggal_mulai', $agenda->tanggal_mulai->format('Y-m-d')) }}" 
                               required>
                        @error('tanggal_mulai')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label for="tanggal_selesai" class="form-label-modern">
                            <i class="fas fa-calendar-check me-2"></i>
                            Tanggal Selesai
                        </label>
                        <input type="date" 
                               class="form-control-modern @error('tanggal_selesai') is-invalid @enderror" 
                               id="tanggal_selesai" 
                               name="tanggal_selesai" 
                               value="{{ old('tanggal_selesai', $agenda->tanggal_selesai ? $agenda->tanggal_selesai->format('Y-m-d') : '') }}">
                        @error('tanggal_selesai')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                        <small class="form-text-modern">Kosongkan jika sama dengan tanggal mulai</small>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label for="waktu_mulai" class="form-label-modern">
                            <i class="fas fa-clock me-2"></i>
                            Waktu Mulai
                        </label>
                        <input type="time" 
                               class="form-control-modern @error('waktu_mulai') is-invalid @enderror" 
                               id="waktu_mulai" 
                               name="waktu_mulai" 
                               value="{{ old('waktu_mulai', $agenda->waktu_mulai) }}">
                        @error('waktu_mulai')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label for="waktu_selesai" class="form-label-modern">
                            <i class="fas fa-clock me-2"></i>
                            Waktu Selesai
                        </label>
                        <input type="time" 
                               class="form-control-modern @error('waktu_selesai') is-invalid @enderror" 
                               id="waktu_selesai" 
                               name="waktu_selesai" 
                               value="{{ old('waktu_selesai', $agenda->waktu_selesai) }}">
                        @error('waktu_selesai')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group-modern">
                <label for="lokasi" class="form-label-modern">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    Lokasi
                </label>
                <input type="text" 
                       class="form-control-modern @error('lokasi') is-invalid @enderror" 
                       id="lokasi" 
                       name="lokasi" 
                       value="{{ old('lokasi', $agenda->lokasi) }}" 
                       placeholder="Contoh: Aula SMKN 4 Kota Bogor">
                @error('lokasi')
                    <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary-modern">
                    <i class="fas fa-save me-2"></i>
                    Update Agenda
                </button>
                <a href="{{ route('admin.agenda') }}" class="btn btn-outline-secondary-modern">
                    <i class="fas fa-times me-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
@include('admin.partials.modern-form-styles')
@endsection
