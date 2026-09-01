@props(['company_slug' => request()->route('company_slug')])

<div class="card border-0 shadow-sm rounded-3 bg-white p-4" x-data="{
    productType: '{{ old('product_type', 'own_production') }}',
    pricingType: '{{ old('pricing_type', 'flat') }}'
}">

    <form action="{{ route('company.owner.products.store', $company_slug ?? request()->route('company_slug')) }}"
        method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 1. BASIC INFORMATION -->
        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-circle-info text-primary me-2"></i>Basic Information
        </h5>

        <div class="row g-3 mb-4">

            <div class="form-group mb-3">
                <label for="barcode">Barcode (Scan external product or leave empty to auto-generate)</label>
                <div class="input-group">
                    <input type="text" id="barcode" wire:model="barcode" class="form-control"
                        placeholder="Scanner input will appear here..." readonly>
                    <button type="button" class="btn btn-outline-secondary" id="start-camera-btn"
                        onclick="startCameraScanner()">
                        Use Camera
                    </button>
                </div>
                <!-- Camera feed container -->
                <div id="reader" style="width: 100%; max-width: 400px; display: none; margin-top: 10px;"></div>
            </div>
            <!-- Product Name -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Product Name <span class="text-danger">*</span></label>
                <input type="text" name="product_name"
                    class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name') }}"
                    placeholder="e.g. Special White Bread" required>
                @error('product_name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Product Source Type -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Product Source / Type <span
                        class="text-danger">*</span></label>
                <select name="product_type" x-model="productType"
                    class="form-select @error('product_type') is-invalid @enderror" required>
                    <option value="own_production">🏭 Bakery Own Production</option>
                    <option value="purchased">🛒 Purchased Item (Trading)</option>
                </select>
                @error('product_type')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Buying Price (Only visible for Purchased Items) -->
            <div class="col-12 col-md-6" x-show="productType === 'purchased'" x-cloak>
                <label class="form-label fw-semibold small">Buying Price (₹) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="buying_price"
                    class="form-control @error('buying_price') is-invalid @enderror" value="{{ old('buying_price') }}"
                    placeholder="0.00">
                @error('buying_price')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- 2. SELLING PRICE & STRATEGY -->
        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-indian-rupee-sign text-success me-2"></i>Selling Price & Pricing Strategy
        </h5>

        <div class="row g-3 mb-4">
            <!-- Pricing Strategy Selection -->
            <div class="col-12">
                <div class="form-check form-check-inline me-4 cursor-pointer">
                    <input class="form-check-input" type="radio" name="pricing_type" id="pricing_flat" value="flat"
                        x-model="pricingType">
                    <label class="form-check-label fw-semibold cursor-pointer" for="pricing_flat">Flat Selling Price
                        (Single Rate)</label>
                </div>
                <div class="form-check form-check-inline cursor-pointer">
                    <input class="form-check-input" type="radio" name="pricing_type" id="pricing_tiered"
                        value="tiered" x-model="pricingType">
                    <label class="form-check-label fw-semibold cursor-pointer" for="pricing_tiered">Tiered Rates
                        (Salesman, Retailer & Customer)</label>
                </div>
            </div>

            <!-- Single Flat Price Field -->
            <div class="col-12 col-md-6" x-show="pricingType === 'flat'" x-cloak>
                <label class="form-label fw-semibold small">Flat Selling Price (₹) <span
                        class="text-danger">*</span></label>
                <input type="number" step="0.01" name="flat_selling_price"
                    class="form-control @error('flat_selling_price') is-invalid @enderror"
                    value="{{ old('flat_selling_price') }}" placeholder="0.00">
                @error('flat_selling_price')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tiered Price Fields -->
            <div class="col-12 row g-3 m-0 p-0" x-show="pricingType === 'tiered'" x-cloak>
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold small">Salesman Rate (₹) <span
                            class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="salesman_price"
                        class="form-control @error('salesman_price') is-invalid @enderror"
                        value="{{ old('salesman_price') }}" placeholder="0.00">
                    @error('salesman_price')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold small">Wholesaler / Retailer Rate (₹) <span
                            class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="retailer_price"
                        class="form-control @error('retailer_price') is-invalid @enderror"
                        value="{{ old('retailer_price') }}" placeholder="0.00">
                    @error('retailer_price')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold small">Direct Customer / MRP (₹) <span
                            class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="customer_price"
                        class="form-control @error('customer_price') is-invalid @enderror"
                        value="{{ old('customer_price') }}" placeholder="0.00">
                    @error('customer_price')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 3. INVENTORY & VALIDITY -->
        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-boxes-stacked text-warning me-2"></i>Stock & Validity
        </h5>

        <div class="row g-3 mb-4">
            <!-- Initial Stock Quantity -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold small">Initial Stock Quantity <span
                        class="text-danger">*</span></label>
                <input type="number" name="stock_quantity"
                    class="form-control @error('stock_quantity') is-invalid @enderror"
                    value="{{ old('stock_quantity', 0) }}" min="0" required>
                @error('stock_quantity')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Unit Type -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold small">Unit Type <span class="text-danger">*</span></label>
                <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
                    <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pcs / Pieces</option>
                    <option value="pkt" {{ old('unit') == 'pkt' ? 'selected' : '' }}>Pkt / Packet</option>
                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kg / Kilogram</option>
                    <option value="gm" {{ old('unit') == 'gm' ? 'selected' : '' }}>Gm / Gram</option>
                    <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                </select>
                @error('unit')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Expiry Date -->
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold small">Expiry Date (Optional)</label>
                <input type="date" name="expiry_date"
                    class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date') }}">
                @error('expiry_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- 4. PRODUCT IMAGES UPLOAD -->
        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-images text-info me-2"></i>Product Images
        </h5>

        <div class="row g-3 mb-4">
            <!-- Main Cover Image -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Main Cover Image</label>
                <input type="file" name="main_image"
                    class="form-control @error('main_image') is-invalid @enderror" accept="image/*">
                <div class="form-text small">Supported formats: JPG, PNG, WEBP, GIF (Max 5MB)</div>
                @error('main_image')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Gallery Images -->
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Gallery Images (Multiple)</label>
                <input type="file" name="gallery_images[]"
                    class="form-control @error('gallery_images') is-invalid @enderror @error('gallery_images.*') is-invalid @enderror"
                    accept="image/*" multiple>
                <div class="form-text small">You can upload up to 10 images at once.</div>
                @error('gallery_images')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
                @error('gallery_images.*')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- SUBMIT & CANCEL BUTTONS -->
        <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <a href="{{ route('company.owner.products.index', $company_slug ?? request()->route('company_slug')) }}"
                class="btn btn-light border fw-semibold">Cancel</a>
            <button type="submit" class="btn btn-primary fw-bold px-4">
                <i class="fa-solid fa-plus me-1"></i> Save Product
            </button>
        </div>
    </form>
</div>

<!-- Include Html5-Qrcode library from CDN -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let scanner = null;

    // Optimized for realistic POS Laser Scanner Beep
    function playPosBeep() {
        try {
            let context = new(window.AudioContext || window.webkitAudioContext)();
            let oscillator = context.createOscillator();
            let gainNode = context.createGain();

            // 'square' wave provides the sharp, mechanical sound of a real scanner
            oscillator.type = 'square';
            oscillator.frequency.setValueAtTime(1500, context.currentTime); // High pitch

            gainNode.gain.setValueAtTime(0.2, context.currentTime); // Volume control
            gainNode.gain.exponentialRampToValueAtTime(0.01, context.currentTime + 0.08); // Ultra-fast sound drop

            oscillator.connect(gainNode);
            gainNode.connect(context.destination);

            oscillator.start();
            oscillator.stop(context.currentTime + 0.08); // 80 milliseconds duration
        } catch (error) {
            console.error("Audio API error", error);
        }
    }

    function startCameraScanner() {
        let btn = document.getElementById('start-camera-btn');
        let reader = document.getElementById('reader');

        btn.style.display = 'none';
        reader.style.display = 'block';

        if (scanner) {
            scanner.clear();
        }

        // Optimized settings for instant scanning and tilted barcodes
        scanner = new Html5QrcodeScanner(
            "reader", {
                fps: 30, // Increased from 10 to 30 frames per second for instant detection
                // Removed qrbox completely. The scanner now uses the entire camera frame.
                // This allows scanning even if the barcode is angled or at the edge of the screen.
                formatsToSupport: [
                    Html5QrcodeSupportedFormats.EAN_13,
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.UPC_A,
                    Html5QrcodeSupportedFormats.EAN_8
                ]
            },
            false
        );

        scanner.render(function(decodedText) {
            // Play realistic POS beep
            playPosBeep();

            scanner.clear().then(() => {
                scanner = null;
                reader.style.display = 'none';

                btn.style.display = 'inline-block';
                btn.innerText = 'Scan Again';

                let barcodeInput = document.getElementById('barcode');
                barcodeInput.value = decodedText;
                barcodeInput.dispatchEvent(new Event('input'));
            });

        }, function(error) {
            // Ignored to prevent console spam during fast continuous scanning
        });
    }
</script>
