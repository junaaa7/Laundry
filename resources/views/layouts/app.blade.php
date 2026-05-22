<!DOCTYPE html>
<html lang="id" data-bs-theme="{{ auth()->user()->theme ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laundry App')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    @stack('styles')
    
    <style>
        body {
            min-height: 100vh;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 24px;
            margin-right: 8px;
        }
        
        .card-stats {
            transition: transform 0.3s;
            cursor: pointer;
        }
        
        .card-stats:hover {
            transform: translateY(-5px);
        }
        
        /* Dark Mode Sidebar */
        [data-bs-theme="dark"] .sidebar {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }
        
        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(50%, -50%);
        }
        
        /* Main Content Padding */
        main {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            @auth
            <div class="col-auto">
                <div class="sidebar p-3" style="width: 280px;">
                    <div class="text-center mb-4">
                        <h4 class="text-white">Laundry App</h4>
                        <small class="text-white-50">{{ ucfirst(auth()->user()->role) }}</small>
                    </div>
                    <hr class="text-white-50">
                    <nav class="nav flex-column">
                        @if(auth()->user()->role == 'admin')
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users"></i> Kelola User
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">
                                <i class="fas fa-exchange-alt"></i> Data Transaksi
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.finance') ? 'active' : '' }}" href="{{ route('admin.finance') }}">
                                <i class="fas fa-chart-line"></i> Data Finance
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.prices.*') ? 'active' : '' }}" href="{{ route('admin.prices.index') }}">
                                <i class="fas fa-tags"></i> Data Harga
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.targets.*') ? 'active' : '' }}" href="{{ route('admin.targets.index') }}">
                                <i class="fas fa-bullseye"></i> Atur Target
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.banks.*') ? 'active' : '' }}" href="{{ route('admin.banks.index') }}">
                                <i class="fas fa-university"></i> Data Bank
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.notifications.settings') ? 'active' : '' }}" href="{{ route('admin.notifications.settings') }}">
                                <i class="fas fa-bell"></i> Setting Notifikasi
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.documentation') ? 'active' : '' }}" href="{{ route('admin.documentation') }}">
                                <i class="fas fa-book"></i> Dokumentasi
                            </a>
                        @elseif(auth()->user()->role == 'karyawan')
                            <a class="nav-link {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}" href="{{ route('karyawan.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a class="nav-link {{ request()->routeIs('karyawan.orders') ? 'active' : '' }}" href="{{ route('karyawan.orders') }}">
                                <i class="fas fa-clipboard-list"></i> Data Order Masuk
                            </a>
                            <a class="nav-link {{ request()->routeIs('karyawan.customers.*') ? 'active' : '' }}" href="{{ route('karyawan.customers.index') }}">
                                <i class="fas fa-user-friends"></i> Data Customer
                            </a>
                            <a class="nav-link {{ request()->routeIs('karyawan.transactions.create') ? 'active' : '' }}" href="{{ route('karyawan.transactions.create') }}">
                                <i class="fas fa-plus-circle"></i> Tambah Transaksi
                            </a>
                            <a class="nav-link {{ request()->routeIs('karyawan.reports') ? 'active' : '' }}" href="{{ route('karyawan.reports') }}">
                                <i class="fas fa-file-alt"></i> Laporan
                            </a>
                        @else
                            <a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a class="nav-link {{ request()->routeIs('customer.payments') ? 'active' : '' }}" href="{{ route('customer.payments') }}">
                                <i class="fas fa-credit-card"></i> Pembayaran
                            </a>
                            <a class="nav-link {{ request()->routeIs('customer.notifications') ? 'active' : '' }}" href="{{ route('customer.notifications') }}">
                                <i class="fas fa-bell"></i> Notifikasi
                                @if(auth()->user()->unreadNotifications()->count() > 0)
                                    <span class="badge bg-danger notification-badge">{{ auth()->user()->unreadNotifications()->count() }}</span>
                                @endif
                            </a>
                        @endif
                        
                        <hr class="text-white-50">
                        <a class="nav-link" href="#" onclick="toggleTheme()">
                            <i class="fas fa-moon"></i> Ubah Tema
                        </a>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a class="nav-link" href="#" onclick="document.getElementById('logout-form').submit()">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </form>
                    </nav>
                </div>
            </div>
            @endauth
            
            <!-- Main Content -->
            <div class="col">
                <main class="p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            fetch('{{ route("theme.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ theme: newTheme })
            }).catch(error => console.log('Theme update error:', error));
        }
        
        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        
        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                setTimeout(() => bsAlert.close(), 5000);
            });
        }, 1000);
    </script>
    @stack('scripts')
</body>
</html>