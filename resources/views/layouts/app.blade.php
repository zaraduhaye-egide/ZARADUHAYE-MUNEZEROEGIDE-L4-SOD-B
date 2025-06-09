<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BERWA SHOP - @yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            padding: 20px;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @auth
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="text-white mb-4 px-3">
                    <h4>BERWA SHOP</h4>
                </div>
                <nav>
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('products.index') }}">Products</a>
                    <a href="{{ route('product-ins.index') }}">Stock In</a>
                    <a href="{{ route('product-outs.index') }}">Stock Out</a>
                    <a href="{{ route('reports') }}">Reports</a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-auto">
                        @csrf
                        <button type="submit" class="btn btn-link text-white w-100 text-start">Logout</button>
                    </form>
                </nav>
            </div>
            @endauth

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto main-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 