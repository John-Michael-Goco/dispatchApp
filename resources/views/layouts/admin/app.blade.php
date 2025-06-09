<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <style>
        /* Base styles */
        body {
            background: #f8f9fa;
        }

        /* Sidebar styles */
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #fff;
            border-right: 1px solid #e9ecef;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.5rem 0.75rem 1.5rem 0.75rem;
            overflow-y: auto;
        }

        /* Navigation link styles */
        .sidebar .nav-link {
            color: #2c3e50;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        /* Active and hover states for nav links */
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: #e9ecef;
            color: #3498db;
            transform: translateX(5px);
        }

        /* Logout button positioning */
        .sidebar .logout {
            margin-top: auto;
        }

        /* Sidebar header styling */
        .sidebar .sidebar-header {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2rem;
            color: #3498db;
        }

        /* Main content area styles */
        .main-content {
            margin-left: 250px; /* Default for larger screens */
            padding: 2rem 1rem 1rem 1rem;
        }

        /* Mobile responsive adjustments */
        @media (max-width: 991.98px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .offcanvas-sidebar {
                width: 280px;
                background: #fff;
                border-right: 1px solid #e9ecef;
                padding: 0;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }

            .offcanvas-header {
                padding: 1.5rem;
                border-bottom: 1px solid #e9ecef;
                background: #f8f9fa;
            }

            .offcanvas-title {
                font-size: 1.5rem;
                font-weight: 600;
                color: #2c3e50;
            }

            .offcanvas-body {
                padding: 1.5rem;
                overflow-y: auto;
            }

            .mobile-menu-btn {
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1030;
                background: #fff;
                border: none;
                border-radius: 8px;
                padding: 0.5rem 1rem;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .mobile-menu-btn:hover {
                background: #f8f9fa;
                transform: translateY(-2px);
            }

            .mobile-menu-btn i {
                font-size: 1.2rem;
                color: #2c3e50;
            }

            .offcanvas-sidebar .nav-link {
                padding: 0.8rem 1rem;
                margin: 0.2rem 0;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .offcanvas-sidebar .nav-link:hover {
                background: #f8f9fa;
                transform: translateX(5px);
            }

            .offcanvas-sidebar .nav-link.active {
                background: #e3f2fd;
                color: #1976d2;
            }

            .offcanvas-sidebar .nav-link i {
                width: 24px;
                text-align: center;
                margin-right: 10px;
                font-size: 1.1rem;
            }

            .offcanvas-sidebar .dropdown-menu {
                border: none;
                background: #f8f9fa;
                margin: 0.5rem 0;
                padding: 0.5rem;
                border-radius: 8px;
            }

            .offcanvas-sidebar .dropdown-item {
                padding: 0.7rem 1rem;
                border-radius: 6px;
                margin: 0.2rem 0;
            }

            .offcanvas-sidebar .dropdown-item:hover {
                background: #e9ecef;
                transform: translateX(5px);
            }

            .offcanvas-sidebar .logout {
                padding: 1.5rem;
                border-top: 1px solid #e9ecef;
                background: #f8f9fa;
            }

            .offcanvas-sidebar .logout button {
                padding: 0.8rem;
                font-weight: 500;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .offcanvas-sidebar .logout button:hover {
                transform: translateY(-2px);
                box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
            }
        }

        /* Custom dropdown styles */
        .sidebar .dropdown-menu,
        .offcanvas-sidebar .dropdown-menu { /* Apply to both sidebar and offcanvas */
            background: #fff;
            border: none;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 0.5rem;
            padding: 0.5rem;
            width: 100%;
            position: relative !important;
            transform: none !important;
            transition: all 0.3s ease;
        }

        .sidebar .dropdown-item,
        .offcanvas-sidebar .dropdown-item { /* Apply to both sidebar and offcanvas */
            color: #2c3e50;
            font-weight: 500;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .sidebar .dropdown-item:hover,
        .offcanvas-sidebar .dropdown-item:hover { /* Apply to both sidebar and offcanvas */
            background: #e9ecef;
            color: #3498db;
            transform: translateX(5px);
        }

        .sidebar .dropdown-toggle::after,
        .offcanvas-sidebar .dropdown-toggle::after { /* Apply to both sidebar and offcanvas */
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .sidebar .nav-item.dropdown,
        .offcanvas-sidebar .nav-item.dropdown { /* Apply to both sidebar and offcanvas */
            margin-bottom: 0.5rem;
        }

        .sidebar .nav-item.dropdown.show .dropdown-toggle::after,
        .offcanvas-sidebar .nav-item.dropdown.show .dropdown-toggle::after { /* Apply to both sidebar and offcanvas */
            transform: rotate(180deg);
        }

        /* Dropdown animation */
        .sidebar .dropdown-menu.show,
        .offcanvas-sidebar .dropdown-menu.show { /* Apply to both sidebar and offcanvas */
            display: block;
            animation: dropdownFade 0.3s ease;
        }

        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Nested dropdown styles */
        .sidebar .dropdown-menu .dropdown-menu,
        .offcanvas-sidebar .dropdown-menu .dropdown-menu { /* Apply to both sidebar and offcanvas */
            margin-left: 1rem;
            margin-top: 0.5rem;
            border-left: 2px solid #e9ecef;
        }

        /* Section headers in sidebar */
        .sidebar-section {
            margin-bottom: 1.5rem;
        }

        .sidebar-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            padding: 0.5rem 1rem;
            margin-bottom: 0.5rem;
        }

        /* Scrollbar styling */
        .sidebar::-webkit-scrollbar,
        .offcanvas-sidebar::-webkit-scrollbar { /* Apply to both sidebar and offcanvas */
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track,
        .offcanvas-sidebar::-webkit-scrollbar-track { /* Apply to both sidebar and offcanvas */
            background: #f1f1f1;
        }

        .sidebar::-webkit-scrollbar-thumb,
        .offcanvas-sidebar::-webkit-scrollbar-thumb { /* Apply to both sidebar and offcanvas */
            background: #c1c1c1;
            border-radius: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover,
        .offcanvas-sidebar::-webkit-scrollbar-thumb:hover { /* Apply to both sidebar and offcanvas */
            background: #a8a8a8;
        }
    </style>

</head>

<body>
    <div id="app">
        <div class="offcanvas offcanvas-start offcanvas-sidebar d-lg-none" tabindex="-1" id="offcanvasSidebar"
            aria-labelledby="offcanvasSidebarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title sidebar-header" id="offcanvasSidebarLabel">Dispatch</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column justify-content-between">
                <div>
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->is('home') ? 'active' : '' }}" href="{{ url('/home') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard</a>
                        <a class="nav-link {{ request()->is('services*') ? 'active' : '' }}" href="">
                            <i class="bi bi-gear"></i> Services</a>
                        <a class="nav-link {{ request()->is('branches*') ? 'active' : '' }}" href="">
                            <i class="bi bi-diagram-3"></i> Branches</a>
                        <a class="nav-link {{ request()->is('incidents*') ? 'active' : '' }}" href="">
                            <i class="bi bi-exclamation-triangle"></i> Incidents</a>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-truck"></i> Mobile Service
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href=""><i class="bi bi-geo-alt"></i> Units</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href=""><i class="bi bi-send"></i> Dispatch</a>
                                </li>
                            </ul>
                        </div>
                        <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="">
                            <i class="bi bi-people"></i> Users</a>
                    </nav>
                </div>
                <form class="logout" id="logout-form-offcanvas" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-box-arrow-right"></i>
                        Logout</button>
                </form>
            </div>
        </div>

        <div class="sidebar d-none d-lg-flex flex-column">
            <div>
                <div class="sidebar-header">Dispatch</div>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->is('home') ? 'active' : '' }}" href="{{ url('/home') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a class="nav-link {{ request()->is('services*') ? 'active' : '' }}" href="">
                        <i class="bi bi-gear"></i> Services</a>
                    <a class="nav-link {{ request()->is('branches*') ? 'active' : '' }}" href="">
                        <i class="bi bi-diagram-3"></i> Branches</a>
                    <a class="nav-link {{ request()->is('incidents*') ? 'active' : '' }}" href="">
                        <i class="bi bi-exclamation-triangle"></i> Incidents</a>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-truck"></i> Mobile Service
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href=""><i class="bi bi-geo-alt"></i> Units</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href=""><i class="bi bi-send"></i> Dispatch</a>
                            </li>
                        </ul>
                    </div>
                    <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="">
                        <i class="bi bi-people"></i> Users</a>
                </nav>
            </div>
            <form class="logout" id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-box-arrow-right"></i>
                    Logout</button>
            </form>
        </div>
        <div class="main-content">
            <button class="mobile-menu-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" aria-controls="offcanvasSidebar">
                <i class="bi bi-list"></i>
            </button>
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>