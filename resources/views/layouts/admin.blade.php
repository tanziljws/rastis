<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Sekolah Galeri</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2563eb;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card-stats {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .card-stats .card-body {
            padding: 1.5rem;
        }
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .school-logo {
            border-radius: 10px;
            background: rgba(255,255,255,0.1);
            padding: 8px;
            transition: all 0.3s ease;
        }
        .school-logo:hover {
            background: rgba(255,255,255,0.2);
            transform: scale(1.05);
        }
        
        /* SMK Negeri 4 Bogor Logo */
        .school-logo {
            width: 80px;
            height: 95px;
            margin: 0 auto 15px;
            position: relative;
        }
        
        .shield {
            width: 100%;
            height: 100%;
            background: #3333ff;
            position: relative;
            clip-path: polygon(0 0, 100% 0, 100% 75%, 50% 100%, 0 75%);
        }
        
        .shield-header {
            background: #3333ff;
            color: white;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            padding: 3px 6px;
            border: 2px solid #ff0000;
            margin: 4px 8px;
            border-radius: 2px;
        }
        
        .shield-wings {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 6px 0;
            padding: 0 10px;
        }
        
        .wing {
            width: 22px;
            height: 16px;
            background: #ff0000;
            position: relative;
            border-radius: 2px;
        }
        
        .wing-lines {
            position: absolute;
            top: 3px;
            width: 100%;
        }
        
        .wing-line {
            height: 2px;
            background: #ff0000;
            margin: 2px 0;
            border-radius: 1px;
        }
        
        .left-wing .wing-line:nth-child(1) { width: 18px; margin-left: 2px; }
        .left-wing .wing-line:nth-child(2) { width: 14px; margin-left: 2px; }
        .left-wing .wing-line:nth-child(3) { width: 10px; margin-left: 2px; }
        
        .right-wing .wing-line:nth-child(1) { width: 18px; margin-left: 2px; }
        .right-wing .wing-line:nth-child(2) { width: 14px; margin-left: 6px; }
        .right-wing .wing-line:nth-child(3) { width: 10px; margin-left: 10px; }
        
        .shield-center {
            width: 16px;
            height: 20px;
            background: #ff0000;
            border-radius: 2px;
            position: relative;
            margin: 0 auto;
        }
        
        .center-emblem {
            width: 12px;
            height: 16px;
            position: absolute;
            top: 2px;
            left: 2px;
            background: white;
            border-radius: 1px;
        }
        
        .flag-red {
            width: 12px;
            height: 5px;
            background: #ff0000;
            position: absolute;
            top: 0;
        }
        
        .flag-white {
            width: 12px;
            height: 5px;
            background: white;
            position: absolute;
            top: 5px;
        }
        
        .flag-element {
            width: 3px;
            height: 4px;
            background: #000;
            position: absolute;
            top: 10px;
            left: 4px;
            border-radius: 1px;
        }
        
        .shield-anchor {
            position: absolute;
            bottom: 18px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 16px;
            background: #ffcc00;
            border-radius: 50% 50% 0 0;
        }
        
        .anchor-ring {
            width: 8px;
            height: 8px;
            border: 2px solid #ffcc00;
            border-radius: 50%;
            position: absolute;
            top: -6px;
            left: 50%;
            transform: translateX(-50%);
            background: transparent;
        }
        
        .anchor-arms {
            width: 24px;
            height: 3px;
            background: #ffcc00;
            position: absolute;
            top: 8px;
            left: -2px;
            border-radius: 2px;
        }
        
        .shield-footer {
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffcc00;
            color: #3333ff;
            font-size: 6px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 8px;
            letter-spacing: 0.5px;
        }
        
        /* ... rest of the styles ... */
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-3 text-center">
                        <div class="mb-3">
                            <img src="{{ asset('images/logo-smkn4-bogor.jpg') }}" alt="SMK NEGERI 4 KOTA BOGOR" class="img-fluid" style="max-height: 100px; width: auto;">
                        </div>
                        <h5 class="text-white mb-1">SMK NEGERI 4 BOGOR</h5>
                        <small class="text-white-50">Admin Dashboard</small>
                    </div>
                    
                    <nav class="nav flex-column mt-3">
                        <!-- DASHBOARD Section -->
                        <div class="px-3 mb-2">
                            <small class="text-white-50 text-uppercase fw-bold">DASHBOARD</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                        
                        <!-- MANAJEMEN GALERI Section -->
                        <div class="px-3 mb-2 mt-4">
                            <small class="text-white-50 text-uppercase fw-bold">MANAJEMEN GALERI</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.fotos') ? 'active' : '' }}" 
                           href="{{ route('admin.fotos') }}">
                            <i class="fas fa-images"></i>
                            Gallery
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.fotos.create') ? 'active' : '' }}" 
                           href="{{ route('admin.fotos.create') }}">
                            <i class="fas fa-plus"></i>
                            Tambah Foto
                        </a>
                        
                        
                        <!-- KONTEN Section -->
                        <div class="px-3 mb-2 mt-4">
                            <small class="text-white-50 text-uppercase fw-bold">KONTEN</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.agenda*') ? 'active' : '' }}" 
                           href="{{ route('admin.agenda') }}">
                            <i class="fas fa-calendar-alt"></i>
                            Agenda
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.informasi*') ? 'active' : '' }}" 
                           href="{{ route('admin.informasi') }}">
                            <i class="fas fa-newspaper"></i>
                            Informasi
                        </a>
                        
                        <!-- MANAJEMEN Section -->
                        <div class="px-3 mb-2 mt-4">
                            <small class="text-white-50 text-uppercase fw-bold">MANAJEMEN</small>
                        </div>
                        <a class="nav-link {{ request()->routeIs('admin.kategori') ? 'active' : '' }}" 
                           href="{{ route('admin.kategori') }}">
                            <i class="fas fa-tags"></i>
                            Kategori
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.hero-background') ? 'active' : '' }}" 
                           href="{{ route('admin.hero-background') }}">
                            <i class="fas fa-image"></i>
                            Hero Background
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.profiles') ? 'active' : '' }}" 
                           href="{{ route('admin.profiles') }}">
                            <i class="fas fa-user"></i>
                            Profil
                        </a>
                        
                        <!-- LOGOUT -->
                        <div class="px-3 mb-2 mt-4">
                            <small class="text-white-50 text-uppercase fw-bold">AKUN</small>
                        </div>
                        <a class="nav-link" href="{{ route('admin.logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <!-- Page Content -->
                    <div class="container-fluid pt-2 px-4 pb-4" style="background: transparent;">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
                // Add delete functionality here
                // Delete photo
            }
        }
    </script>
    
    @yield('scripts')
</body>
</html>
