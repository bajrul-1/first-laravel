@extends('layouts.owner')

@section('content')
    <div class="container-fluid p-0">

        <!-- Delete & Action Success Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3 p-3 bg-success bg-opacity-10 text-success border-start border-success border-4"
                role="alert">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                    <div>
                        <strong class="d-block text-dark fw-bold mb-0">Success Response</strong>
                        <span class="small text-secondary">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header Section -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark m-0">All Products Catalog</h4>
                <p class="text-muted small mb-0">Manage all bakery products, stock levels, and price details.</p>
            </div>
            <a href="{{ route('company.owner.products.create', $company_slug) }}"
                class="btn btn-primary fw-semibold shadow-sm">
                <i class="fa-solid fa-plus me-1"></i> Add New Product
            </a>
        </div>

        <!-- FILTER TABS & SEARCH SECTION -->
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-body p-3">

                <!-- TABS -->
                <ul class="nav nav-pills mb-3 border-bottom pb-2 gap-2">
                    <li class="nav-item">
                        <a class="nav-link py-1.5 px-3 fw-semibold small {{ $activeTab === 'all' ? 'active bg-primary' : 'text-secondary bg-light' }}"
                            href="{{ route('company.owner.products.index', [$company_slug, 'tab' => 'all', 'search' => request('search'), 'type' => request('type')]) }}">
                            <i class="fa-solid fa-boxes-stacked me-1"></i> All Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1.5 px-3 fw-semibold small {{ $activeTab === 'low_stock' ? 'active bg-warning text-dark' : 'text-secondary bg-light' }}"
                            href="{{ route('company.owner.products.index', [$company_slug, 'tab' => 'low_stock', 'search' => request('search'), 'type' => request('type')]) }}">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Low Stock
                            @if ($lowStockCount > 0)
                                <span class="badge bg-danger ms-1">{{ $lowStockCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link py-1.5 px-3 fw-semibold small {{ $activeTab === 'expiring' ? 'active bg-danger' : 'text-secondary bg-light' }}"
                            href="{{ route('company.owner.products.index', [$company_slug, 'tab' => 'expiring', 'search' => request('search'), 'type' => request('type')]) }}">
                            <i class="fa-solid fa-calendar-xmark me-1"></i> Expired / Expiring Soon
                            @if ($expiringCount > 0)
                                <span class="badge bg-white text-danger ms-1">{{ $expiringCount }}</span>
                            @endif
                        </a>
                    </li>
                </ul>

                <!-- SEARCH & SOURCE FILTER FORM -->
                <form action="{{ route('company.owner.products.index', $company_slug) }}" method="GET"
                    class="row g-2 align-items-center">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">

                    <!-- Search Box -->
                    <div class="col-12 col-md-6 col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 bg-light"
                                placeholder="Search by Product Name or Barcode..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Source Filter Dropdown -->
                    <div class="col-12 col-md-4 col-lg-3">
                        <select name="type" class="form-select bg-light" onchange="this.form.submit()">
                            <option value="">All Sources (Bakery Own & Purchased)</option>
                            <option value="own_production" {{ request('type') == 'own_production' ? 'selected' : '' }}>🏭
                                Bakery Own Production</option>
                            <option value="purchased" {{ request('type') == 'purchased' ? 'selected' : '' }}>🛒 Purchased
                                Items</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12 col-md-2 col-lg-4 d-flex gap-2">
                        <button type="submit" class="btn btn-dark fw-semibold px-3">Search</button>
                        @if (request('search') || request('type'))
                            <a href="{{ route('company.owner.products.index', [$company_slug, 'tab' => $activeTab]) }}"
                                class="btn btn-outline-secondary px-3" title="Clear Filters">Clear</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- PRODUCTS TABLE -->
        <div class="card border-0 shadow-sm rounded-3 bg-white">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle m-0">
                        <thead class="table-light border-bottom">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-4 py-3">Product</th>
                                <th class="py-3">Barcode</th>
                                <th class="py-3">Source</th>
                                <th class="py-3">Selling Price(s)</th>
                                <th class="py-3">Stock Level</th>
                                <th class="py-3">Expiry Date</th>
                                <th class="py-3 text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <!-- Image & Name -->
                                    <td class="ps-4 py-3">
                                        <a href="{{ route('company.owner.products.show', [$company_slug, $product->id]) }}"
                                            class="text-decoration-none d-flex align-items-center">
                                            @if ($product->main_image)
                                                <img src="{{ asset('storage/' . $product->main_image) }}"
                                                    alt="{{ $product->product_name }}"
                                                    class="rounded-2 me-3 object-fit-cover"
                                                    style="width: 48px; height: 48px;">
                                            @else
                                                <div class="bg-light rounded-2 me-3 d-flex align-items-center justify-content-center text-secondary fw-bold"
                                                    style="width: 48px; height: 48px; font-size: 1.2rem;">
                                                    <i class="fa-solid fa-bread-slice"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0 hover-primary">
                                                    {{ $product->product_name }}</h6>
                                                <span class="text-muted small">Unit:
                                                    {{ strtoupper($product->unit) }}</span>
                                            </div>
                                        </a>
                                    </td>

                                    <!-- Barcode -->
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace py-1.5 px-2">
                                            <i
                                                class="fa-solid fa-barcode me-1 text-secondary"></i>{{ $product->product_code }}
                                        </span>
                                    </td>

                                    <!-- Source -->
                                    <td>
                                        @if ($product->product_type === 'own_production')
                                            <span
                                                class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                                Bakery Own
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                                Purchased
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Selling Price -->
                                    <td>
                                        @if ($product->pricing_type === 'flat')
                                            <div class="fw-bold text-dark">
                                                ₹{{ number_format($product->flat_selling_price, 2) }} <span
                                                    class="badge bg-secondary-subtle text-secondary small">Flat</span></div>
                                        @else
                                            <div class="small">
                                                <div><span class="text-muted">Salesman:</span>
                                                    <strong>₹{{ number_format($product->salesman_price, 2) }}</strong>
                                                </div>
                                                <div><span class="text-muted">Retailer:</span>
                                                    <strong>₹{{ number_format($product->retailer_price, 2) }}</strong>
                                                </div>
                                                <div><span class="text-muted">Customer:</span>
                                                    <strong>₹{{ number_format($product->customer_price, 2) }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Stock Quantity -->
                                    <td>
                                        @if ($product->stock_quantity > 10)
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 fs-6">
                                                {{ $product->stock_quantity }} {{ $product->unit }}
                                            </span>
                                        @elseif($product->stock_quantity > 0)
                                            <span
                                                class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1.5 fs-6">
                                                {{ $product->stock_quantity }} {{ $product->unit }} (Low)
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 fs-6">
                                                Out of Stock
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Expiry Date -->
                                    <td>
                                        @if ($product->expiry_date)
                                            @php
                                                $isExpired = \Carbon\Carbon::parse($product->expiry_date)->isPast();
                                                $isExpiringSoon =
                                                    \Carbon\Carbon::parse($product->expiry_date)->diffInDays(
                                                        \Carbon\Carbon::now(),
                                                    ) <= 7;
                                            @endphp
                                            <span
                                                class="small fw-bold {{ $isExpired ? 'text-danger' : ($isExpiringSoon ? 'text-warning-emphasis' : 'text-secondary') }}">
                                                {{ \Carbon\Carbon::parse($product->expiry_date)->format('d M, Y') }}
                                                @if ($isExpired)
                                                    <span class="badge bg-danger ms-1">Expired</span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>

                                    <!-- Action Buttons -->
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <a href="{{ route('company.owner.products.show', [$company_slug, $product->id]) }}"
                                                class="btn btn-sm btn-light border text-secondary" title="View Details">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('company.owner.products.edit', [$company_slug, $product->id]) }}"
                                                class="btn btn-sm btn-light border text-primary" title="Edit Product">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form
                                                action="{{ route('company.owner.products.destroy', [$company_slug, $product->id]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete {{ $product->product_name }}?');"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger"
                                                    title="Delete Product">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-boxes-packing fs-1 mb-2 d-block opacity-25"></i>
                                        No products found matching your search or filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                @if ($products->hasPages())
                    <div class="p-3 border-top d-flex justify-content-center">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
