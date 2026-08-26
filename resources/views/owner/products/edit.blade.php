@extends('layouts.owner')

@section('content')
    <div class="container-fluid p-0" style="max-width: 900px;" x-data="{
        isChanged: false,
        deletedImages: []
    }">

        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('company.owner.products.show', [$company_slug, $product->id]) }}"
                    class="btn btn-sm btn-outline-secondary mb-1">
                    <i class="fa-solid fa-arrow-left me-1"></i> Cancel
                </a>
                <h4 class="fw-bold text-dark m-0">Edit Product & Media</h4>
            </div>
        </div>

        <form action="{{ route('company.owner.products.update', [$company_slug, $product->id]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Dynamic Hidden Inputs for Images Marked for Deletion -->
            <template x-for="id in deletedImages" :key="id">
                <input type="hidden" name="deleted_gallery_ids[]" :value="id">
            </template>

            <!-- SECTION 1: PRODUCT INFO & STOCK -->
            <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold text-dark m-0"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Product
                        Info & Inventory</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-semibold text-dark small">Product Name</label>
                            <input type="text" name="product_name" class="form-control"
                                value="{{ old('product_name', $product->product_name) }}" @input="isChanged = true"
                                required>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold text-dark small">Measurement Unit</label>
                            <select name="unit" class="form-select" @input="isChanged = true">
                                <option value="pcs" {{ $product->unit == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                <option value="pkt" {{ $product->unit == 'pkt' ? 'selected' : '' }}>Pkt</option>
                                <option value="kg" {{ $product->unit == 'kg' ? 'selected' : '' }}>Kg</option>
                                <option value="box" {{ $product->unit == 'box' ? 'selected' : '' }}>Box</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold text-success small">Current Stock Quantity <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" class="form-control border-success fw-bold"
                                value="{{ old('stock_quantity', $product->stock_quantity) }}" @input="isChanged = true"
                                required>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold text-dark small">Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control"
                                value="{{ old('expiry_date', $product->expiry_date) }}" @input="isChanged = true">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: PRODUCT IMAGES MANAGEMENT -->
            <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold text-dark m-0"><i class="fa-solid fa-images text-info me-2"></i>Manage Product Images
                    </h6>
                </div>
                <div class="card-body p-4">

                    <!-- 1. MAIN DISPLAY IMAGE -->
                    <div class="mb-4 pb-3 border-bottom">
                        <label class="form-label fw-bold text-dark small mb-2">Main Display Image (Cover Photo)</label>

                        <div class="d-flex align-items-center gap-3 mb-2">
                            @if ($product->main_image)
                                <div class="position-relative border rounded-3 p-1 bg-light"
                                    style="width: 90px; height: 90px;">
                                    <img src="{{ asset('storage/' . $product->main_image) }}"
                                        class="w-100 h-100 object-fit-cover rounded-2">
                                </div>
                                <div>
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle mb-1">Current
                                        Main Photo</span>
                                    <div class="small text-muted">Selecting a new file below will replace this main photo
                                        upon saving.</div>
                                </div>
                            @else
                                <div class="p-3 bg-light rounded-3 text-secondary small">
                                    <i class="fa-solid fa-image me-1"></i> No Main Cover Image uploaded yet.
                                </div>
                            @endif
                        </div>

                        <!-- Change / Upload Main Image Input -->
                        <input type="file" name="main_image" class="form-control mt-2" accept="image/*"
                            @input="isChanged = true">
                    </div>

                    <!-- 2. GALLERY IMAGES -->
                    <div>
                        <label class="form-label fw-bold text-dark small mb-2">Gallery Images</label>

                        <!-- Existing Gallery List -->
                        @if ($product->images->count() > 0)
                            <div class="d-flex flex-wrap gap-3 mb-3">
                                @foreach ($product->images as $galleryImg)
                                    <div x-show="!deletedImages.includes({{ $galleryImg->id }})"
                                        class="position-relative border rounded-3 p-1 bg-light"
                                        style="width: 90px; height: 90px;">

                                        <img src="{{ asset('storage/' . $galleryImg->image_path) }}"
                                            class="w-100 h-100 object-fit-cover rounded-2">

                                        <!-- Remove Button (Only removes from UI temporarily) -->
                                        <button type="button"
                                            @click="deletedImages.push({{ $galleryImg->id }}); isChanged = true"
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle p-0 d-flex align-items-center justify-content-center shadow"
                                            style="width: 22px; height: 22px; transform: translate(30%, -30%);">
                                            <i class="fa-solid fa-xmark" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted small mb-2">No additional gallery images found.</div>
                        @endif

                        <!-- Add New Gallery Images Input -->
                        <label class="form-label fw-semibold text-dark small">Add More Gallery Images</label>
                        <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/*"
                            @input="isChanged = true">
                        <div class="form-text small text-muted">You can select and upload multiple images at once.</div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PRICING UPDATE -->
            <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold text-dark m-0"><i class="fa-solid fa-tags text-success me-2"></i>Pricing Update
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @if ($product->product_type === 'purchased')
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark small">Buying Price</label>
                                <input type="number" step="0.01" name="buying_price" class="form-control"
                                    value="{{ old('buying_price', $product->buying_price) }}" required
                                    @input="isChanged = true">
                            </div>
                        @endif

                        @if ($product->pricing_type === 'flat')
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold text-dark small">Flat Selling Price</label>
                                <input type="number" step="0.01" name="flat_selling_price" class="form-control"
                                    value="{{ old('flat_selling_price', $product->flat_selling_price) }}" required
                                    @input="isChanged = true">
                            </div>
                        @else
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold text-dark small">Salesman Rate</label>
                                <input type="number" step="0.01" name="salesman_price" class="form-control"
                                    value="{{ old('salesman_price', $product->salesman_price) }}" required
                                    @input="isChanged = true">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold text-dark small">Retailer Rate</label>
                                <input type="number" step="0.01" name="retailer_price" class="form-control"
                                    value="{{ old('retailer_price', $product->retailer_price) }}"
                                    @input="isChanged = true" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold text-dark small">Customer Rate (MRP)</label>
                                <input type="number" step="0.01" name="customer_price" class="form-control"
                                    value="{{ old('customer_price', $product->customer_price) }}"
                                    @input="isChanged = true" required>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold shadow-sm mb-5" :disabled="!isChanged">
                <i class="fa-solid fa-floppy-disk me-2"></i> Save Changes & Update Product
            </button>
        </form>
    </div>
@endsection
