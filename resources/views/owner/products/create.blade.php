@extends('layouts.owner')

@section('content')
    <div class="container-fluid p-0">

        <!-- 🟢 SUCCESS & ERROR FLASH ALERTS -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center"
                role="alert">
                <i class="fa-solid fa-circle-check fs-5 me-2"></i>
                <div>
                    <strong>Success!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center"
                role="alert">
                <i class="fa-solid fa-triangle-exclamation fs-5 me-2"></i>
                <div>
                    <strong>Error!</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold text-dark m-0">Add New Product</h4>
                <p class="text-muted small mb-0">Create new items with flat or salesman/retailer/customer pricing rates.</p>
            </div>
            <a href="{{ route('company.owner.products.index', $company_slug) }}" class="btn btn-light border fw-semibold">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Catalog
            </a>
        </div>

        <!-- Product Create Form Component -->
        <x-owner.product-create-form :company_slug="$company_slug" />
    </div>
@endsection
