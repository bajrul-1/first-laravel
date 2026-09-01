<div class="container-fluid p-0 position-relative">

    @php
        $themeClass = match ($customer_type) {
            'salesman' => 'theme-salesman',
            'retailer' => 'theme-retailer',
            default => 'theme-customer',
        };
    @endphp

    <!-- 🌐 BROWSER NEW TAB OPENER TOP BAR -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-2 mb-3 border-bottom">
        <div class="d-flex align-items-center gap-2">
            <span class="fs-4">
                @if ($customer_type === 'salesman')
                    🚚
                @elseif($customer_type === 'retailer')
                    🏪
                @else
                    👤
                @endif
            </span>
            <div>
                <h5 class="fw-bold text-dark m-0">
                    Billing Counter:
                    <span
                        class="text-uppercase 
                        @if ($customer_type === 'salesman') text-success 
                        @elseif($customer_type === 'retailer') text-warning 
                        @else text-primary @endif">
                        {{ $customer_type === 'customer' ? 'Customer Bill (MRP)' : ($customer_type === 'retailer' ? 'Wholesaler / Retailer' : 'Salesman Route') }}
                    </span>
                </h5>
                <small class="text-muted">Separate pricing and accounts automatically applied.</small>
            </div>
        </div>

        <!-- NEW TAB LAUNCH BUTTONS -->
        <div class="d-flex align-items-center gap-2">
            <span class="small text-muted fw-semibold me-1 d-none d-md-inline">Open New Bill Tab:</span>

            <a href="{{ route('company.owner.pos.index', ['company_slug' => $company_slug, 'type' => 'customer']) }}"
                target="_blank" class="btn btn-sm btn-outline-primary fw-bold shadow-sm">
                👤 + Customer Tab
            </a>

            <a href="{{ route('company.owner.pos.index', ['company_slug' => $company_slug, 'type' => 'retailer']) }}"
                target="_blank" class="btn btn-sm btn-outline-warning text-dark fw-bold shadow-sm">
                🏪 + Wholesaler Tab
            </a>

            <a href="{{ route('company.owner.pos.index', ['company_slug' => $company_slug, 'type' => 'salesman']) }}"
                target="_blank" class="btn btn-sm btn-outline-success fw-bold shadow-sm">
                🚚 + Salesman Tab
            </a>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if (session()->has('sale_success'))
        <div
            class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center">
            <i class="fa-solid fa-circle-check fs-4 me-2"></i>
            <div class="fw-bold">{{ session('sale_success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('scan_error'))
        <div
            class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center">
            <i class="fa-solid fa-triangle-exclamation fs-4 me-2"></i>
            <div>{{ session('scan_error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- MAIN BILLING INTERFACE -->
    <div class="row g-3 {{ $themeClass }}">

        <!-- LEFT: SCANNER, SEARCH & CART (8 COLUMNS) -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100 d-flex flex-column theme-border-top">

                <!-- TOP BAR: SCANNER, SEARCH & VIEW CATALOG BUTTON -->
                <div class="row g-2 mb-3 align-items-center">
                    <!-- Barcode Input -->
                    <div class="col-12 col-md-5">
                        <form wire:submit.prevent="handleBarcodeScan">
                            <div class="input-group">
                                <span class="input-group-text bg-light text-primary border-end-0"><i
                                        class="fa-solid fa-barcode fs-5"></i></span>
                                <input type="text" wire:model="barcode_scan"
                                    class="form-control font-monospace border-start-0 bg-light"
                                    placeholder="Scan Barcode or Code + Enter" autofocus>
                            </div>
                        </form>
                    </div>

                    <!-- Search by Name -->
                    <div class="col-12 col-md-4 position-relative">
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i
                                    class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" wire:model.live.debounce.150ms="search_query"
                                class="form-control border-start-0 bg-light" placeholder="Search item name...">
                            @if ($search_query)
                                <button type="button" wire:click="$set('search_query', '')"
                                    class="btn btn-light border-start-0 border text-muted">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            @endif
                        </div>

                        <!-- Dropdown Results with Live Image -->
                        @if (!empty($searchResults) && strlen(trim($search_query)) >= 1)
                            <div class="position-absolute start-0 w-100 bg-white border border-secondary border-opacity-25 shadow-lg rounded-3 mt-1 py-1"
                                style="z-index: 1050; max-height: 280px; overflow-y: auto;">
                                @foreach ($searchResults as $res)
                                    @php $itemPrice = $this->calculateUnitPrice($res); @endphp
                                    <div wire:click="addProductToCart({{ $res->id }})"
                                        class="px-3 py-2 border-bottom cursor-pointer d-flex justify-content-between align-items-center hover-bg-item">
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($res->main_image)
                                                <img src="{{ asset('storage/' . $res->main_image) }}" class="rounded"
                                                    style="width: 38px; height: 38px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light text-muted d-flex align-items-center justify-content-center"
                                                    style="width: 38px; height: 38px;">
                                                    <i class="fa-solid fa-bread-slice text-secondary"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark mb-0 small">{{ $res->product_name }}</div>
                                                <small class="text-muted font-monospace" style="font-size: 0.72rem;">
                                                    {{ $res->product_code }} | <span
                                                        class="text-success fw-semibold">Stock:
                                                        {{ $res->stock_quantity }} {{ $res->unit }}</span>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold text-dark">₹{{ number_format($itemPrice, 2) }}</span>
                                            <span class="badge bg-primary text-white ms-1 small">Add</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- 👁️ View Catalog Modal Trigger -->
                    <div class="col-12 col-md-3">
                        <button type="button" wire:click="openCatalog"
                            class="btn btn-outline-dark w-100 fw-bold d-flex align-items-center justify-content-center gap-1">
                            <i class="fa-solid fa-eye text-primary"></i>
                            <span>View Catalog</span>
                        </button>
                    </div>
                </div>

                <!-- CART ITEMS TABLE -->
                <div class="table-responsive flex-grow-1" style="min-height: 320px; max-height: 480px;">
                    <table class="table align-middle table-hover m-0">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th style="width: 5%">#</th>
                                <th style="width: 45%">Item Details</th>
                                <th style="width: 15%" class="text-center">Rate</th>
                                <th style="width: 20%" class="text-center">Qty</th>
                                <th style="width: 15%" class="text-end">Total</th>
                                <th style="width: 5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cart as $id => $item)
                                <tr>
                                    <td class="text-muted small">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item['name'] }}</div>
                                        <small class="text-muted font-monospace">{{ $item['code'] }}</small>
                                    </td>
                                    <td class="text-center fw-semibold">₹{{ number_format($item['price'], 2) }}</td>
                                    <td class="text-center">
                                        <div class="input-group input-group-sm justify-content-center"
                                            style="max-width: 120px; margin: 0 auto;">
                                            <input type="number" min="1" max="{{ $item['max_stock'] }}"
                                                value="{{ $item['qty'] }}"
                                                wire:change="updateQuantity({{ $id }}, $event.target.value)"
                                                class="form-control text-center fw-bold bg-light">
                                            <span
                                                class="input-group-text bg-white small text-muted">{{ $item['unit'] }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-dark">₹{{ number_format($item['subtotal'], 2) }}
                                    </td>
                                    <td class="text-end">
                                        <button wire:click="removeItem({{ $id }})"
                                            class="btn btn-sm text-danger p-0 border-0" title="Remove item">
                                            <i class="fa-solid fa-circle-xmark fs-5"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-basket-shopping fs-1 d-block mb-2 opacity-25"></i>
                                        Cart is empty. Scan barcode or click "View Catalog" to add items.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (!empty($cart))
                    <div class="d-flex justify-content-between align-items-center border-top pt-2 mt-2">
                        <button wire:click="clearCart" class="btn btn-outline-danger btn-sm fw-semibold">
                            <i class="fa-solid fa-trash-can me-1"></i> Clear Cart
                        </button>
                        <span class="text-muted small fw-semibold">Total Items:
                            <strong>{{ count($cart) }}</strong></span>
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT: BUYER IDENTIFIER & CHECKOUT (4 COLUMNS) -->
        <div class="col-12 col-lg-4">
            <div
                class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100 d-flex flex-column justify-content-between theme-border-top">

                <div>
                    <!-- 1. DYNAMIC BUYER ACCOUNT LOOKUP -->
                    <div class="p-3 rounded-3 theme-panel-bg mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold small text-dark">
                                @if ($customer_type === 'salesman')
                                    🚚 Salesman Staff
                                @elseif($customer_type === 'retailer')
                                    🏪 Wholesaler Account
                                @else
                                    👤 Customer Details
                                @endif
                            </span>

                            <!-- Visual Status Tag -->
                            @if ($customer_type === 'customer')
                                @if ($is_old_customer)
                                    <span class="badge bg-success small"><i class="fa-solid fa-check me-1"></i> Old
                                        Customer</span>
                                @elseif(!empty($customer_phone))
                                    <span class="badge bg-secondary small">New Customer</span>
                                @endif
                            @endif
                        </div>

                        <!-- Wholesaler Select Dropdown -->
                        @if ($customer_type === 'retailer')
                            <div class="mb-2">
                                <label class="form-label small text-muted mb-1">Select Registered Wholesaler</label>
                                <select wire:model.live="selected_user_id"
                                    class="form-select form-select-sm bg-white fw-semibold">
                                    <option value="">-- Choose Wholesaler / Retailer --</option>
                                    @foreach ($wholesalersList as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->name }}
                                            ({{ $wh->email ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Salesman Select Dropdown -->
                        @if ($customer_type === 'salesman')
                            <div class="mb-2">
                                <label class="form-label small text-muted mb-1">Select Active Salesman</label>
                                <select wire:model.live="selected_user_id"
                                    class="form-select form-select-sm bg-white fw-semibold">
                                    <option value="">-- Choose Salesman Staff --</option>
                                    @foreach ($salesmenList as $sm)
                                        <option value="{{ $sm->id }}">{{ $sm->name }}
                                            ({{ $sm->email ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Mobile Number Lookup -->
                        <div class="mb-2">
                            <input type="text" wire:model.live.debounce.400ms="customer_phone"
                                class="form-control form-control-sm bg-white"
                                placeholder="Mobile Number (e.g. 9876543210)">
                        </div>

                        <!-- Name Input -->
                        <div class="mb-1">
                            <input type="text" wire:model.defer="customer_name"
                                class="form-control form-control-sm bg-white" placeholder="Name / Shop Name">
                        </div>

                        @if (($previous_due ?? 0) > 0)
                            <div
                                class="mt-2 text-danger small fw-bold d-flex justify-content-between align-items-center">
                                <span><i class="fa-solid fa-circle-exclamation me-1"></i> Previous Unsettled
                                    Due:</span>
                                <span>₹{{ number_format($previous_due, 2) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- 2. BILL SUMMARY -->
                    <div class="d-flex justify-content-between mb-1 small text-muted">
                        <span>Items Subtotal:</span>
                        <span class="fw-bold text-dark">₹{{ number_format($this->subtotal, 2) }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2 small text-muted">
                        <span>Discount (₹):</span>
                        <input type="number" min="0" step="1" wire:model.live="discount"
                            class="form-control form-control-sm text-end fw-semibold py-0" style="width: 90px;">
                    </div>

                    <div class="d-flex justify-content-between align-items-center py-2 border-top border-bottom my-2">
                        <span class="fw-bold fs-5 text-dark">Net Payable:</span>
                        <span class="fw-bold fs-4 theme-price-color">₹{{ number_format($this->netPayable, 2) }}</span>
                    </div>

                    <!-- 3. PAYMENT BREAKDOWN -->
                    <div class="theme-panel-bg p-2.5 rounded-3 my-3">
                        <div class="fw-bold small text-dark mb-2">Payment Collection</div>

                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label text-muted mb-0 small">💵 Cash Paid (₹)</label>
                                <input type="number" step="0.01" wire:model.live="cash_paid"
                                    class="form-control form-control-sm bg-white fw-bold">
                            </div>
                            <div class="col-6">
                                <label class="form-label text-muted mb-0 small">📱 Online / UPI (₹)</label>
                                <input type="number" step="0.01" wire:model.live="online_paid"
                                    class="form-control form-control-sm bg-white fw-bold">
                            </div>
                        </div>

                        @if (($current_due ?? 0) > 0)
                            <div
                                class="mt-2 p-2 rounded bg-danger-subtle text-danger d-flex justify-content-between align-items-center small fw-bold">
                                <span>Due / Balance:</span>
                                <span>₹{{ number_format($current_due, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 4. CHECKOUT BUTTON -->
                <div>
                    <button wire:click="completeSale" wire:loading.attr="disabled"
                        class="btn theme-btn-checkout w-100 py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center text-white"
                        @if (empty($cart)) disabled @endif>
                        <span wire:loading.remove><i class="fa-solid fa-print me-2"></i> Save & Print Bill</span>
                        <span wire:loading><i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...</span>
                    </button>
                </div>

            </div>
        </div>

    </div>

    <!-- ========================================================= -->
    <!-- 🖼️ POPUP CATALOG MODAL (SAFE ARRAY ACCESS) -->
    <!-- ========================================================= -->
    @if ($showCatalogModal)
        <div class="modal-backdrop-custom d-flex align-items-center justify-content-center">
            <div class="modal-dialog-custom bg-white rounded-3 shadow-lg d-flex flex-column">

                <!-- Modal Header -->
                <div
                    class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light rounded-top-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-boxes-stacked text-primary fs-5"></i>
                        <h5 class="fw-bold text-dark m-0">Catalog Items (Rates for {{ ucfirst($customer_type) }})</h5>
                    </div>
                    <button type="button" wire:click="closeCatalog" class="btn-close" aria-label="Close"></button>
                </div>

                <!-- Modal Search Filter & Alert -->
                <div class="p-3 border-bottom bg-white">
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted"><i
                                class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" wire:model.live.debounce.200ms="catalog_search"
                            class="form-control bg-light" placeholder="Search catalog items...">
                    </div>

                    @if (session()->has('modal_error'))
                        <div
                            class="alert alert-danger border-0 shadow-sm rounded-3 mt-2 mb-0 py-2 d-flex align-items-center small">
                            <i class="fa-solid fa-triangle-exclamation fs-5 me-2"></i>
                            <div class="fw-bold">{{ session('modal_error') }}</div>
                        </div>
                    @endif
                </div>

                <!-- Modal Body -->
                <div class="p-3 overflow-auto flex-grow-1" style="background-color: #f8fafc;">
                    <div class="row g-3">
                        @forelse($catalogProducts as $prod)
                            @php
                                $rate = $this->calculateUnitPrice($prod);
                                // Safe Array Access without boolean offset error
                                $inCartQty =
                                    isset($cart[$prod->id]) && is_array($cart[$prod->id])
                                        ? $cart[$prod->id]['qty'] ?? 0
                                        : 0;
                            @endphp
                            <div class="col-6 col-md-4 col-lg-3">
                                <div
                                    class="card h-100 border bg-white rounded-3 overflow-hidden shadow-sm product-card-hover position-relative {{ $inCartQty > 0 ? 'border-primary border-2' : '' }}">

                                    <!-- Cover Image -->
                                    <div class="bg-light position-relative d-flex align-items-center justify-content-center border-bottom"
                                        style="height: 125px;">
                                        @if ($prod->main_image)
                                            <img src="{{ asset('storage/' . $prod->main_image) }}"
                                                class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <div class="text-muted d-flex flex-column align-items-center">
                                                <i class="fa-solid fa-bread-slice fs-2 mb-1 opacity-25"></i>
                                                <span class="small opacity-50" style="font-size: 0.7rem;">No
                                                    Image</span>
                                            </div>
                                        @endif

                                        <!-- Stock Badge -->
                                        <span
                                            class="position-absolute top-0 end-0 m-1.5 badge bg-dark bg-opacity-75 small">
                                            {{ $prod->stock_quantity }} {{ strtoupper($prod->unit) }}
                                        </span>

                                        <!-- Live Cart Badge -->
                                        @if ($inCartQty > 0)
                                            <span
                                                class="position-absolute top-0 start-0 m-1.5 badge bg-success shadow-sm fw-bold">
                                                <i class="fa-solid fa-check me-1"></i> In Cart: {{ $inCartQty }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Details & Action Buttons -->
                                    <div
                                        class="p-2.5 text-center d-flex flex-column justify-content-between flex-grow-1">
                                        <div>
                                            <div class="fw-bold text-dark text-truncate small"
                                                title="{{ $prod->product_name }}">{{ $prod->product_name }}</div>
                                            <div class="text-muted font-monospace" style="font-size: 0.72rem;">
                                                {{ $prod->product_code }}</div>
                                        </div>

                                        <div
                                            class="mt-2 pt-2 border-top d-flex align-items-center justify-content-between">
                                            <span
                                                class="fw-bold text-primary fs-6">₹{{ number_format($rate, 2) }}</span>

                                            @if ($inCartQty > 0)
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button"
                                                        wire:click="decrementCartProduct({{ $prod->id }})"
                                                        class="btn btn-outline-danger px-2 fw-bold">-</button>
                                                    <button type="button"
                                                        class="btn btn-primary px-2 fw-bold disabled text-white">{{ $inCartQty }}</button>
                                                    <button type="button"
                                                        wire:click="addProductToCart({{ $prod->id }})"
                                                        class="btn btn-outline-success px-2 fw-bold">+</button>
                                                </div>
                                            @else
                                                <button type="button"
                                                    wire:click="addProductToCart({{ $prod->id }})"
                                                    class="btn btn-sm btn-primary fw-bold px-3 py-1">
                                                    <i class="fa-solid fa-plus me-1"></i> Add
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-5">
                                <i class="fa-solid fa-box-open fs-1 d-block mb-2 opacity-25"></i>
                                No products found.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Modal Footer -->
                <div
                    class="p-2.5 border-top d-flex justify-content-between align-items-center bg-light rounded-bottom-3">
                    <span class="text-muted small">Cart Items: <strong>{{ count($cart) }}</strong></span>
                    <button type="button" wire:click="closeCatalog" class="btn btn-dark btn-sm fw-bold px-4">
                        Done / Close
                    </button>
                </div>

            </div>
        </div>
    @endif

    <!-- ========================================================= -->
    <!-- 🧾 BILL SUCCESS & PRINT RECEIPT POPUP MODAL -->
    <!-- ========================================================= -->
    @if ($showReceiptModal && $lastSavedSale)
        <div class="modal-backdrop-custom d-flex align-items-center justify-content-center">
            <div class="modal-dialog-custom bg-white rounded-3 shadow-lg d-flex flex-column"
                style="max-width: 480px; height: auto;">

                <div class="p-3 bg-success text-white rounded-top-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                        <h5 class="fw-bold m-0">Bill Completed!</h5>
                    </div>
                    <button type="button" wire:click="closeReceiptModal" class="btn-close btn-close-white"></button>
                </div>

                <div class="p-3 text-center border-bottom bg-light">
                    <h6 class="fw-bold text-dark m-0">{{ $lastSavedSale->invoice_no }}</h6>
                    <small class="text-muted">Buyer: {{ $lastSavedSale->customer_name }}
                        ({{ ucfirst($lastSavedSale->customer_type) }})</small>
                    <div class="fs-4 fw-bold text-success mt-1">₹{{ number_format($lastSavedSale->grand_total, 2) }}
                    </div>
                </div>

                <div class="p-3 overflow-auto" style="max-height: 220px;">
                    <table class="table table-sm align-middle small m-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lastSavedSale->items as $it)
                                <tr>
                                    <td>{{ $it->product->product_name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $it->quantity }}</td>
                                    <td class="text-end fw-bold">₹{{ number_format($it->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-3 bg-light border-top rounded-bottom-3 d-flex gap-2">
                    <a href="{{ route('company.owner.pos.print', ['company_slug' => $company_slug, 'id' => $lastSavedSale->id]) }}"
                        target="_blank" class="btn btn-primary fw-bold flex-grow-1">
                        <i class="fa-solid fa-print me-1"></i> Print Receipt
                    </a>
                    <button type="button" wire:click="closeReceiptModal" class="btn btn-dark fw-bold px-3">
                        + New Sale
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>

<!-- 🎨 THEME STYLES -->
<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .hover-bg-item:hover {
        background-color: #f1f5f9;
    }

    /* 👤 Customer Theme */
    .theme-customer .theme-border-top {
        border-top: 4px solid #0d6efd !important;
    }

    .theme-customer .theme-panel-bg {
        background-color: #eff6ff;
    }

    .theme-customer .theme-price-color {
        color: #0d6efd;
    }

    .theme-customer .theme-btn-checkout {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .theme-customer .theme-btn-checkout:hover {
        background-color: #0b5ed7;
    }

    /* 🏪 Retailer Theme */
    .theme-retailer .theme-border-top {
        border-top: 4px solid #d97706 !important;
    }

    .theme-retailer .theme-panel-bg {
        background-color: #fffbeb;
    }

    .theme-retailer .theme-price-color {
        color: #d97706;
    }

    .theme-retailer .theme-btn-checkout {
        background-color: #d97706;
        border-color: #d97706;
    }

    .theme-retailer .theme-btn-checkout:hover {
        background-color: #b45309;
    }

    /* 🚚 Salesman Theme */
    .theme-salesman .theme-border-top {
        border-top: 4px solid #059669 !important;
    }

    .theme-salesman .theme-panel-bg {
        background-color: #ecfdf5;
    }

    .theme-salesman .theme-price-color {
        color: #059669;
    }

    .theme-salesman .theme-btn-checkout {
        background-color: #059669;
        border-color: #059669;
    }

    .theme-salesman .theme-btn-checkout:hover {
        background-color: #047857;
    }

    /* Modal Backdrop */
    .modal-backdrop-custom {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.65);
        z-index: 1065;
    }

    .modal-dialog-custom {
        width: 92%;
        max-width: 980px;
        height: 86vh;
        z-index: 1070;
    }
</style>
