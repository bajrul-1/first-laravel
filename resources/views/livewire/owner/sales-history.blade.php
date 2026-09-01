<div class="container-fluid p-0 position-relative">

    <!-- Flash Alerts -->
    @if (session('success'))
        <div
            class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center">
            <i class="fa-solid fa-circle-check fs-4 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div
            class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center">
            <i class="fa-solid fa-triangle-exclamation fs-4 me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- TOP HEADER -->
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark m-0"><i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i>Sales
                Invoices & History</h4>
            <p class="text-muted small mb-0">View past customer, wholesaler, and salesman invoices, print receipts, and
                manage returns.</p>
        </div>
        <a href="{{ route('company.owner.pos.index', $company_slug) }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="fa-solid fa-cash-register me-1"></i> Open POS Counter
        </a>
    </div>

    <!-- FILTER BAR -->
    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 mb-3">
        <div class="row g-2">
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                        placeholder="Search Invoice, Customer Name, Phone...">
                </div>
            </div>

            <div class="col-6 col-md-2">
                <select wire:model.live="customer_type_filter" class="form-select form-select-sm">
                    <option value="">All Buyer Types</option>
                    <option value="customer">👤 General Customer</option>
                    <option value="retailer">🏪 Wholesaler / Retailer</option>
                    <option value="salesman">🚚 Salesman</option>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <select wire:model.live="payment_status_filter" class="form-select form-select-sm">
                    <option value="">All Payment Status</option>
                    <option value="paid">✅ Fully Paid</option>
                    <option value="partial">⚠️ Partial Due</option>
                    <option value="unpaid">❌ Unpaid</option>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <input type="date" wire:model.live="date_from" class="form-control form-control-sm"
                    title="From Date">
            </div>

            <div class="col-6 col-md-2">
                <input type="date" wire:model.live="date_to" class="form-control form-control-sm" title="To Date">
            </div>
        </div>
    </div>

    <!-- SALES TABLE -->
    <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle table-hover m-0">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th>Invoice No</th>
                        <th>Date & Time</th>
                        <th>Buyer / Customer</th>
                        <th>Buyer Role</th>
                        <th>Total Amount</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr
                            class="{{ $sale->status === 'cancelled' ? 'table-danger text-muted text-decoration-line-through' : '' }}">
                            <td class="fw-bold font-monospace text-primary">{{ $sale->invoice_no }}</td>
                            <td class="small">{{ $sale->created_at->format('d M, Y h:i A') }}</td>
                            <td>
                                <div class="fw-bold text-dark small">{{ $sale->customer_name }}</div>
                                @if ($sale->customer_phone)
                                    <small class="text-muted font-monospace">{{ $sale->customer_phone }}</small>
                                @endif
                            </td>
                            <td>
                                @if ($sale->customer_type === 'salesman')
                                    <span class="badge bg-success-subtle text-success">🚚 Salesman</span>
                                @elseif($sale->customer_type === 'retailer')
                                    <span class="badge bg-warning-subtle text-warning-emphasis">🏪 Wholesaler</span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary">👤 Customer</span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">₹{{ number_format($sale->grand_total, 2) }}</td>
                            <td class="text-success fw-semibold">₹{{ number_format($sale->paid_total, 2) }}</td>
                            <td class="{{ $sale->due_amount > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
                                ₹{{ number_format($sale->due_amount, 2) }}
                            </td>
                            <td>
                                @if ($sale->status === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @elseif($sale->payment_status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($sale->payment_status === 'partial')
                                    <span class="badge bg-warning text-dark">Partial</span>
                                @else
                                    <span class="badge bg-danger">Unpaid</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button wire:click="viewSaleDetails({{ $sale->id }})"
                                        class="btn btn-light border text-dark fw-semibold" title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <a href="{{ route('company.owner.pos.print', ['company_slug' => $company_slug, 'id' => $sale->id]) }}"
                                        target="_blank" class="btn btn-light border text-primary" title="Print Receipt">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-receipt fs-1 d-block mb-2 opacity-25"></i>
                                No sales invoices found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($sales->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $sales->links() }}
            </div>
        @endif
    </div>

    <!-- ========================================================= -->
    <!-- 📄 INVOICE DETAILS & RETURN / CANCEL MODAL -->
    <!-- ========================================================= -->
    @if ($showDetailsModal && $selectedSale)
        <div class="modal-backdrop-custom d-flex align-items-center justify-content-center">
            <div class="modal-dialog-custom bg-white rounded-3 shadow-lg d-flex flex-column"
                style="max-width: 650px; height: 85vh;">

                <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light rounded-top-3">
                    <div>
                        <h5 class="fw-bold text-dark m-0">Invoice: {{ $selectedSale->invoice_no }}</h5>
                        <small class="text-muted">{{ $selectedSale->created_at->format('d M, Y - h:i A') }}</small>
                    </div>
                    <button type="button" wire:click="closeDetailsModal" class="btn-close"></button>
                </div>

                <div class="p-3 overflow-auto flex-grow-1">
                    <!-- Customer Details Box -->
                    <div class="p-2.5 rounded bg-light border mb-3">
                        <div class="row g-2 small">
                            <div class="col-6"><strong>Customer:</strong> {{ $selectedSale->customer_name }}</div>
                            <div class="col-6"><strong>Phone:</strong> {{ $selectedSale->customer_phone ?? 'N/A' }}
                            </div>
                            <div class="col-6"><strong>Buyer Type:</strong>
                                {{ ucfirst($selectedSale->customer_type) }}</div>
                            <div class="col-6"><strong>Payment:</strong> {{ ucfirst($selectedSale->payment_status) }}
                            </div>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <h6 class="fw-bold small text-dark mb-2">Purchased Items:</h6>
                    <table class="table table-sm align-middle table-bordered mb-3">
                        <thead class="table-light small">
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @foreach ($selectedSale->items as $it)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $it->product->product_name ?? 'Item' }}</td>
                                    <td class="text-center">₹{{ number_format($it->unit_price, 2) }}</td>
                                    <td class="text-center fw-bold">{{ $it->quantity }}
                                        {{ $it->product->unit ?? 'pcs' }}</td>
                                    <td class="text-end fw-bold">₹{{ number_format($it->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="small fw-bold">
                            <tr>
                                <td colspan="4" class="text-end">Grand Total:</td>
                                <td class="text-end text-primary fs-6">
                                    ₹{{ number_format($selectedSale->grand_total, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end">Paid Amount:</td>
                                <td class="text-end text-success">₹{{ number_format($selectedSale->paid_total, 2) }}
                                </td>
                            </tr>
                            @if ($selectedSale->due_amount > 0)
                                <tr class="text-danger">
                                    <td colspan="4" class="text-end">Due Balance:</td>
                                    <td class="text-end">₹{{ number_format($selectedSale->due_amount, 2) }}</td>
                                </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>

                <!-- Footer Actions -->
                <div
                    class="p-3 border-top bg-light rounded-bottom-3 d-flex justify-content-between align-items-center">
                    @if ($selectedSale->status !== 'cancelled')
                        <button type="button"
                            onclick="confirm('Are you sure you want to cancel this bill and return all products to stock?') || event.stopImmediatePropagation()"
                            wire:click="cancelSale({{ $selectedSale->id }})"
                            class="btn btn-outline-danger btn-sm fw-bold">
                            <i class="fa-solid fa-rotate-left me-1"></i> Cancel & Return Stock
                        </button>
                    @else
                        <span class="badge bg-danger p-2">Cancelled Invoice</span>
                    @endif

                    <div class="d-flex gap-2">
                        <a href="{{ route('company.owner.pos.print', ['company_slug' => $company_slug, 'id' => $selectedSale->id]) }}"
                            target="_blank" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="fa-solid fa-print me-1"></i> Print Receipt
                        </a>
                        <button type="button" wire:click="closeDetailsModal" class="btn btn-secondary btn-sm px-3">
                            Close
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif

</div>

<style>
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
        width: 90%;
        max-width: 650px;
        z-index: 1070;
    }
</style>
