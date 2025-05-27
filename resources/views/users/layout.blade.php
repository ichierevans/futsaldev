<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FutZone - Booking Lapangan Sepak Bola</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary-color: #28a745;
            --sidebar-bg: #f8f9fa;
            --sidebar-hover: #e9e9e9;
            --sidebar-active: #28a745;
            --sidebar-active-text: white;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            overflow-x: auto;
            width: 100%;
            max-width: 100%;
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
            width: calc(100% - 310px);
            max-width: calc(100% - 310px);
            overflow-x: auto;
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
                width: 100%;
                max-width: 100%;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .sidebar-toggle {
                display: none;
            }
        }

        .sidebar.collapsed + .content-wrapper {
            margin-left: 80px;
            width: calc(100% - 120px);
            max-width: calc(100% - 120px);
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

        .main-content {
            display: flex;
            min-height: calc(100vh - 60px);
        }
    </style>
    @yield('styles')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    @yield('header-scripts')
</head>
<body>
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
                            <small>Anda berhasil masuk ke Akun Pengguna</small>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonColor: '#28a745',
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
        });
    </script>
    <!-- Header -->
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
                    <a href="{{ route('user.profile') }}" class="brand text-white">FutZone</a>
                </div>
                <div>
                    <a href="{{ route('logout') }}" 
                       class="logout-btn me-3 logout-link"
                       onclick="event.preventDefault(); showLogoutConfirmation();">
                        Logout
                    </a>
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <span class="text-light">|</span>
                    <span class="ms-3 text-light">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <aside class="sidebar" id="sidebar">
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('user.profile') }}"
                       class="{{ request()->routeIs('user.profile') ? 'active' : '' }}">
                        <i class="fas fa-user"></i> <span>PROFILE</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.jadwal') }}"
                       class="{{ request()->routeIs('user.jadwal') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> <span>JADWAL LAPANGAN</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.booking.reguler') }}"
                       class="{{ request()->routeIs('user.booking.reguler') ? 'active' : '' }}">
                        <i class="fas fa-futbol"></i> <span>BOOKING</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.pesanan') }}"
                       class="{{ request()->routeIs('user.pesanan') ? 'active' : '' }}">
                        <i class="fas fa-list-alt"></i> <span>PESANAN / RIWAYAT</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.password') }}"
                       class="{{ request()->routeIs('user.password') ? 'active' : '' }}">
                        <i class="fas fa-lock"></i> <span>UBAH PASSWORD</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" 
                       class="logout-link"
                       onclick="event.preventDefault(); showLogoutConfirmation();">
                        <i class="fas fa-sign-out-alt"></i> <span>LOGOUT</span>
                    </a>
                </li>
            </ul>
        </aside>

        <main class="content-wrapper">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                    html: 'Apakah Anda yakin ingin keluar dari akun? <br><small>Semua sesi aktif akan ditutup.</small>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'swal-custom-popup',
                        title: 'swal-custom-title'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('sidebar-logout-form').submit();
                    }
                });
            };
        });
    </script>

    @yield('scripts')
</body>
</html>