<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FutZone - @yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary-color: #4CAF50;
            --sidebar-bg: #f9f9f9;
            --sidebar-hover: #e9e9e9;
            --sidebar-active: #4CAF50;
            --sidebar-active-text: white;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        .main-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .logout-btn {
            color: white;
            text-decoration: none;
        }

        .admin-content {
            display: flex;
            min-height: calc(100vh - 60px);
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            margin-right: 15px;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
        }

        .sidebar {
            width: 270px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid #e0e0e0;
            padding: 20px 0;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            transition: all 0.3s ease;
            z-index: 999;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 80px;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-menu a span {
            display: none;
        }

        .sidebar.collapsed .sidebar-menu a {
            justify-content: center;
            padding: 12px 0;
        }

        .sidebar.collapsed .sidebar-menu i {
            margin-right: 0;
            width: 100%;
            text-align: center;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 10px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.3s;
        }

        .sidebar-menu a span {
            margin-left: 10px;
        }

        .sidebar-menu a:hover {
            background-color: var(--sidebar-hover);
            border-left-color: var(--primary-color);
        }

        .sidebar-menu a.active {
            background-color: var(--sidebar-active);
            color: var(--sidebar-active-text);
            border-left-color: #2E7D32;
        }

        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .content-wrapper {
            flex: 1;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            margin: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            margin-left: 270px;
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .admin-content {
                flex-direction: column;
            }

            .sidebar-toggle {
                display: none;
            }
        }

        .sidebar.collapsed + .content-wrapper {
            margin-left: 80px;
        }

        .dashboard-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .dashboard-card {
            flex: 1;
            min-width: 250px;
            padding: 20px;
            border-radius: 10px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-customer {
            background-color: #4CAF50;
        }

        .card-lapangan {
            background-color: #4267B2;
        }

        .card-transaksi {
            background-color: #FFC107;
        }

        .card-icon {
            font-size: 2.5rem;
        }

        .card-info h3 {
            font-size: 2.5rem;
            margin: 0;
        }

        .card-info p {
            margin: 0;
            font-size: 1rem;
        }

        .more-info {
            text-align: right;
            margin-top: 10px;
        }

        .more-info a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        .footer-text {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: #666;
            font-size: 0.8rem;
            text-align: center;
            width: calc(100% - 40px);
        }

        .background-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .bg-shape {
            position: absolute;
            background-color: rgba(76, 175, 80, 0.1);
            border-radius: 15px;
        }

        .shape1 {
            width: 400px;
            height: 400px;
            right: -100px;
            top: 30%;
            transform: rotate(45deg);
        }

        .shape2 {
            width: 300px;
            height: 300px;
            left: 40%;
            bottom: -100px;
            transform: rotate(20deg);
        }

        .shape3 {
            width: 200px;
            height: 200px;
            left: 10%;
            top: 50%;
            transform: rotate(65deg);
        }

        .soccer-ball {
            position: fixed;
            right: 30px;
            bottom: 30px;
            width: 60px;
            height: 60px;
            z-index: 10;
        }

        .sidebar-menu a.logout-link {
            color: #dc3545;
        }

        .sidebar.collapsed .sidebar-menu a.logout-link i {
            color: #dc3545;
        }

        .sidebar-menu a.logout-link:hover {
            background-color: rgba(220, 53, 69, 0.1);
            border-left-color: #dc3545;
        }

        /* SweetAlert2 Custom Styling */
        .swal-custom-popup {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .swal-custom-title {
            font-weight: 600;
            color: #4CAF50;
        }

        .swal2-icon.swal2-question {
            border-color: #4CAF50;
            color: #4CAF50;
        }

        .swal2-actions {
            margin-top: 20px;
        }

        .swal2-confirm, .swal2-cancel {
            margin: 0 10px;
            padding: 10px 20px;
            border-radius: 8px;
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header class="main-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="mobile-menu-toggle me-3" id="mobileMenuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <button class="sidebar-toggle me-3" id="sidebarToggle">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="brand">FutZone</a>
                </div>
                <div>
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <span class="text-light">|</span>
                    <span class="ms-3 text-light">ADMIN FUTZONE</span>
                </div>
            </div>
        </div>
    </header>

    <div class="admin-content">
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i> <span>DASHBOARD</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.data.customer') }}"
                        class="{{ request()->routeIs('admin.data.customer') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> <span>DATA CUSTOMER</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.data.lapangan') }}"
                        class="{{ request()->routeIs('admin.data.lapangan') ? 'active' : '' }}">
                        <i class="fas fa-futbol"></i> <span>DATA LAPANGAN</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.transaksi') }}"
                        class="{{ request()->routeIs('admin.transaksi') ? 'active' : '' }}">
                        <i class="fas fa-exchange-alt"></i> <span>VALIDASI</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pelunasan.index') }}" class="{{ request()->routeIs('admin.pelunasan.*') ? 'active' : '' }}">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Pelunasan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.laporan') }}"
                        class="{{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> <span>LAPORAN</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('logout') }}" 
                       class="logout-link"
                       onclick="event.preventDefault(); showLogoutConfirmation();">
                        <i class="fas fa-sign-out-alt"></i> <span>LOGOUT</span>
                    </a>
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </aside>

        <main class="content-wrapper">
            @yield('content')
        </main>
    </div>

    <div class="background-shapes">
        <div class="bg-shape shape1"></div>
        <div class="bg-shape shape2"></div>
        <div class="bg-shape shape3"></div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin ingin keluar sebagai admin?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check for login success
            @if(session('login_success'))
                Swal.fire({
                    title: 'Login Berhasil!',
                    html: `
                        <div class="d-flex flex-column align-items-center">
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                            </div>
                            <p>Selamat datang, {{ Auth::user()->name }}!</p>
                            <small>Anda berhasil masuk ke {{ Auth::user()->role === 'admin' ? 'Dashboard Admin' : 'Akun Pengguna' }}</small>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonColor: '#4CAF50',
                    confirmButtonText: 'Lanjutkan',
                    customClass: {
                        popup: 'swal-custom-popup',
                        title: 'swal-custom-title'
                    },
                    didOpen: () => {
                        const popup = document.querySelector('.swal2-popup');
                        popup.style.borderRadius = '20px';
                        popup.style.padding = '20px';
                    }
                });
            @endif

            // Sidebar toggle functionality
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            // Mobile menu toggle
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });

            // Sidebar collapse toggle
            sidebarToggle.addEventListener('click', function() {
                const isCollapsed = sidebar.classList.toggle('collapsed');
                
                // Update toggle icon
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-chevron-left', !isCollapsed);
                icon.classList.toggle('fa-chevron-right', isCollapsed);

                // Save preference in localStorage
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });

            // Close sidebar when clicking outside (mobile)
            document.addEventListener('click', function(event) {
                if (!sidebar.contains(event.target) && 
                    !mobileMenuToggle.contains(event.target) && 
                    !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            });

            // Restore sidebar state from localStorage
            const savedCollapsedState = localStorage.getItem('sidebarCollapsed') === 'true';
            if (savedCollapsedState) {
                sidebar.classList.add('collapsed');
                const icon = sidebarToggle.querySelector('i');
                icon.classList.remove('fa-chevron-left');
                icon.classList.add('fa-chevron-right');
            }

            // Logout confirmation function
            window.showLogoutConfirmation = function() {
                Swal.fire({
                    title: 'Konfirmasi Logout',
                    html: 'Apakah Anda yakin ingin keluar dari admin? <br><small>Semua sesi aktif akan ditutup.</small>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4CAF50',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'swal-custom-popup',
                        title: 'swal-custom-title'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Add loading state to prevent multiple submissions
                        Swal.fire({
                            title: 'Logging Out...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit logout form
                        const logoutForm = document.getElementById('sidebar-logout-form');
                        if (logoutForm) {
                            logoutForm.submit();
                        }
                    }
                });
            };

            // Existing logout functionality
            function initLogoutHandlers() {
                const logoutLinks = document.querySelectorAll('.logout-link');
                
                logoutLinks.forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        showLogoutConfirmation();
                    });
                });
            }

            // Initialize logout handlers
            initLogoutHandlers();
        });
    </script>

    @stack('scripts')

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</body>

</html>