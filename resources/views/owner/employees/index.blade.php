@extends('layouts.owner')

@section('content')
<div class="container-fluid p-0">

    <!-- Page Header & Actions -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark m-0">Staff Directory & Roster</h4>
            <small class="text-muted">Manage company employees, permissions, and profile completion.</small>
        </div>
        <a href="{{ route('company.owner.employees.create', $company_slug) }}" class="btn btn-primary shadow-sm fw-semibold">
            <i class="fa-solid fa-user-plus me-1"></i> Onboard New Staff
        </a>
    </div>

    <!-- Success Flash Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Employee List Table Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle m-0" style="font-size: 0.9rem;">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="ps-4 text-muted fw-bold py-3" style="font-size: 0.75rem;">EMPLOYEE</th>
                            <th class="text-muted fw-bold py-3" style="font-size: 0.75rem;">DEPARTMENT & DESIGNATION</th>
                            <th class="text-muted fw-bold py-3" style="font-size: 0.75rem;">SYSTEM ROLE</th>
                            <th class="text-muted fw-bold py-3" style="font-size: 0.75rem;">PROFILE COMPLETION</th>
                            <th class="pe-4 text-end text-muted fw-bold py-3" style="font-size: 0.75rem;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                            @php
                                // প্রোফাইল % বের করার হেলপার মেথড কল
                                $completion = \App\Http\Controllers\Owner\EmployeeController::calculateProfileCompletion($emp);
                            @endphp
                            <tr>
                                <!-- Employee Name & Contact -->
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px; font-size: 0.85rem;">
                                            @if($emp->avatar)
                                                <img src="{{ asset('storage/' . $emp->avatar) }}" class="rounded-circle w-100 h-100" style="object-fit: cover;">
                                            @else
                                                {{ strtoupper(substr($emp->name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark m-0">{{ $emp->name }}</h6>
                                            <small class="text-muted" style="font-size: 0.75rem;">{{ $emp->email }} | {{ $emp->mobile }}</small>
                                        </div>
                                    </div>
                                </td>

                                <!-- Department & Designation -->
                                <td>
                                    <span class="fw-semibold text-dark d-block">{{ $emp->designation }}</span>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $emp->department }}</small>
                                </td>

                                <!-- Role -->
                                <td>
                                    @if($emp->role === 'manager')
                                        <span class="badge bg-warning-subtle text-warning border border-warning px-2.5 py-1 fw-semibold">
                                            <i class="fa-solid fa-user-shield me-1"></i> Manager
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1 fw-semibold">
                                            Employee
                                        </span>
                                    @endif
                                </td>

                                <!-- Profile Completion Progress Bar -->
                                <td style="width: 200px;">
                                    <div class="d-flex align-items-center justify-content-between mb-1 small">
                                        <span class="fw-semibold text-muted" style="font-size: 0.75rem;">Score</span>
                                        <span class="fw-bold text-dark" style="font-size: 0.75rem;">{{ $completion }}%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 6px;">
                                        <div class="progress-bar rounded-pill @if($completion < 60) bg-danger @elseif($completion < 90) bg-warning @else bg-success @endif" 
                                             role="progressbar" 
                                             style="width: {{ $completion }}%">
                                        </div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="pe-4 text-end">
                                    <span class="badge bg-success-subtle text-success border border-success px-2 py-1 rounded-pill small">
                                        Active
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-users-slash fa-2x mb-2 d-block text-secondary"></i>
                                    No staff onboarded yet. Click "Onboard New Staff" to add team members.
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