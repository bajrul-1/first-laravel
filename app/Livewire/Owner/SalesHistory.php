<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SalesHistory extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $company_slug;
    public $company;

    // Filters
    public $search = '';
    public $customer_type_filter = '';
    public $payment_status_filter = '';
    public $date_from = '';
    public $date_to = '';

    // Selected Sale Details Modal
    public $selectedSale = null;
    public $showDetailsModal = false;

    public function mount($company_slug)
    {
        $this->company_slug = $company_slug;
        $this->company = Company::where('company_slug', $company_slug)->firstOrFail();
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingCustomerTypeFilter() { $this->resetPage(); }
    public function updatingPaymentStatusFilter() { $this->resetPage(); }

    public function viewSaleDetails($saleId)
    {
        $this->selectedSale = Sale::with(['items.product', 'payments'])
                                  ->where('company_id', $this->company->id)
                                  ->findOrFail($saleId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedSale = null;
    }

    // Cancel / Return Entire Sale & Restore Stock
    public function cancelSale($saleId)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::with('items')->where('company_id', $this->company->id)->findOrFail($saleId);

            if ($sale->status === 'cancelled') {
                session()->flash('error', 'Sale is already cancelled.');
                return;
            }

            // Restore Stock
            foreach ($sale->items as $item) {
                Product::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
            }

            $sale->update(['status' => 'cancelled']);

            DB::commit();
            session()->flash('success', "Invoice {$sale->invoice_no} cancelled and stock returned successfully!");
            $this->closeDetailsModal();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $sales = Sale::where('company_id', $this->company->id)
                     ->when($this->search, function($q) {
                         $q->where(function($sub) {
                             $sub->where('invoice_no', 'like', "%{$this->search}%")
                                 ->orWhere('customer_name', 'like', "%{$this->search}%")
                                 ->orWhere('customer_phone', 'like', "%{$this->search}%");
                         });
                     })
                     ->when($this->customer_type_filter, function($q) {
                         $q->where('customer_type', $this->customer_type_filter);
                     })
                     ->when($this->payment_status_filter, function($q) {
                         $q->where('payment_status', $this->payment_status_filter);
                     })
                     ->when($this->date_from, function($q) {
                         $q->whereDate('created_at', '>=', $this->date_from);
                     })
                     ->when($this->date_to, function($q) {
                         $q->whereDate('created_at', '<=', $this->date_to);
                     })
                     ->latest()
                     ->paginate(15);

        return view('livewire.owner.sales-history', [
            'sales' => $sales
        ]);
    }
}