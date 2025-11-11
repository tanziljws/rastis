<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sekolah Galeri')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .hero-section {
            background-image: linear-gradient(135deg, rgba(0, 123, 255, 0.8), rgba(255, 193, 7, 0.6)), url('https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1200&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
            padding: 100px 0;
            min-height: 100vh;
            position: relative;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .school-logo-badge {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #ffc107, #ff9800);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(255, 193, 7, 0.3);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem 1rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .stat-card i {
            color: #ffc107;
        }
        
        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0.5rem 0;
        }
        
        .stat-card p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .section-badge {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #007bff, #0056b3);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
        }
        
        .divider {
            width: 80px;
            height: 4px;
            background: linear-gradient(45deg, #007bff, #ffc107);
            border-radius: 2px;
        }
        
        .bg-gradient {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .min-vh-75 {
            min-height: 75vh;
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #007bff, #0056b3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 25px rgba(0, 123, 255, 0.3);
        }
        
        .feature-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #333;
        }
        
        .feature-text {
            color: #666;
            line-height: 1.6;
            margin: 0;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }
        .gallery-item img {
            transition: transform 0.3s ease;
        }
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 40px 0;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-school me-2"></i>
                Sekolah Galeri
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#profil">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('agenda.index') }}">Agenda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('informasi.index') }}">Informasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('galeri.index') }}">Galeri</a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>SMKN 4 Kota Bogor</h5>
                    <p class="mb-0">Mencetak Generasi Unggul, Berkarakter, dan Siap Kerja</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Jl. Raya Tajur, Kp. Buntar RT.02/RW.08, Kel. Muara sari, Kec. Bogor Selatan, Kota Bogor, Jawa Barat 16137
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-envelope me-2"></i>
                        info@smkn4bogor.sch.id
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-phone me-2"></i>
                        (0251) 1234567
                    </p>
                </div>
            </div>
            <hr class="my-3">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">
                        &copy; {{ date('Y') }} SMKN 4 Kota Bogor. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
