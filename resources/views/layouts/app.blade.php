<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Siakad')</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @stack('styles')
    <style>
        html, body {
            height: 100%;
        }
        .wrapper {
            display: flex;
            height: calc(100vh - 56px);
        }
        .sidebar {
            width: 250px;
            background-color: #212529;
            color: white;
            flex-shrink: 0;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 10px 20px;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .content {
            flex-grow: 1;
            background-color: #f8f9fa;
            overflow-y: auto;
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <a class="navbar-brand fw-bold" href="#">Sistem Informasi Siakad</a>
        <div class="ms-auto text-light">
            <span>Halo, Administrator</span>
        </div>
    </nav>

    <!-- Layout Wrapper (Sidebar & Konten) -->
    <div class="wrapper">

        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column p-3">
            <h5 class="text-secondary text-uppercase fs-6 px-3 mb-3">Menu Utama</h5>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ url('/tampilmahasiswa') }}" class="nav-link {{ request()->is('tampilmahasiswa*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Mahasiswa
                    </a>
                </li>
                <li>
                    <a href="{{ route('dosen.index') }}" class="nav-link {{ request()->is('dosen*') || request()->is('getDosen') ? 'active' : '' }}">
                        <i class="bi bi-person-badge me-2"></i> Dosen
                    </a>
                </li>
                <li>
                    <a href="{{ route('mata-kuliah.index') }}" class="nav-link {{ request()->is('mata-kuliah*') ? 'active' : '' }}">
                        <i class="bi bi-book me-2"></i> Mata Kuliah
                    </a>
                </li>
                <li>
                    <a href="{{ route('pengawas.index') }}" class="nav-link {{ request()->is('pengawas*') ? 'active' : '' }}">
                        <i class="bi bi-person-check me-2"></i> Pengawas
                    </a>
                </li>
            </ul>
            <hr>
            <div class="text-center text-muted small">
                &copy; 2026 Siakad
            </div>
        </div>

        <div class="content p-4">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>

    </div>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>