<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BERWA SHOP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .nav-link {
            font-size: 1.1rem;
            margin: 0 5px;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #f8f9fa !important;
        }
        #about, #services {
            background: rgba(255, 255, 255, 0.1);
        }
        .card {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .welcome-section {
            padding: 80px 0;
            background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8');
            background-size: cover;
            background-position: center;
            position: relative;
            min-height: 100vh;
        }
        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
        }
        .card-header {
            background: #2c3e50;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        .btn-custom {
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 30px;
            margin: 10px;
            min-width: 200px;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .shop-features {
            margin-top: 2rem;
        }
        .feature-item {
            padding: 1rem;
            text-align: center;
            color: #fff;
        }
        .feature-item i {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2c3e50;">
        <div class="container">
            <a class="navbar-brand" href="#">BERWA SHOP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="welcome-section">
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center">
                            <h1 class="display-4 mb-0">Welcome to BERWA SHOP</h1>
                        </div>
                        <div class="card-body text-center py-5">
                            <h2 class="mb-4">Shoes and Clothes Management System</h2>
                            <p class="lead mb-5">
                                Efficient inventory management system for tracking stock-in and stock-out, 
                                generating reports, and maintaining product records.
                            </p>
                            <div class="d-flex justify-content-center flex-wrap">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-custom m-2">
                                    <i class="fas fa-sign-in-alt me-2"></i> Login
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-success btn-custom m-2">
                                    <i class="fas fa-user-plus me-2"></i> Register as Shopkeeper
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row shop-features">
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="fas fa-box"></i>
                                <h4>Inventory Management</h4>
                                <p>Track your stock efficiently</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="fas fa-chart-line"></i>
                                <h4>Reports Generation</h4>
                                <p>Get detailed insights</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-item">
                                <i class="fas fa-shield-alt"></i>
                                <h4>Secure Access</h4>
                                <p>Protected data management</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <!-- About Us Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="text-center">About Us</h2>
                        </div>
                        <div class="card-body">
                            <p class="lead">
                                BERWA SHOP is your premier destination for quality shoes and clothes. We take pride in offering 
                                a carefully curated selection of fashion items that combine style, comfort, and affordability.
                            </p>
                            <p>
                                Our mission is to provide our customers with the best shopping experience through:
                            </p>
                            <ul>
                                <li>High-quality products from trusted brands</li>
                                <li>Excellent customer service</li>
                                <li>Competitive pricing</li>
                                <li>Efficient inventory management</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="text-center">Our Services</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="text-center">
                                        <i class="fas fa-tshirt fa-3x mb-3 text-primary"></i>
                                        <h4>Fashion Retail</h4>
                                        <p>Wide selection of trendy clothes and shoes for all occasions</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="text-center">
                                        <i class="fas fa-shipping-fast fa-3x mb-3 text-primary"></i>
                                        <h4>Fast Delivery</h4>
                                        <p>Quick and reliable shipping services</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="text-center">
                                        <i class="fas fa-exchange-alt fa-3x mb-3 text-primary"></i>
                                        <h4>Easy Returns</h4>
                                        <p>Hassle-free return and exchange policy</p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="text-center">
                                        <i class="fas fa-headset fa-3x mb-3 text-primary"></i>
                                        <h4>Customer Support</h4>
                                        <p>24/7 dedicated customer service</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 