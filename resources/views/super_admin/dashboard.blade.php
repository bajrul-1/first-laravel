@extends('layouts.admin')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <strong>Success:</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h3 class="fw-bold text-dark mb-1">Super Admin Overview</h3>
        <p class="text-muted small">Global enterprise metrics.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
    <div class="card shadow-sm bg-white p-4">
        <div class="text-muted small text-uppercase fw-semibold mb-1">Total Registered Businesses</div>
        <div class="h1 fw-bold text-dark mb-3">{{ $totalCompanies }}</div>
        
        <hr class="my-2 text-muted opacity-25">
        
        <div class="row pt-2">
            <div class="col-6 border-end">
                <span class="text-muted small d-block">Active Status</span>
                <span class="fw-bold text-success h5 mb-0">{{ $activeCompanies }}</span>
            </div>
            <div class="col-6 ps-3">
                <span class="text-muted small d-block">Trial Mode</span>
                <span class="fw-bold text-warning h5 mb-0">{{ $trialCompanies }}</span>
            </div>
        </div>
    </div>
</div>
    <!-- Platform Income Card -->
<div class="col-12 col-md-4">
    <div class="card shadow-sm bg-white p-4">
        <!-- Main Top Metric: Total Platform Income -->
        <div class="text-muted small text-uppercase fw-semibold mb-1">Total Platform ARR Income</div>
        <div class="h1 fw-bold text-dark mb-3">₹0.00</div>
        
        <!-- Divider to separate main metric and sub-metrics -->
        <hr class="my-2 text-muted opacity-25">
        
        <!-- Sub-Metrics Grid: Monthly vs Annualized Recurring -->
        <div class="row pt-2">
            <div class="col-6 border-end">
                <span class="text-muted small d-block">This Month MRR</span>
                <span class="fw-bold text-dark h5 mb-0">₹0.00</span>
            </div>
            <div class="col-6 ps-3">
                <span class="text-muted small d-block">Pending Invoices</span>
                <span class="fw-bold text-danger h5 mb-0">₹0.00</span>
            </div>
        </div>
    </div>
</div>

<!-- System Expenses Card -->
<div class="col-12 col-md-4">
    <div class="card shadow-sm bg-white p-4">
        <!-- Main Top Metric: Total System Expenses -->
        <div class="text-muted small text-uppercase fw-semibold mb-1">Total System Expenses</div>
        <div class="h1 fw-bold text-dark mb-3">₹0.00</div>
        
        <!-- Divider to separate main metric and sub-metrics -->
        <hr class="my-2 text-muted opacity-25">
        
        <!-- Sub-Metrics Grid: Server Infrastructure vs Marketing/Other Costs -->
        <div class="row pt-2">
            <div class="col-6 border-end">
                <span class="text-muted small d-block">Cloud Infrastructure</span>
                <span class="fw-bold text-dark h5 mb-0">₹0.00</span>
            </div>
            <div class="col-6 ps-3">
                <span class="text-muted small d-block">SMS & API Gateway</span>
                <span class="fw-bold text-dark h5 mb-0">₹0.00</span>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm bg-white">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3 text-dark">Registered Businesses</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Company Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $company->name }}</td>
                            <td>{{ $company->email ?? 'N/A' }}</td>
                            <td>{{ $company->phone ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-light text-success border border-success px-2 py-1 text-uppercase small">
                                    {{ $company->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="/company/manage/{{ $company->id }}" class="btn btn-sm btn-outline-primary px-3">Manage & Provision</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No business profiles registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection