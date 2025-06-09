<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>{{ config('app.name', 'BerwaShop') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #000000 !important;
            padding: 1rem 0;
        }

        .navbar-brand {
            color: #ffffff !important;
            font-size: 1.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 0.5rem 1rem;
            border: 2px solid #ffffff;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            background-color: #ffffff;
            color: #000000 !important;
        }

        .nav-link {
            color: #3498db !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        .navbar-toggler {
            border-color: #ffffff;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            margin-bottom: 2rem;
        }

        .card-header {
            background: #000000;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1rem 1.5rem;
        }

        .footer {
            background-color: #000000;
            color: #ffffff;
            padding: 3rem 0;
            margin-top: auto;
        }

        .social-links a {
            color: #3498db;
            margin: 0 15px;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: #ffffff;
            transform: translateY(-3px);
        }

        .dropdown-menu {
            background-color: #000000;
            border: none;
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            color: #3498db;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #3498db;
            color: #ffffff;
        }

        .btn {
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-2px);
        }

        main {
            flex: 1;
            padding: 2rem 0;
        }

        .services-section {
            padding: 2rem 0;
        }

        .service-card {
            text-align: center;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
        }

        .service-icon {
            font-size: 3rem;
            color: #3498db;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                BerwaShop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">
                                <i class="fas fa-home"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/services') }}">
                                <i class="fas fa-store"></i> Services
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/about') }}">
                                <i class="fas fa-info-circle"></i> About Us
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/contact') }}">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index') }}">
                                <i class="fas fa-box"></i> Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('stock.in.index') }}">
                                <i class="fas fa-arrow-circle-down"></i> Stock In
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('product-outs.index') }}">
                                <i class="fas fa-arrow-circle-up"></i> Stock Out
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reports') }}">
                                <i class="fas fa-chart-bar"></i> Reports
                            </a>
                        </li>
                    @endguest
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> {{ __('Login') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> {{ __('Register') }}
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center text-md-start mb-4 mb-md-0">
                    <h3 class="mb-3">BerwaShop</h3>
                    <p class="mb-0">Your trusted fashion and lifestyle destination</p>
                    <p class="mb-0">Designed and developed by Egide Chaban</p>
                </div>
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <h4 class="mb-3">Our Services</h4>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none text-light">Clothing</a></li>
                        <li><a href="#" class="text-decoration-none text-light">Shoes</a></li>
                        <li><a href="#" class="text-decoration-none text-light">Accessories</a></li>
                        <li><a href="#" class="text-decoration-none text-light">Custom Orders</a></li>
                    </ul>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <h4 class="mb-3">Connect With Us</h4>
                    <div class="social-links mb-3">
                        <a href="https://instagram.com/the_real_motivator_12" target="_blank" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://facebook.com/egidechaban" target="_blank" title="Facebook">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://twitter.com/egidechaban" target="_blank" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://tiktok.com/@egidechaban" target="_blank" title="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="https://linkedin.com/in/egidechaban" target="_blank" title="LinkedIn">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                    <p class="mb-0">© {{ date('Y') }} BerwaShop. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html> 