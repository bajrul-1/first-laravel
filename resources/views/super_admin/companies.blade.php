@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Page Header Area -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1">Company Directory</h3>
            <p class="text-muted small mb-0">Overview of all registered tenants, system states, and access management profiles.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="/company/create" class="btn btn-primary px-4 py-2 fw-medium shadow-sm rounded-3">
                Register New Company
            </a>
        </div>
    </div>

    <!-- Alert Messages Integration -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            ✓ {{ session('success') }}
        </div>
    @endif

    <!-- Centralized Data Table Card -->
    <div class="card shadow-sm border-0 rounded-3 bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100">
                    <!-- Head Format -->
                    <thead class="table-light border-bottom">
                        <tr class="text-secondary small text-uppercase fw-bold">
                            <th class="ps-4 py-3" style="width: 10%;">ID</th>
                            <th class="py-3" style="width: 30%;">Company Details</th>
                            <th class="py-3" style="width: 30%;">Corporate Email</th>
                            <th class="py-3" style="width: 15%;">Contact Line</th>
                            <th class="py-3" style="width: 10%;">Status</th>
                            <th class="pe-4 py-3 text-end" style="width: 5%;">Action</th>
                        </tr>
                    </thead>
                    
                    <!-- Table Body -->
                    <tbody class="border-top-0">
                        @forelse($companies as $company)
                            <tr class="align-middle">
                                <!-- ID Column -->
                                <td class="ps-4 py-3">
                                    <span class="badge bg-light text-secondary font-monospace border px-2 py-1">#{{ $company->id }}</span>
                                </td>
                                
                                <!-- Name Column -->
                                <td class="py-3">
                                    <div class="fw-semibold text-dark fs-6">{{ $company->name }}</div>
                                </td>
                                
                                <!-- Email Column -->
                                <td class="py-3 text-secondary">
                                    {{ $company->email ?? '—' }}
                                </td>
                                
                                <!-- Phone Column -->
                                <td class="py-3 text-secondary font-monospace small">
                                    {{ $company->phone ?? '—' }}
                                </td>
                                
                                <!-- Status Column -->
                                <td class="py-3">
                                    @if($company->status === 'active')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 text-uppercase fw-bold" style="font-size: 0.7rem;">Active</span>
                                    @elseif($company->status === 'trial')
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2.5 py-1 text-uppercase fw-bold" style="font-size: 0.7rem;">Trial</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 text-uppercase fw-bold" style="font-size: 0.7rem;">{{ $company->status }}</span>
                                    @endif
                                </td>
                                
                                <!-- Action Component (সরাসরি ডিরেক্ট বাটন, কোনো ড্রপডাউন ঝামেলা নেই) -->
                                <td class="pe-4 py-3 text-end">
                                    <a href="/company/manage/{{ $company->id }}" class="btn btn-sm btn-outline-primary fw-medium px-3 rounded-2">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <!-- Empty Catch Block -->
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    📂 No Business Entries Registered
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection