<!-- 👤 SLIM & CLEAN HEADER / TOPBAR -->
<nav class="navbar navbar-expand-lg navbar-white bg-white border-bottom px-3 py-2 shadow-sm">
    <div class="container-fluid p-0">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light btn-sm border shadow-sm" id="sidebarToggle" type="button">
                <i class="fa-solid fa-bars fs-5"></i>
            </button>
            <span class="fw-bold text-dark fs-6 text-uppercase tracking-wider">
                <i class="fa-solid fa-building text-primary me-1"></i>
                @php
                    $currentOwner = auth('owner')->user();
                @endphp

                @if ($currentOwner && $currentOwner->company)
                    <span class="fw-bold text-dark">
                        {{ $currentOwner->company->company_name }}
                    </span>
                @else
                    <span class="fw-bold text-muted">
                        Company
                    </span>
                @endif
            </span>
        </div>

        <div class="ms-auto">
            <div class="dropdown">
                <button
                    class="btn btn-link text-dark text-decoration-none dropdown-toggle fw-semibold d-flex align-items-center py-1 px-2 border rounded-pill bg-light"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.85rem;">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold"
                        style="width: 26px; height: 26px; font-size: 0.75rem;">
                        {{ strtoupper(substr(Auth::guard('owner')->user()->name, 0, 1)) }}
                    </div>
                    <span class="d-none d-sm-inline">{{ Auth::guard('owner')->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li>
                        <form action="{{ route('owner.logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger fw-semibold py-2">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
