@extends('layouts.admin')

@section('title', 'Edit Informasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-edit me-2"></i>
        Edit Informasi
    </h2>
    <a href="{{ route('admin.informasi') }}" class="btn btn-outline-secondary-modern">
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
            <i class="fas fa-file-alt me-2"></i>
            Edit Informasi
        </h5>
    </div>
    <div class="card-body-modern">
        <form action="{{ route('admin.informasi.update', $informasi) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="form-group-modern">
                        <label for="judul" class="form-label-modern">
                            <i class="fas fa-heading me-2"></i>
                            Judul Informasi <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control-modern @error('judul') is-invalid @enderror" 
                               id="judul" 
                               name="judul" 
                               value="{{ old('judul', $informasi->judul) }}" 
                               placeholder="Masukkan judul informasi"
                               required>
                        @error('judul')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group-modern">
                        <label for="kategori" class="form-label-modern">
                            <i class="fas fa-tag me-2"></i>
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <select class="form-control-modern @error('kategori') is-invalid @enderror" 
                                id="kategori" 
                                name="kategori" 
                                required>
                            <option value="">Pilih Kategori</option>
                            <option value="Pengumuman" {{ old('kategori', $informasi->kategori) === 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                            <option value="Berita" {{ old('kategori', $informasi->kategori) === 'Berita' ? 'selected' : '' }}>Berita</option>
                            <option value="Event" {{ old('kategori', $informasi->kategori) === 'Event' ? 'selected' : '' }}>Event</option>
                            <option value="Pendidikan" {{ old('kategori', $informasi->kategori) === 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                            <option value="Prestasi" {{ old('kategori', $informasi->kategori) === 'Prestasi' ? 'selected' : '' }}>Prestasi</option>
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group-modern">
                <label for="konten" class="form-label-modern">
                    <i class="fas fa-align-left me-2"></i>
                    Konten <span class="text-danger">*</span>
                </label>
                <textarea class="form-control-modern @error('konten') is-invalid @enderror" 
                          id="konten" 
                          name="konten" 
                          rows="10" 
                          placeholder="Masukkan konten informasi"
                          required>{{ old('konten', $informasi->konten) }}</textarea>
                @error('konten')
                    <div class="invalid-feedback-modern">{{ $message }}</div>
                @enderror
                <small class="form-text-modern">Gunakan format HTML untuk formatting teks</small>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label for="status" class="form-label-modern">
                            <i class="fas fa-info-circle me-2"></i>
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-control-modern @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status" 
                                required>
                            <option value="draft" {{ old('status', $informasi->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $informasi->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label for="tanggal_publish" class="form-label-modern">
                            <i class="fas fa-calendar me-2"></i>
                            Tanggal Publish
                        </label>
                        <input type="date" 
                               class="form-control-modern @error('tanggal_publish') is-invalid @enderror" 
                               id="tanggal_publish" 
                               name="tanggal_publish" 
                               value="{{ old('tanggal_publish', $informasi->tanggal_publish ? \Carbon\Carbon::parse($informasi->tanggal_publish)->format('Y-m-d') : '') }}">
                        @error('tanggal_publish')
                            <div class="invalid-feedback-modern">{{ $message }}</div>
                        @enderror
                        <small class="form-text-modern">Kosongkan untuk publish otomatis saat status = published</small>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary-modern">
                    <i class="fas fa-save me-2"></i>
                    Update Informasi
                </button>
                <a href="{{ route('admin.informasi') }}" class="btn btn-outline-secondary-modern">
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
<style>
#konten {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
}
</style>
@endsection

@section('scripts')
<script>
// Auto-fill publish date when status changes to published
document.getElementById('status').addEventListener('change', function() {
    const publishDateInput = document.getElementById('tanggal_publish');
    if (this.value === 'published' && !publishDateInput.value) {
        const today = new Date().toISOString().split('T')[0];
        publishDateInput.value = today;
    }
});
</script>
@endsection
