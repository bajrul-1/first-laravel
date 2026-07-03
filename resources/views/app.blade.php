<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery ERP - SaaS Platform</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; color: #4e73df !important; }
    </style>
</head>
<body>

    <!-- মেইন হেডার নেভিগেশন বার -->
    <nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">🍞 Bakery ERP</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Expenses</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- মেইন কন্টেন্ট এরিয়া -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>