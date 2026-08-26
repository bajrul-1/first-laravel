<!-- 🧭 SLIM & COMPACT COLLAPSIBLE SIDEBAR -->
<div class="bg-dark text-white shadow d-flex flex-column" id="sidebar-wrapper">
    <!-- Branding Area -->
    <div class="p-3 border-bottom border-secondary text-center">
        <h6 class="fw-bold text-primary m-0">
            <i class="fa-solid fa-bread-slice"></i>
            <span class="sidebar-text ms-2">Bakery ERP</span>
        </h6>
    </div>

    <!-- Navigation Links -->
    <div class="list-group list-group-flush px-2 py-2" id="sidebarAccordion">

        <!-- ========================================== -->
        <!-- 🏠 Core Section -->
        <!-- ========================================== -->
        <div class="sidebar-section-title text-muted text-uppercase fw-bold px-3 mb-1 mt-1"
            style="font-size: 0.65rem; letter-spacing: 0.5px;">
            <span>Core</span>
        </div>

        <a href="{{ route('company.owner.dashboard', $company_slug) }}"
            class="list-group-item list-group-item-action bg-dark text-white border-0 rounded-2 py-2 px-3 mb-1 d-flex align-items-center @if (Route::is('company.owner.dashboard')) bg-primary active @endif"
            title="Dashboard">
            <i class="fa-solid fa-chart-pie fs-5" style="width: 25px;"></i>
            <span class="sidebar-text ms-2">Dashboard</span>
        </a>

        <!-- ========================================== -->
        <!-- 👥 Human Resources Section -->
        <!-- ========================================== -->
        <div class="sidebar-section-title text-muted text-uppercase fw-bold px-3 mb-1 mt-2"
            style="font-size: 0.65rem; letter-spacing: 0.5px;">
            <span>Human Resources</span>
        </div>

        <!-- Collapsible Parent Menu 1 (Staff) -->
        <a class="list-group-item list-group-item-action bg-dark text-white border-0 rounded-2 py-2 px-3 d-flex align-items-center justify-content-between mb-1 text-decoration-none dropdown-toggle @if (!Route::is('company.owner.employees.*')) collapsed @endif"
            data-bs-toggle="collapse" href="#staffMenu" role="button"
            aria-expanded="@if (Route::is('company.owner.employees.*')) true @else false @endif" aria-controls="staffMenu"
            title="Staff Manager">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-users-gear fs-5" style="width: 25px;"></i>
                <span class="sidebar-text ms-2">Staff Manager</span>
            </div>
        </a>

        <!-- Nested Child Links 1 -->
        <div class="collapse mb-1 @if (Route::is('company.owner.employees.*')) show @endif" id="staffMenu"
            data-bs-parent="#sidebarAccordion">
            <div class="d-flex flex-column gap-1 ps-2 py-1 rounded sub-menu-box">
                <a href="{{ route('company.owner.employees.index', $company_slug) }}"
                    class="list-group-item list-group-item-action bg-transparent border-0 py-1.5 px-3 small d-flex align-items-center @if (Route::is('company.owner.employees.index')) text-primary fw-bold @else text-white-50 @endif"
                    title="Roster List">
                    <i class="fa-solid fa-list" style="width: 25px; font-size: 0.9rem;"></i>
                    <span class="sidebar-text ms-2">Roster List</span>
                </a>
                <a href="{{ route('company.owner.employees.create', $company_slug) }}"
                    class="list-group-item list-group-item-action bg-transparent border-0 py-1.5 px-3 small d-flex align-items-center @if (Route::is('company.owner.employees.create')) text-primary fw-bold @else text-white-50 @endif"
                    title="Onboard New Staff">
                    <i class="fa-solid fa-user-plus" style="width: 25px; font-size: 0.9rem;"></i>
                    <span class="sidebar-text ms-2">Onboard New Staff</span>
                </a>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 📦 Logistics Section -->
        <!-- ========================================== -->
        <div class="sidebar-section-title text-muted text-uppercase fw-bold px-3 mb-1 mt-2"
            style="font-size: 0.65rem; letter-spacing: 0.5px;">
            <span>Logistics</span>
        </div>

        <!-- Collapsible Parent Menu 2 (Inventory Manager) -->
        <a class="list-group-item list-group-item-action bg-dark text-white border-0 rounded-2 py-2 px-3 d-flex align-items-center justify-content-between mb-1 text-decoration-none dropdown-toggle @if (!Route::is('company.owner.products.*')) collapsed @endif"
            data-bs-toggle="collapse" href="#inventoryMenu" role="button"
            aria-expanded="@if (Route::is('company.owner.products.*')) true @else false @endif" aria-controls="inventoryMenu"
            title="Inventory Manager">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-boxes-stacked fs-5" style="width: 25px;"></i>
                <span class="sidebar-text ms-2">Inventory Manager</span>
            </div>
        </a>

        <!-- Nested Child Links 2 -->
        <div class="collapse mb-1 @if (Route::is('company.owner.products.*')) show @endif" id="inventoryMenu"
            data-bs-parent="#sidebarAccordion">
            <div class="d-flex flex-column gap-1 ps-2 py-1 rounded sub-menu-box">
                <!-- Add New Product -->
                <a href="{{ route('company.owner.products.create', $company_slug) }}"
                    class="list-group-item list-group-item-action bg-transparent border-0 py-1.5 px-3 small d-flex align-items-center @if (Route::is('company.owner.products.create')) text-primary fw-bold @else text-white-50 @endif"
                    title="Add New Product">
                    <i class="fa-solid fa-plus" style="width: 25px; font-size: 0.9rem;"></i>
                    <span class="sidebar-text ms-2">Add New Product</span>
                </a>

                <!-- All Products Catalog -->
                <a href="{{ route('company.owner.products.index', $company_slug) }}"
                    class="list-group-item list-group-item-action bg-transparent border-0 py-1.5 px-3 small d-flex align-items-center @if (Route::is('company.owner.products.index')) text-primary fw-bold @else text-white-50 @endif"
                    title="All Products Catalog">
                    <i class="fa-solid fa-boxes-packing" style="width: 25px; font-size: 0.9rem;"></i>
                    <span class="sidebar-text ms-2">All Products Catalog</span>
                </a>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 🛒 Sales & Billing Section -->
        <!-- ========================================== -->
        <div class="sidebar-section-title text-muted text-uppercase fw-bold px-3 mb-1 mt-2"
            style="font-size: 0.65rem; letter-spacing: 0.5px;">
            <span>Billing & POS</span>
        </div>

        <a href="{{ route('company.owner.pos.index', $company_slug) }}"
            class="list-group-item list-group-item-action bg-dark text-white border-0 rounded-2 py-2 px-3 mb-1 d-flex align-items-center justify-content-between @if (Route::is('company.owner.pos.*')) bg-primary active @endif"
            title="POS Counter">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-cash-register fs-5" style="width: 25px;"></i>
                <span class="sidebar-text ms-2">POS & Billing</span>
            </div>
            <span class="badge bg-success small px-1.5 py-0.5" style="font-size: 0.65rem;">LIVE</span>
        </a>

    </div>
</div>
