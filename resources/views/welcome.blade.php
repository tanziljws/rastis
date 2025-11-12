<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMKN 4 Kota Bogor - Selamat Datang</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-start: #2563eb;
            --primary-end: #2563eb;
            --surface: #ffffff;
            --radius-lg: 16px;
            --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
            --shadow-lg: 0 20px 50px rgba(0,0,0,0.12);
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-section {
            position: relative;
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.85), rgba(29, 78, 216, 0.9));
        }
        
        .hero-background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            z-index: 0;
            transition: opacity 0.5s ease;
        }
        
        .hero-background-image.loading {
            opacity: 0;
        }
        
        .hero-background-image.loaded {
            opacity: 1;
        }
        
        /* Modern gradient overlay for better text readability */
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                rgba(37, 99, 235, 0.4) 0%,
                rgba(29, 78, 216, 0.5) 50%,
                rgba(0, 0, 0, 0.3) 100%
            );
            z-index: 1;
        }
        
        /* Subtle animated overlay */
        .hero-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.1);
            z-index: 1;
            animation: fadeIn 1s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Responsive hero height */
        @media (max-width: 768px) {
            .hero-section {
                min-height: 80vh;
            }
        }
        
        .hero-content {
            z-index: 2;
            max-width: 840px;
            padding: 2.25rem 2rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: var(--radius-lg);
            box-shadow: 0 6px 30px rgba(0,0,0,0.20);
            backdrop-filter: blur(8px);
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            opacity: 0.9;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-hero {
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .btn-primary-hero {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }
        
        .btn-primary-hero:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            color: white;
        }
        
        .btn-outline-hero {
            background: transparent;
            color: white;
            border-color: white;
        }
        
        .btn-outline-hero:hover {
            background: white;
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        
        .school-logo {
            width: 120px;
            height: 120px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.5));
        }
        
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: #2563eb !important;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.3px;
        }
        
        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            color: rgba(255, 255, 255, 0.9) !important;
            transform: translateY(-1px);
            text-shadow: 0 2px 10px rgba(255,255,255,0.25);
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-hero {
                width: 100%;
                max-width: 300px;
            }
        }
        
        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .school-logo {
                width: 80px;
                height: 80px;
            }
        }
        
        /* Map Section Styles */
        .map-container {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            background: white;
        }
        
        .map-container iframe {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .map-info-panel {
            position: absolute;
            top: 20px;
            left: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            max-width: 350px;
            z-index: 10;
            overflow: hidden;
        }
        
        .map-info-content {
            padding: 1.5rem;
        }
        
        .map-info-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        
        .map-info-header h5 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .map-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .stars {
            color: #ffc107;
            font-size: 0.9rem;
        }
        
        .rating-text {
            color: #666;
            font-size: 0.85rem;
        }
        
        .map-info-details .address {
            color: #666;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .map-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .map-actions .btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
        }
        
        .contact-card {
            background: var(--surface);
            padding: 2rem 1rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .contact-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .contact-card h6 {
            color: #333;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .contact-card p {
            color: #666;
            line-height: 1.6;
        }
        
        /* Button Animations */
        .btn-primary {
            background: #2563eb;
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.25);
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.45);
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:active {
            transform: translateY(-1px) scale(1.02);
            transition: all 0.1s ease;
        }
        
        /* Pulse Animation for CTA Buttons */
        .btn-pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(37, 99, 235, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0);
            }
        }
        
        /* Bounce Animation */
        .btn-bounce {
            animation: bounce 1s ease-in-out;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        /* Shake Animation */
        .btn-shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        /* Glow Effect */
        .btn-glow {
            box-shadow: 0 0 20px rgba(37, 99, 235, 0.5);
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from {
                box-shadow: 0 0 20px rgba(37, 99, 235, 0.5);
            }
            to {
                box-shadow: 0 0 30px rgba(37, 99, 235, 0.8);
            }
        }
        
        /* Floating Animation */
        .btn-float {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
        }
        
        /* Button Loading Animation */
        .btn-loading {
            position: relative;
            color: transparent;
        }
        
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        
        /* Special Button Styles */
        .btn-hero {
            background: linear-gradient(135deg, var(--primary-start) 0%, var(--primary-end) 100%);
            border: none;
            border-radius: 50px;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.35);
        }
        
        .btn-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }
        
        .btn-hero:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 15px 35px rgba(37, 99, 235, 0.5);
        }
        
        .btn-hero:hover::before {
            left: 100%;
        }
        
        .btn-hero:active {
            transform: translateY(-2px) scale(1.05);
        }
        
        /* Ripple Effect */
        .btn {
            position: relative;
            overflow: hidden;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        /* Enhanced Card Animations */
        .card {
            transition: all 0.3s ease;
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
        }
        
        .card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
        }
        
        /* Icon Animations */
        .fa-3x {
            transition: all 0.3s ease;
        }
        
        .card:hover .fa-3x {
            transform: scale(1.1) rotate(5deg);
        }
        
        /* Text Animation */
        .card-title {
            transition: all 0.3s ease;
        }
        
        .card:hover .card-title {
            color: #2563eb;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .map-container iframe {
                height: 350px;
            }
            
            .map-info-panel {
                position: relative !important;
                top: auto !important;
                left: auto !important;
                max-width: 100% !important;
                margin: 1rem !important;
            }
            
            .map-info-content {
                padding: 1rem;
            }
            
            .map-actions {
                flex-direction: column;
            }
            
            .map-actions .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .contact-card {
                margin-bottom: 1rem;
            }
        }
        
        /* Section Title and Subtitle Styles */
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 0;
        }
        
        @media (max-width: 768px) {
            .section-title {
                font-size: 2rem;
            }
            
            .section-subtitle {
                font-size: 1rem;
            }
        }
            </style>
    </head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo-smkn4-bogor.png') }}" alt="SMK NEGERI 4 KOTA BOGOR" class="me-2" style="height: 40px; width: auto; background: transparent; padding: 0;">
                <span>Sekolah Galeri</span>
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
                        <a class="nav-link" href="{{ route('agenda.index') }}">Agenda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('informasi.index') }}">Informasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('galeri.index') }}">Galeri</a>
                    </li>
                    </ul>
                </div>
        </div>
    </nav>

    <!-- Hero Section -->
            <section class="hero-section" id="beranda">
                <img src="{{ $heroBackground ?? asset('images/hero-default.jpg') }}" 
                     alt="SMK NEGERI 4 KOTA BOGOR" 
                     class="hero-background-image loading"
                     loading="lazy"
                     onload="this.classList.add('loaded')"
                     onerror="this.src='{{ asset('images/hero-default.jpg') }}'; this.classList.add('loaded');">
        <div class="hero-content">
            
            <!-- Main Title -->
            <h1 class="hero-title">
                Selamat Datang di<br>
                <span style="color: #ffc107;">{{ isset($profil) && $profil->nama_sekolah ? $profil->nama_sekolah : 'SMKN 4 Kota Bogor' }}</span>
            </h1>
            
            <!-- Subtitle -->
            <p class="hero-subtitle">
                {{ isset($profil) && $profil->deskripsi ? Str::limit($profil->deskripsi, 80) : 'Mencetak Generasi Unggul, Berkarakter, dan Siap Kerja' }}
            </p>
            
        </div>
    </section>



    <!-- Profil Sekolah Section -->
    <section id="profil" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Profil Sekolah</h2>
                    <p class="section-subtitle">Mengenal lebih dekat SMKN 4 Kota Bogor</p>
                </div>
            </div>
            
            <!-- Tentang Sekolah Section - Full Width Split Layout -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-md-5">
                            <div class="row align-items-center">
                                <!-- Description on Left -->
                                <div class="col-lg-6 pe-lg-4">
                                    <h3 class="text-primary mb-4">
                                        <i class="fas fa-school me-2"></i>
                                        Tentang Sekolah
                                    </h3>
                                    <p class="card-text mb-0 text-start" style="line-height: 1.8; font-size: 1.05rem;">
                                        {{ isset($profil) && $profil->deskripsi ? $profil->deskripsi : 'SMK Negeri 4 Kota Bogor adalah sekolah menengah kejuruan yang berkomitmen untuk mencetak generasi unggul, berkarakter, dan siap kerja. Dengan fasilitas modern dan tenaga pengajar profesional, kami mempersiapkan siswa untuk menghadapi tantangan dunia industri.' }}
                                    </p>
                                </div>
                                
                                <!-- Stats on Right -->
                                <div class="col-lg-6 ps-lg-4 mt-4 mt-lg-0">
                                    <div class="border-start border-primary border-3 ps-4">
                                        <h3 class="text-primary mb-4">
                                            <i class="fas fa-chart-bar me-2"></i>
                                            Statistik
                                        </h3>
                                        <div class="row g-4">
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                                                    <h4 class="mb-1">1000+</h4>
                                                    <small class="text-muted d-block">Siswa Aktif</small>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center">
                                                    <i class="fas fa-chalkboard-teacher fa-3x text-primary mb-3"></i>
                                                    <h4 class="mb-1">80+</h4>
                                                    <small class="text-muted d-block">Tenaga Pengajar</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Visi & Misi Section - Full Width Split Layout -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4 p-md-5">
                            <div class="row">
                                <!-- Visi -->
                                <div class="col-lg-6 pe-lg-4 position-relative">
                                    <div class="h-100 d-flex flex-column justify-content-center">
                                        <div class="text-center mb-3">
                                            <i class="fas fa-eye fa-3x text-primary mb-3"></i>
                                            <h4 class="card-title mb-3">Visi</h4>
                                        </div>
                                        <p class="card-text mb-0 text-start" style="line-height: 1.8; white-space: pre-line;">{{ isset($profil) && $profil->visi ? $profil->visi : 'Menjadi SMK unggul yang menghasilkan lulusan berkarakter, kompeten, dan berdaya saing global.' }}</p>
                                    </div>
                                    <!-- Divider -->
                                    <div class="d-lg-block d-none position-absolute top-0 end-0 h-100" style="width: 1px; background: #dee2e6; transform: translateX(50%);"></div>
                                </div>
                                
                                <!-- Misi -->
                                <div class="col-lg-6 ps-lg-4 mt-4 mt-lg-0">
                                    <div class="h-100 d-flex flex-column justify-content-center">
                                        <div class="text-center mb-3">
                                            <i class="fas fa-bullseye fa-3x text-primary mb-3"></i>
                                            <h4 class="card-title mb-3">Misi</h4>
                                        </div>
                                        <p class="card-text mb-0 text-start" style="line-height: 1.8; white-space: pre-line;">{{ isset($profil) && $profil->misi ? $profil->misi : '1. Menyelenggarakan pendidikan yang berkualitas dan berwawasan global
2. Mengembangkan potensi siswa secara optimal melalui kegiatan akademik dan non-akademik
3. Menanamkan nilai-nilai karakter dan akhlak mulia
4. Mewujudkan lingkungan sekolah yang nyaman, aman, dan kondusif
5. Mengembangkan kerjasama dengan berbagai pihak untuk meningkatkan kualitas pendidikan' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section id="lokasi" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title">Lokasi Sekolah</h2>
                    <p class="section-subtitle">Temukan lokasi SMKN 4 Kota Bogor di peta</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.123456789!2d106.7890123456789!3d-6.601234567890123!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5a123456789%3A0x123456789abcdef0!2sJl.%20Raya%20Tajur%2C%20Kp.%20Buntar%20RT.02%2FRW.08%2C%20Kel.%20Muara%20sari%2C%20Kec.%20Bogor%20Selatan%2C%20RT.03%2FRW.08%2C%20Muarasari%2C%20Kec.%20Bogor%20Sel.%2C%20Kota%20Bogor%2C%20Jawa%20Barat%2016137!5e0!3m2!1sen!2sid!4v1234567890123!5m2!1sen!2sid"
                            width="100%" 
                            height="450" 
                            style="border:0; border-radius: 15px;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        
                        <!-- Map Info Panel -->
                        <div class="map-info-panel">
                            <div class="map-info-content">
                                <div class="map-info-header">
                                    <h5><i class="fas fa-map-marker-alt text-danger me-2"></i>SMKN 4 Kota Bogor</h5>
                                    <div class="map-rating">
                                        <span class="stars">★★★★★</span>
                                        <span class="rating-text">4.8 (127 reviews)</span>
                                    </div>
                                </div>
                                
                                <div class="map-info-details">
                                    <p class="address">
                                        <i class="fas fa-location-dot me-2"></i>
                                        Jl. Raya Tajur, Kp. Buntar RT.02/RW.08, Kel. Muara sari, Kec. Bogor Selatan, RT.03/RW.08, Muarasari, Kec. Bogor Sel., Kota Bogor, Jawa Barat 16137
                                    </p>
                                    
                                    <div class="map-actions">
                                        <a href="https://www.google.com/maps/dir//Jl.+Raya+Tajur,+Kp.+Buntar+RT.02%2FRW.08,+Kel.+Muara+sari,+Kec.+Bogor+Selatan,+RT.03%2FRW.08,+Muarasari,+Kec.+Bogor+Sel.,+Kota+Bogor,+Jawa+Barat+16137" 
                                           target="_blank" 
                                           class="btn btn-primary btn-sm me-2 btn-shake">
                                            <i class="fas fa-directions me-1"></i>
                                            Directions
                                        </a>
                                        <a href="https://www.google.com/maps/search/?api=1&query=Jl.+Raya+Tajur,+Kp.+Buntar+RT.02%2FRW.08,+Kel.+Muara+sari,+Kec.+Bogor+Selatan,+RT.03%2FRW.08,+Muarasari,+Kec.+Bogor+Sel.,+Kota+Bogor,+Jawa+Barat+16137" 
                                           target="_blank" 
                                           class="btn btn-outline-primary btn-sm btn-glow">
                                            <i class="fas fa-external-link-alt me-1"></i>
                                            View larger map
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-4 text-center mb-3">
                    <div class="contact-card">
                        <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                        <h6>Alamat</h6>
                        <p class="mb-0">Jl. Raya Tajur, Kp. Buntar RT.02/RW.08<br>Kel. Muara sari, Kec. Bogor Selatan<br>Kota Bogor, Jawa Barat 16137</p>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <div class="contact-card">
                        <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                        <h6>Telepon</h6>
                        <p class="mb-0">(0251) 1234567</p>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-3">
                    <div class="contact-card">
                        <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                        <h6>Email</h6>
                        <p class="mb-0">info@smkn4bogor.sch.id</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer bg-dark text-white py-4">
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
                        admin@smkn4kotabogor.sch.id
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-phone me-2"></i>
                        (0251) 7547381  
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
    
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Navbar background change on scroll - removed since we're using consistent bg-primary
        
        // Enhanced Button Animations and Interactions
        document.addEventListener('DOMContentLoaded', function() {
            const mapContainer = document.querySelector('.map-container');
            const mapInfoPanel = document.querySelector('.map-info-panel');
            
            // Add hover effects to map container
            mapContainer.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 15px 40px rgba(0,0,0,0.15)';
            });
            
            mapContainer.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
            });
            
            // Enhanced Button Interactions
            const allButtons = document.querySelectorAll('.btn');
            
            allButtons.forEach(button => {
                // Add ripple effect on click
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
                
                // Add sound effect simulation (visual feedback)
                button.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
                
                // Add loading state for external links
                if (button.hasAttribute('target') && button.getAttribute('target') === '_blank') {
                    button.addEventListener('click', function() {
                        this.classList.add('btn-loading');
                        setTimeout(() => {
                            this.classList.remove('btn-loading');
                        }, 2000);
                    });
                }
            });
            
            // Staggered animation for cards
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });
            
            // Add click tracking for analytics (optional)
            const mapButtons = document.querySelectorAll('.map-actions .btn');
            mapButtons.forEach(button => {
                button.addEventListener('click', function() {
                    console.log('Map action clicked:', this.textContent.trim());
                });
            });
            
            // Responsive map info panel
            function adjustMapInfoPanel() {
                if (window.innerWidth < 768) {
                    mapInfoPanel.style.position = 'relative';
                    mapInfoPanel.style.top = 'auto';
                    mapInfoPanel.style.left = 'auto';
                    mapInfoPanel.style.maxWidth = '100%';
                    mapInfoPanel.style.margin = '1rem';
                } else {
                    mapInfoPanel.style.position = 'absolute';
                    mapInfoPanel.style.top = '20px';
                    mapInfoPanel.style.left = '20px';
                    mapInfoPanel.style.maxWidth = '350px';
                    mapInfoPanel.style.margin = '0';
                }
            }
            
            // Call on load and resize
            adjustMapInfoPanel();
            window.addEventListener('resize', adjustMapInfoPanel);
            
            // Add scroll-triggered animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, observerOptions);
            
            // Observe all animated elements
            document.querySelectorAll('.btn-pulse, .btn-float, .btn-glow, .btn-bounce').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
    </body>
</html>