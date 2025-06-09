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
        .card {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 