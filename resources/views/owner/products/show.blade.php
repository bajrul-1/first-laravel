@extends('layouts.owner')

@section('content')
<div class="container-fluid p-0">

<!-- SUCCESS NOTIFICATION BANNER -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3 p-3 bg-success bg-opacity-10 text-success border-start border-success border-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                <div>
                    <strong class="d-block text-dark fw-bold mb-0">Update Successful!</strong>
                    <span class="small text-secondary">{{ session('success') }}</span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header & Action Buttons -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <a href="{{ route('company.owner.products.index', $company_slug) }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Catalog
            </a>
            <h4 class="fw-bold text-dark m-0">{{ $product->product_name }}</h4>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex flex-wrap gap-2">
            <!-- Print Barcode -->
            <a href="{{ route('company.owner.products.print-barcode', [$company_slug, $product->id]) }}" target="_blank" class="btn btn-outline-dark fw-semibold shadow-sm">
                <i class="fa-solid fa-barcode me-1"></i> Print Barcode
            </a>

            <!-- Edit / Refill Stock -->
            <a href="{{ route('company.owner.products.edit', [$company_slug, $product->id]) }}" class="btn btn-primary fw-semibold shadow-sm">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit / Refill Stock
            </a>

            <!-- Delete -->
            <form action="{{ route('company.owner.products.destroy', [$company_slug, $product->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger fw-semibold shadow-sm">
                    <i class="fa-solid fa-trash me-1"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Product Details Content -->
    <div class="row g-4" x-data="{ 
        activeImage: '{{ $product->main_image ? asset('storage/' . $product->main_image) : '' }}' 
    }">
        <!-- LEFT: GALLERY SECTION -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                <div class="text-center bg-light rounded-3 p-3 mb-3 d-flex align-items-center justify-content-center" style="height: 320px; overflow: hidden;">
                    <template x-if="activeImage">
                        <img :src="activeImage" class="img-fluid rounded-2 object-fit-contain h-100" alt="Product Image">
                    </template>
                    <template x-if="!activeImage">
                        <div class="text-secondary opacity-50 text-center">
                            <i class="fa-solid fa-bread-slice fs-1 mb-2"></i>
                            <div class="small">No Display Image</div>
                        </div>
                    </template>
                </div>

                <!-- Gallery Thumbnails -->
                @if($product->main_image || $product->images->count() > 0)
                    <div class="d-flex gap-2 overflow-auto pb-2">
                        @if($product->main_image)
                            <div @click="activeImage = '{{ asset('storage/' . $product->main_image) }}'" 
                                 class="border rounded-2 p-1 bg-white shadow-sm" 
                                 :class="activeImage === '{{ asset('storage/' . $product->main_image) }}' ? 'border-primary border-2' : 'opacity-75'"
                                 style="width: 65px; height: 65px; flex-shrink: 0; cursor: pointer;">
                                <img src="{{ asset('storage/' . $product->main_image) }}" class="w-100 h-100 object-fit-cover rounded-1">
                            </div>
                        @endif

                        @foreach($product->images as $galleryImg)
                            <div @click="activeImage = '{{ asset('storage/' . $galleryImg->image_path) }}'" 
                                 class="border rounded-2 p-1 bg-white shadow-sm" 
                                 :class="activeImage === '{{ asset('storage/' . $galleryImg->image_path) }}' ? 'border-primary border-2' : 'opacity-75'"
                                 style="width: 65px; height: 65px; flex-shrink: 0; cursor: pointer;">
                                <img src="{{ asset('storage/' . $galleryImg->image_path) }}" class="w-100 h-100 object-fit-cover rounded-1">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT: DETAILS & PRICING -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-4">
                <div class="d-flex align-items-center justify-content-between pb-3 border-bottom mb-3">
                    <div>
                        <span class="badge bg-light text-dark border font-monospace py-1.5 px-3 fs-6">
                            <i class="fa-solid fa-barcode me-1 text-secondary"></i> {{ $product->product_code }}
                        </span>
                    </div>
                    <div>
                        @if($product->product_type === 'own_production')
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill">
                                Bakery Own Production
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 rounded-pill">
                                Purchased Item
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Stock Info -->
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="text-muted small fw-semibold">Available Stock</div>
                            <div class="fs-5 fw-bold text-dark mt-1">
                                {{ $product->stock_quantity }} {{ strtoupper($product->unit) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="text-muted small fw-semibold">Expiry Date</div>
                            <div class="fs-6 fw-bold text-dark mt-1">
                                {{ $product->expiry_date ? \Carbon\Carbon::parse($product->expiry_date)->format('d M, Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing Table -->
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fa-solid fa-tags text-success me-2"></i>Pricing Structure
                </h6>
                <div class="table-responsive border rounded-3">
                    <table class="table align-middle m-0">
                        <tbody class="small">
                            @if($product->buying_price)
                                <tr>
                                    <td class="bg-light fw-semibold text-secondary w-50">Buying Price (Cost)</td>
                                    <td class="fw-bold text-danger">₹{{ number_format($product->buying_price, 2) }}</td>
                                </tr>
                            @endif

                            @if($product->pricing_type === 'flat')
                                <tr>
                                    <td class="bg-light fw-semibold text-secondary w-50">Flat Selling Price</td>
                                    <td class="fw-bold text-success fs-6">₹{{ number_format($product->flat_selling_price, 2) }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td class="bg-light fw-semibold text-secondary">Salesman Delivery Rate</td>
                                    <td class="fw-bold text-primary">₹{{ number_format($product->salesman_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light fw-semibold text-secondary">Retailer Rate</td>
                                    <td class="fw-bold text-dark">₹{{ number_format($product->retailer_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light fw-semibold text-secondary">Customer Rate (MRP)</td>
                                    <td class="fw-bold text-success">₹{{ number_format($product->customer_price, 2) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection