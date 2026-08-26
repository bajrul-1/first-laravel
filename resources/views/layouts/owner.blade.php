<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - Bakery ERP</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    
    <style>
        #sidebar-wrapper {
            transition: all 0.25s ease-in-out;
            width: 250px;
            min-width: 250px;
            overflow-x: hidden;
            overflow-y: auto;
            white-space: nowrap;
        }
        body.sidebar-toggled #sidebar-wrapper {
            width: 70px;
            min-width: 70px;
        }
        body.sidebar-toggled .sidebar-text,
        body.sidebar-toggled .sidebar-section-title span,
        body.sidebar-toggled .dropdown-toggle::after {
            display: none !important;
        }
        body.sidebar-toggled .sidebar-section-title {
            text-align: center;
            padding: 0.5rem 0 !important;
        }
        body.sidebar-toggled .sidebar-section-title i {
            display: inline-block !important;
        }
        .sub-menu-box {
            background-color: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="bg-light">

    <div class="d-flex" id="wrapper" style="min-height: 100vh;">
        
        <!-- 🧭 মডুলার সাইডবার ইম্পোর্ট -->
        @include('layouts.partials.sidebar')

        <!-- Page Content Wrapper -->
        <div class="flex-grow-1 d-flex flex-column" style="min-width: 0;">
            
            <!-- 👤 মডুলার হেডার ইম্পোর্ট -->
            @include('layouts.partials.header')

            <!-- 📝 মেইন ডাইনামিক কন্টেন্ট এরিয়া -->
            <main class="p-4 flex-grow-1">
                @yield('content')
            </main>

            <!-- 📜 মডুলার ফুটার ইম্পোর্ট -->
            @include('layouts.partials.footer')
            
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Alpine.js CDN for Dynamic UI Interactivity -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- AUTOMATIC & ACCORDION EXPAND SCRIPT -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarToggle = document.getElementById("sidebarToggle");
            if (localStorage.getItem('sidebar-toggle') === 'true') {
                document.body.classList.add('sidebar-toggled');
            }
            sidebarToggle.addEventListener("click", function (e) {
                e.preventDefault();
                document.body.classList.toggle("sidebar-toggled");
                localStorage.setItem('sidebar-toggle', document.body.classList.contains('sidebar-toggled'));
            });

            const activeLink = document.querySelector('.collapse .list-group-item.text-primary');
            if (activeLink) {
                const parentCollapse = activeLink.closest('.collapse');
                if (parentCollapse) {
                    const bsCollapse = new bootstrap.Collapse(parentCollapse, { toggle: false });
                    bsCollapse.show();
                    
                    const triggerButton = document.querySelector(`a[href="#${parentCollapse.id}"]`);
                    if (triggerButton) {
                        triggerButton.classList.remove('collapsed');
                        triggerButton.setAttribute('aria-expanded', 'true');
                    }
                }
            }
        });
    </script>
</body>
</html>