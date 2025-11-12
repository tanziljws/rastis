@extends('layouts.admin')

@section('title', 'Tambah Foto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fas fa-images me-2"></i>
        Upload Foto
    </h2>
    <a href="{{ route('admin.fotos') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Kembali
    </a>
</div>

<!-- Alerts -->
<div id="fotoAlert" class="alert d-none" role="alert"></div>

<!-- Upload Form -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom">
        <h5 class="card-title mb-0 fw-bold">
            <i class="fas fa-cloud-upload-alt me-2 text-primary"></i>
            Upload Foto Baru
        </h5>
        <p class="text-muted mb-0 small">Upload satu atau banyak foto sekaligus</p>
    </div>
    <div class="card-body p-4">
        <form id="uploadFotoForm" enctype="multipart/form-data">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label for="kategori_id" class="form-label-modern">
                            <i class="fas fa-tag me-2"></i>
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <select class="form-control-modern" id="kategori_id" name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                            @if(isset($kategoris) && $kategoris->count() > 0)
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->judul }}</option>
                                @endforeach
                            @endif
                        </select>
                        <small class="form-text-modern">Pilih kategori untuk mengorganisir foto</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label for="judul" class="form-label-modern">
                            <i class="fas fa-heading me-2"></i>
                            Judul Foto
                        </label>
                        <input type="text" 
                               class="form-control-modern" 
                               id="judul" 
                               name="judul" 
                               placeholder="Masukkan judul (opsional)">
                        <small class="form-text-modern">Judul akan diterapkan ke semua foto yang diupload</small>
                    </div>
                </div>
            </div>
            
            <div class="form-group-modern mt-4">
                <label for="files" class="form-label-modern">
                    <i class="fas fa-images me-2"></i>
                    Pilih Foto <span class="text-danger">*</span>
                </label>
                <div class="file-upload-area" id="fileUploadArea">
                    <input type="file" 
                           class="form-control-modern d-none" 
                           id="files" 
                           name="files[]" 
                           accept="image/*" 
                           multiple 
                           required>
                    <div class="file-upload-placeholder" onclick="document.getElementById('files').click()">
                        <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-primary"></i>
                        <h5 class="mb-2">Klik atau drag & drop foto di sini</h5>
                        <p class="text-muted mb-0">Anda bisa memilih banyak foto sekaligus</p>
                        <p class="text-muted small mt-2">Format: JPEG, JPG, PNG | Maks: 5MB per foto</p>
                    </div>
                </div>
                <div id="fileList" class="mt-3"></div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary-modern" id="submitBtn">
                    <i class="fas fa-upload me-2"></i>
                    <span id="submitText">Upload Foto</span>
                </button>
                <button type="reset" class="btn btn-outline-secondary-modern">
                    <i class="fas fa-redo me-2"></i>
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Upload Progress -->
<div id="uploadProgress" class="card shadow-sm border-0 mt-4 d-none">
    <div class="card-header bg-white border-bottom">
        <h5 class="card-title mb-0 fw-bold">
            <i class="fas fa-spinner fa-spin me-2 text-primary"></i>
            Progress Upload
        </h5>
    </div>
    <div class="card-body p-4">
        <div id="progressList"></div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Modern Form Styling */
.card {
    border-radius: 16px;
    overflow: hidden;
}

.card-header {
    padding: 1.5rem;
}

.form-group-modern {
    margin-bottom: 1.5rem;
}

.form-label-modern {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-label-modern i {
    color: #2563eb;
}

.form-control-modern {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #fff;
}

.form-control-modern:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    background: #fff;
}

.form-text-modern {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
}

/* File Upload Area */
.file-upload-area {
    position: relative;
}

.file-upload-placeholder {
    border: 3px dashed #d1d5db;
    border-radius: 12px;
    padding: 3rem 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.file-upload-placeholder:hover {
    border-color: #2563eb;
    background: #eff6ff;
    transform: translateY(-2px);
}

.file-upload-placeholder.dragover {
    border-color: #2563eb;
    background: #dbeafe;
}

/* File List */
.file-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 10px;
    margin-bottom: 0.75rem;
    border: 2px solid #e5e7eb;
}

.file-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.file-item-info {
    flex: 1;
}

.file-item-name {
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.file-item-size {
    font-size: 0.8rem;
    color: #6b7280;
}

.file-item-remove {
    color: #ef4444;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.file-item-remove:hover {
    background: #fee2e2;
    transform: scale(1.1);
}

/* Buttons */
.btn-primary-modern {
    background: #2563eb;
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.btn-primary-modern:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
    color: white;
}

.btn-primary-modern:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn-outline-secondary-modern {
    border: 2px solid #e5e7eb;
    color: #6b7280;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    background: white;
}

.btn-outline-secondary-modern:hover {
    border-color: #d1d5db;
    background: #f9fafb;
    color: #374151;
}

/* Progress */
.progress-item {
    padding: 1rem;
    background: #f9fafb;
    border-radius: 10px;
    margin-bottom: 0.75rem;
    border: 2px solid #e5e7eb;
}

.progress-item-name {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #111827;
}

.progress-bar-modern {
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #2563eb, #3b82f6);
    border-radius: 4px;
    transition: width 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
}

.progress-item.success .progress-bar-fill {
    background: #10b981;
}

.progress-item.error .progress-bar-fill {
    background: #ef4444;
}

/* Loading */
.btn-loading {
    position: relative;
    color: transparent !important;
    pointer-events: none;
}

.btn-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection

@section('scripts')
<script>
let selectedFiles = [];
let uploadQueue = [];

function showAlert(el, type, message) {
    if (!el) return;
    el.className = `alert alert-${type} alert-dismissible fade show`;
    el.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function hideAlert(el) {
    if (!el) return;
    el.className = 'alert d-none';
    el.innerHTML = '';
}

// Load categories (fallback if not loaded from server)
function loadCategories() {
    // Check if categories are already loaded from server
    const select = document.getElementById('kategori_id');
    if (select && select.options.length > 1) {
        // Categories already loaded from server, skip fetch
        return;
    }
    
    // Fallback: Try to load via API if not already loaded
    const url = '{{ url("/admin/api/categories") }}';
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        mode: 'same-origin'
    })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (select && data && data.data && Array.isArray(data.data)) {
                // Only update if select is empty (no categories from server)
                if (select.options.length <= 1) {
                    select.innerHTML = '<option value="">Pilih Kategori</option>';
                    data.data.forEach(kategori => {
                        if (kategori && kategori.id && kategori.judul) {
                            select.innerHTML += `<option value="${kategori.id}">${kategori.judul}</option>`;
                        }
                    });
                }
            }
        })
        .catch(error => {
            // Silently fail if categories already loaded from server
            if (select && select.options.length <= 1) {
                console.error('Error loading categories:', error);
            }
        });
}

// File input handler
const fileInput = document.getElementById('files');
const fileList = document.getElementById('fileList');
const uploadArea = document.getElementById('fileUploadArea');

fileInput.addEventListener('change', function(e) {
    handleFiles(Array.from(e.target.files));
});

// Drag and drop
uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    uploadArea.querySelector('.file-upload-placeholder').classList.add('dragover');
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    uploadArea.querySelector('.file-upload-placeholder').classList.remove('dragover');
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    uploadArea.querySelector('.file-upload-placeholder').classList.remove('dragover');
    const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
    if (files.length > 0) {
        fileInput.files = createFileList(files);
        handleFiles(files);
    }
});

function createFileList(files) {
    const dt = new DataTransfer();
    files.forEach(file => dt.items.add(file));
    return dt.files;
}

function handleFiles(files) {
    selectedFiles = [];
    fileList.innerHTML = '';
    
    files.forEach((file, index) => {
        // Validate
        if (!file.type.startsWith('image/')) {
            showAlert(document.getElementById('fotoAlert'), 'warning', `File ${file.name} bukan gambar, dilewati.`);
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            showAlert(document.getElementById('fotoAlert'), 'warning', `File ${file.name} terlalu besar (max 5MB), dilewati.`);
            return;
        }
        
        selectedFiles.push(file);
        
        // Create preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.dataset.index = index;
            fileItem.innerHTML = `
                <img src="${e.target.result}" alt="${file.name}">
                <div class="file-item-info">
                    <div class="file-item-name">${file.name}</div>
                    <div class="file-item-size">${formatFileSize(file.size)}</div>
                </div>
                <div class="file-item-remove" onclick="removeFile(${index})">
                    <i class="fas fa-times"></i>
                </div>
            `;
            fileList.appendChild(fileItem);
        };
        reader.readAsDataURL(file);
    });
    
    updateSubmitButton();
}

function removeFile(index) {
    selectedFiles = selectedFiles.filter((_, i) => i !== index);
    const fileItem = document.querySelector(`.file-item[data-index="${index}"]`);
    if (fileItem) fileItem.remove();
    updateFileInput();
    updateSubmitButton();
}

function updateFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    fileInput.files = dt.files;
}

function updateSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    if (selectedFiles.length > 0) {
        submitText.textContent = `Upload ${selectedFiles.length} Foto`;
    } else {
        submitText.textContent = 'Upload Foto';
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Form submit
document.getElementById('uploadFotoForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const kategoriId = document.getElementById('kategori_id').value;
    const judul = document.getElementById('judul').value;
    const alertBox = document.getElementById('fotoAlert');
    const submitBtn = document.getElementById('submitBtn');
    const progressCard = document.getElementById('uploadProgress');
    const progressList = document.getElementById('progressList');
    
    hideAlert(alertBox);
    
    if (!kategoriId) {
        showAlert(alertBox, 'warning', 'Kategori wajib dipilih.');
        return;
    }
    
    if (selectedFiles.length === 0) {
        showAlert(alertBox, 'warning', 'Pilih minimal satu foto.');
        return;
    }
    
    // Show progress
    progressCard.classList.remove('d-none');
    progressList.innerHTML = '';
    submitBtn.disabled = true;
    submitBtn.classList.add('btn-loading');
    
    // Generate batch_id for grouping
    const batchId = 'batch_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    
    // Upload files one by one
    let successCount = 0;
    let errorCount = 0;
    
    for (let i = 0; i < selectedFiles.length; i++) {
        const file = selectedFiles[i];
        const progressItem = document.createElement('div');
        progressItem.className = 'progress-item';
        progressItem.id = `progress-${i}`;
        progressItem.innerHTML = `
            <div class="progress-item-name">${file.name}</div>
            <div class="progress-bar-modern">
                <div class="progress-bar-fill" style="width: 0%">0%</div>
            </div>
        `;
        progressList.appendChild(progressItem);
        
        const formData = new FormData();
        formData.append('kategori_id', kategoriId);
        formData.append('judul', judul || '');
        formData.append('file', file);
        formData.append('batch_id', batchId);
        
        try {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                showAlert(alertBox, 'danger', 'CSRF token tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            const response = await fetch('{{ route("admin.fotos.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: formData
            });
            
            // Handle 401 Unauthorized
            if (response.status === 401) {
                const json = await response.json();
                showAlert(alertBox, 'danger', 'Session expired. Redirecting to login...');
                setTimeout(() => {
                    window.location.href = json.redirect || '{{ route("admin.login") }}';
                }, 2000);
                return;
            }
            
            // Handle other errors
            if (!response.ok) {
                let errorMessage = 'Upload gagal';
                try {
                    const json = await response.json();
                    errorMessage = json.message || json.error || 'Upload gagal';
                } catch (e) {
                    errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                }
                errorCount++;
                updateProgress(i, 100, false, errorMessage);
                return;
            }
            
            const json = await response.json();
            
            if (json.success) {
                successCount++;
                updateProgress(i, 100, true);
            } else {
                errorCount++;
                updateProgress(i, 100, false, json.message || 'Upload gagal');
            }
        } catch (error) {
            errorCount++;
            updateProgress(i, 100, false, 'Error: ' + error.message);
        }
    }
    
    // Show result
    submitBtn.disabled = false;
    submitBtn.classList.remove('btn-loading');
    
    if (successCount > 0) {
        showAlert(alertBox, 'success', `${successCount} foto berhasil diupload${errorCount > 0 ? ', ' + errorCount + ' gagal' : ''}.`);
        setTimeout(() => {
            window.location.href = '{{ route('admin.fotos') }}';
        }, 2000);
    } else {
        showAlert(alertBox, 'danger', 'Semua foto gagal diupload.');
    }
});

function updateProgress(index, percent, success, errorMsg) {
    const progressItem = document.getElementById(`progress-${index}`);
    const progressFill = progressItem.querySelector('.progress-bar-fill');
    
    progressFill.style.width = percent + '%';
    progressFill.textContent = percent + '%';
    
    if (success) {
        progressItem.classList.add('success');
        progressFill.textContent = '✓ Selesai';
    } else {
        progressItem.classList.add('error');
        progressFill.textContent = errorMsg || '✗ Gagal';
    }
}

// Reset form
document.querySelector('button[type="reset"]').addEventListener('click', function() {
    selectedFiles = [];
    fileList.innerHTML = '';
    fileInput.value = '';
    hideAlert(document.getElementById('fotoAlert'));
    document.getElementById('uploadProgress').classList.add('d-none');
    updateSubmitButton();
});

// Load categories on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
});
</script>
@endsection
