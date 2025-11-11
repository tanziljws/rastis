<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Sekolah Galeri</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .login-header {
            background: #2563eb;
            color: white;
            padding: 40px;
            text-align: center;
        }
        .login-body {
            padding: 40px;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }
        .btn-login {
            background: #2563eb;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .back-link {
            color: white;
            text-decoration: none;
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .back-link:hover {
            color: rgba(255,255,255,0.8);
        }
    </style>
</head>
<body>
    <a href="{{ route('home') }}" class="back-link">
        <i class="fas fa-arrow-left me-2"></i>
        Kembali ke Website
    </a>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-school fa-3x mb-3"></i>
                        <h3 class="mb-0">Admin Login</h3>
                        <p class="mb-0">Sekolah Galeri</p>
                    </div>
                    
                    <div class="login-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.login.post') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Nama atau Email
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Masukkan nama atau email"
                                       autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email (opsional jika sudah isi nama)
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="Masukkan email (opsional)">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-login w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Login
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Default: admin / admin123 atau admin@example.com / admin123
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Make name/email fields work together
        document.addEventListener('DOMContentLoaded', function() {
            const nameField = document.getElementById('name');
            const emailField = document.getElementById('email');
            
            function updateFieldRequirements() {
                if (nameField.value.trim()) {
                    emailField.removeAttribute('required');
                    emailField.placeholder = 'Masukkan email (opsional)';
                } else if (emailField.value.trim()) {
                    nameField.removeAttribute('required');
                    nameField.placeholder = 'Masukkan nama (opsional)';
                } else {
                    nameField.setAttribute('required', 'required');
                    emailField.setAttribute('required', 'required');
                    nameField.placeholder = 'Masukkan nama atau email';
                    emailField.placeholder = 'Masukkan email';
                }
            }
            
            nameField.addEventListener('input', updateFieldRequirements);
            emailField.addEventListener('input', updateFieldRequirements);
            
            // Initial check
            updateFieldRequirements();
        });
    </script>
</body>
</html>
