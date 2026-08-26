@extends('layouts.owner')

@section('content')
<div class="container-fluid p-0">

    <!-- ========================================== -->
    <!-- 📊 1. METRIC COUNTERS (KPI CARDS) -->
    <!-- ========================================== -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Active Staff -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Active Roster</small>
                        <h3 class="fw-bold text-dark m-0">Active</h3>
                        <small class="text-primary fw-semibold" style="font-size: 0.8rem;">
                            <a href="{{ route('company.owner.employees.index', $company_slug) }}" class="text-decoration-none">
                                <i class="fa-solid fa-arrow-right me-1"></i> Staff Directory
                            </a>
                        </small>
                    </div>
                    <div class="bg-primary-subtle text-primary p-3 rounded-3">
                        <i class="fa-solid fa-users fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Production Orders -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Today's Batches</small>
                        <h3 class="fw-bold text-dark m-0">12 <span class="fs-6 text-muted fw-normal">Runs</span></h3>
                        <small class="text-success fw-semibold" style="font-size: 0.8rem;">
                            <i class="fa-solid fa-circle-dot me-1"></i> Kitchen Live
                        </small>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-3">
                        <i class="fa-solid fa-kitchen-set fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Inventory Alerts -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Stock Alerts</small>
                        <h3 class="fw-bold text-danger m-0">02 <span class="fs-6 text-muted fw-normal">Items</span></h3>
                        <small class="text-danger fw-semibold" style="font-size: 0.8rem;">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Low Flour & Sugar
                        </small>
                    </div>
                    <div class="bg-danger-subtle text-danger p-3 rounded-3">
                        <i class="fa-solid fa-boxes-stacked fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: System Invoices -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Billing Hub</small>
                        <h3 class="fw-bold text-dark m-0">Ready</h3>
                        <small class="text-muted fw-semibold" style="font-size: 0.8rem;">
                            <span class="badge bg-secondary-subtle text-secondary">Vite Engine</span>
                        </small>
                    </div>
                    <div class="bg-warning-subtle text-warning p-3 rounded-3">
                        <i class="fa-solid fa-file-invoice-dollar fa-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 📉 2. ANALYTICS & QUICK ACTIONS (SPLIT ROW) -->
    <!-- ========================================== -->
    <div class="row g-3 mb-4">
        <!-- Left: Production Load Visual Chart -->
        <div class="col-12 col-xl-7">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold m-0 text-dark"><i class="fa-solid fa-chart-line text-primary me-2"></i>Weekly Production Load</h6>
                    <span class="badge bg-light text-dark border small fw-normal">7 Days Filter</span>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1 small fw-semibold">
                            <span>Biscuits & Cookies</span>
                            <span class="text-primary">85% Capacity</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1 small fw-semibold">
                            <span>Breads & Buns</span>
                            <span class="text-success">60% Capacity</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: 60%"></div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between mb-1 small fw-semibold">
                            <span>Cakes & Pastries</span>
                            <span class="text-warning">40% Capacity</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px;">
                            <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: 40%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Quick Operations Gateway -->
        <div class="col-12 col-xl-5">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold m-0 text-dark"><i class="fa-solid fa-bolt text-warning me-2"></i>Quick Operations Gateway</h6>
                </div>
                <div class="card-body p-3 d-flex flex-column gap-2 justify-content-center">
                    <a href="{{ route('company.owner.employees.create', $company_slug) }}" class="btn btn-light border text-start py-2.5 px-3 rounded-3 d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fa-solid fa-user-plus text-primary me-2"></i>
                            <span class="fw-semibold text-dark small">Onboard New Employee</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('company.owner.employees.index', $company_slug) }}" class="btn btn-light border text-start py-2.5 px-3 rounded-3 d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fa-solid fa-clipboard-user text-success me-2"></i>
                            <span class="fw-semibold text-dark small">Staff Roster & Permissions</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-muted small"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 📋 3. RECENT SYSTEM EVENTS (LIVE LOG) -->
    <!-- ========================================== -->
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold m-0 text-dark"><i class="fa-solid fa-clock-rotate-left text-secondary me-2"></i>Recent System Activity Log</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0" style="font-size: 0.9rem;">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="ps-4 text-muted fw-bold py-3" style="font-size: 0.75rem;">Event Details</th>
                                    <th class="text-muted fw-bold py-3" style="font-size: 0.75rem;">Module</th>
                                    <th class="text-muted fw-bold py-3" style="font-size: 0.75rem;">Operator</th>
                                    <th class="pe-4 text-end text-muted fw-bold py-3" style="font-size: 0.75rem;">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4 py-3 fw-semibold text-dark">
                                        <i class="fa-solid fa-circle-dot text-success me-2" style="font-size: 0.6 rails;"></i>
                                        Owner session successfully authenticated
                                    </td>
                                    <td><span class="badge bg-dark-subtle text-dark border">Auth Guard</span></td>
                                    <td>{{ $owner->name }}</td>
                                    <td class="pe-4 text-end text-muted small">Just Now</td>
                                </tr>
                                <tr>
                                    <td class="ps-4 py-3 text-muted">
                                        <i class="fa-solid fa-circle-dot text-muted me-2" style="font-size: 0.6 rem;"></i>
                                        Database migration session table rebuilt
                                    </td>
                                    <td><span class="badge bg-secondary-subtle text-secondary border">Core Schema</span></td>
                                    <td>System CLI</td>
                                    <td class="pe-4 text-end text-muted small">5 mins ago</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection