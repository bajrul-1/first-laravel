@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <a href="/companies" class="btn btn-sm btn-outline-secondary mb-3">&larr; Back to Directory</a>
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="fw-bold text-dark mb-1">Enterprise Console: {{ $company->name }}</h3>
                <p class="text-muted small">Central tracking hub for profiles, licensing, and credentials routing.</p>
            </div>
            <div>
                @if($company->status === 'active')
                    <span class="badge bg-light text-success border border-success px-3 py-2 text-uppercase fs-6">Active</span>
                @elseif($company->status === 'trial')
                    <span class="badge bg-light text-warning border border-warning px-3 py-2 text-uppercase fs-6">Trial</span>
                @else
                    <span class="badge bg-light text-danger border border-danger px-3 py-2 text-uppercase fs-6">{{ $company->status }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
@endif

<div class="row g-4">
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border-0 bg-white h-100">
            <div class="card-header bg-transparent pt-4 px-4 border-0">
                <h5 class="fw-bold text-secondary mb-0">🏢 Core Profile Information</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="mb-3 border-bottom pb-2">
                    <label class="text-muted small d-block">Business Legal Name</label>
                    <span class="fw-semibold text-dark fs-5">{{ $company->name }}</span>
                </div>
                <div class="mb-3 border-bottom pb-2">
                    <label class="text-muted small d-block">Registered Corporate Email</label>
                    <span class="fw-semibold text-dark">{{ $company->email ?? 'Not Provided' }}</span>
                </div>
                <div class="mb-3 border-bottom pb-2">
                    <label class="text-muted small d-block">Primary Contact Number</label>
                    <span class="fw-semibold text-dark">{{ $company->phone ?? 'Not Provided' }}</span>
                </div>
                <div class="mb-3 border-bottom pb-2">
                    <label class="text-muted small d-block">System Database ID</label>
                    <span class="font-monospace text-secondary">#{{ $company->id }}</span>
                </div>
                
                <div class="mt-4 pt-2">
                    <a href="/company/edit/{{ $company->id }}" class="btn btn-outline-primary btn-sm me-2">✏️ Modify Metadata</a>
                    <a href="/company/suspend/{{ $company->id }}" class="btn btn-outline-danger btn-sm" onclick="return confirm('Forcefully suspend this tenant gateway?')">🚫 Suspend Business</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border-0 bg-white h-100">
            <div class="card-header bg-transparent pt-4 px-4 border-0">
                <h5 class="fw-bold text-secondary mb-0">🔐 Root Authentication Provider</h5>
            </div>
            <div class="card-body px-4 pb-4 d-flex flex-column justify-content-between">
                
                @if($adminUser)
                    <div class="p-3 bg-light rounded border mb-3">
                        <p class="text-success fw-medium mb-2">✓ Active Root Credentials Provisioned</p>
                        
                        <div class="small mb-1"><strong>Login Username:</strong> <span class="text-dark">{{ $adminUser->email }}</span></div>
                        
                        <div class="small mb-2">
                            <strong>Temporary Password:</strong> 
                            <span class="badge bg-warning text-dark font-monospace fs-6 px-2 py-1">{{ $adminUser->temporary_password ?? '🔑 Changed by user' }}</span>
                        </div>
                        <p class="text-muted small mb-0 mt-2 italic">* Use these credentials to initiate first-time onboarding sync inside the client application domain.</p>
                    </div>
                    
                    <button class="btn btn-secondary w-100 py-2 fw-medium" disabled>Credentials Buffer Active</button>
                @else
                    <div class="text-center my-auto py-4">
                        <div class="fs-1 text-muted mb-2">🔑</div>
                        <p class="text-muted small px-3">No root admin credentials have been configured for this network domain yet. You must generate temporary system passes for initial deployment.</p>
                    </div>
                    
                    <form action="/company/generate-access/{{ $company->id }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 py-2 fw-medium shadow-sm">
                            ⚡ Generate Temporary Admin Credentials
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection