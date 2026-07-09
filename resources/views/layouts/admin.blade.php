<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery ERP — Enterprise Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .sidebar { background-color: #ffffff; min-height: 100vh; border-right: 1px solid #e3e6f0; }
        .sidebar .nav-link { color: #6e707e; font-weight: 500; padding: 12px 20px; border-radius: 5px; margin: 4px 10px; display: block; text-decoration: none; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #f8f9fc; color: #4e73df !important; font-weight: 600; }
        .card { border: none; border-radius: 8px; }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            
            <!-- Sidebar Component Included -->
            <div class="col-md-2 px-0 sidebar d-none d-md-block">
                @include('panels.sidebar')
            </div>

            <!-- Content Workspace -->
            <div class="col-md-10 px-0 d-flex flex-column min-vh-100">
                
                <!-- Topbar Component Included -->
                @include('panels.topbar')

                <!-- Dynamic Content Workspace -->
                <div class="container-fluid px-4 mb-5 grow">
                    @yield('content')
                </div>

                <!-- Footer Component Included -->
                @include('panels.footer')
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>