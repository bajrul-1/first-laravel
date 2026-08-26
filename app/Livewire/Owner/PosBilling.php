<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\Product;
use App\Models\Company;
use App\Models\User;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosBilling extends Component
{
    public $company_slug;
    public $company;
    public $customer_type = 'customer'; // customer, retailer, salesman

    // Search & Scan
    public $barcode_scan = '';
    public $search_query = '';
    public $catalog_search = '';
    public $showCatalogModal = false;

    // Buyer Information
    public $selected_user_id = '';
    public $customer_phone = '';
    public $customer_name = '';
    public $is_old_customer = false;
    public $previous_due = 0.00;

    // Cart Items: [$productId => [...]]
    public $cart = [];

    // Checkout & Payment
    public $discount = 0;
    public $cash_paid = 0;
    public $online_paid = 0;
    public $current_due = 0;

    // User Lists
    public $wholesalersList = [];
    public $salesmenList = [];

    public function mount($company_slug, $customer_type = 'customer')
    {
        $this->company_slug = $company_slug;
        $this->customer_type = in_array($customer_type, ['customer', 'retailer', 'salesman']) ? $customer_type : 'customer';
        $this->company = Company::where('company_slug', $company_slug)->firstOrFail();

        $this->salesmenList = User::where('company_id', $this->company->id)
                                  ->where('role', 'salesman')
                                  ->get(['id', 'name', 'email']);

        $this->wholesalersList = User::where('company_id', $this->company->id)
                                    ->whereIn('role', ['retailer', 'wholesaler', 'dealer'])
                                    ->get(['id', 'name', 'email']);
    }

    // Modal Control
    public function openCatalog()
    {
        $this->showCatalogModal = true;
        $this->catalog_search = '';
    }

    public function closeCatalog()
    {
        $this->showCatalogModal = false;
        $this->catalog_search = '';
    }

    // Phone Auto-lookup for Customer
    public function updatedCustomerPhone($phone)
    {
        $phone = trim($phone);
        if (strlen($phone) >= 10) {
            $lastSale = Sale::where('company_id', $this->company->id)
                            ->where('customer_phone', $phone)
                            ->latest()
                            ->first();

            if ($lastSale) {
                $this->customer_name = $lastSale->customer_name;
                $this->is_old_customer = true;
                $this->previous_due = (float) Sale::where('company_id', $this->company->id)
                                                  ->where('customer_phone', $phone)
                                                  ->sum('due_amount');
            } else {
                $this->is_old_customer = false;
                $this->previous_due = 0.00;
            }
        } else {
            $this->is_old_customer = false;
            $this->previous_due = 0.00;
        }
    }

    // Wholesaler / Salesman Select
    public function updatedSelectedUserId($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $this->customer_name = $user->name;
            $this->customer_phone = $user->phone ?? '';
        }
    }

    // Dynamic Price by Buyer Role
    public function calculateUnitPrice($product)
    {
        if ($product->pricing_type === 'flat') {
            return (float) $product->flat_selling_price;
        }

        if ($this->customer_type === 'salesman') {
            return (float) $product->salesman_price;
        } elseif ($this->customer_type === 'retailer') {
            return (float) $product->retailer_price;
        }

        return (float) $product->customer_price;
    }

    // Barcode Scanning
    public function handleBarcodeScan()
    {
        $code = trim($this->barcode_scan);
        if (empty($code)) return;

        $product = Product::where('company_id', $this->company->id)
                          ->where('product_code', $code)
                          ->first();

        if ($product) {
            $this->addProductToCart($product->id);
            $this->barcode_scan = '';
        } else {
            session()->flash('scan_error', "Barcode '{$code}' not found!");
            $this->barcode_scan = '';
        }
    }

    // Add Product to Cart
    public function addProductToCart($productId)
    {
        $product = Product::where('company_id', $this->company->id)->find($productId);
        if (!$product || $product->stock_quantity <= 0) {
            $msg = "{$product->product_name} is Out of stock!";
            if ($this->showCatalogModal) {
                session()->flash('modal_error', $msg);
            } else {
                session()->flash('scan_error', $msg);
            }
            return;
        }

        $price = $this->calculateUnitPrice($product);
        $id = $product->id;

        if (isset($this->cart[$id])) {
            if ($this->cart[$id]['qty'] < $product->stock_quantity) {
                $this->cart[$id]['qty']++;
                $this->cart[$id]['subtotal'] = $this->cart[$id]['qty'] * $price;
            } else {
                $msg = "Only {$product->stock_quantity} {$product->unit} available for '{$product->product_name}'!";
                if ($this->showCatalogModal) {
                    session()->flash('modal_error', $msg);
                } else {
                    session()->flash('scan_error', $msg);
                }
                return;
            }
        } else {
            $this->cart[$id] = [
                'id'        => $product->id,
                'name'      => $product->product_name,
                'code'      => $product->product_code,
                'unit'      => $product->unit,
                'price'     => $price,
                'qty'       => 1,
                'subtotal'  => $price,
                'max_stock' => $product->stock_quantity,
            ];
        }

        $this->search_query = '';
        $this->recomputeBill();
    }

    // Decrement from cart
    public function decrementCartProduct($productId)
    {
        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['qty'] > 1) {
                $this->cart[$productId]['qty']--;
                $this->cart[$productId]['subtotal'] = $this->cart[$productId]['qty'] * $this->cart[$productId]['price'];
            } else {
                unset($this->cart[$productId]);
            }
            $this->recomputeBill();
        }
    }

    public function updateQuantity($id, $qty)
    {
        $qty = (int) $qty;
        if ($qty <= 0) {
            unset($this->cart[$id]);
            $this->recomputeBill();
            return;
        }

        if (isset($this->cart[$id])) {
            $max = $this->cart[$id]['max_stock'];
            $actualQty = min($qty, $max);
            $this->cart[$id]['qty'] = $actualQty;
            $this->cart[$id]['subtotal'] = $actualQty * $this->cart[$id]['price'];
        }

        $this->recomputeBill();
    }

    public function removeItem($id)
    {
        unset($this->cart[$id]);
        $this->recomputeBill();
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->recomputeBill();
    }

    // Computations
    public function getSubtotalProperty()
    {
        return array_sum(array_column($this->cart, 'subtotal'));
    }

    public function getNetPayableProperty()
    {
        $net = $this->subtotal - (float)$this->discount;
        return $net > 0 ? $net : 0;
    }

    public function recomputeBill()
    {
        $this->cash_paid = $this->netPayable;
        $this->online_paid = 0;
        $this->current_due = 0;
    }

    public function updatedDiscount() { $this->calculateRemainingDue(); }
    public function updatedCashPaid() { $this->calculateRemainingDue(); }
    public function updatedOnlinePaid() { $this->calculateRemainingDue(); }

    public function calculateRemainingDue()
    {
        $paid = (float)$this->cash_paid + (float)$this->online_paid;
        $due = $this->netPayable - $paid;
        $this->current_due = $due > 0 ? $due : 0;
    }

    // Complete Sale
    public function completeSale()
    {
        if (empty($this->cart)) {
            session()->flash('scan_error', 'Cart is empty!');
            return;
        }

        DB::beginTransaction();
        try {
            $netPayable = $this->netPayable;
            $paidTotal = (float)$this->cash_paid + (float)$this->online_paid;
            $dueTotal = (float)$this->current_due;

            $status = 'paid';
            if ($dueTotal > 0 && $paidTotal > 0) $status = 'partial';
            elseif ($dueTotal > 0 && $paidTotal == 0) $status = 'unpaid';

            $sale = Sale::create([
                'company_id'     => $this->company->id,
                'invoice_no'     => 'INV-' . date('ymd') . '-' . strtoupper(Str::random(4)),
                'customer_type'  => $this->customer_type,
                'customer_name'  => $this->customer_name ?: ($this->customer_type === 'customer' ? 'Walk-in Customer' : ucfirst($this->customer_type)),
                'customer_phone' => $this->customer_phone ?: null,
                'grand_total'    => $netPayable,
                'paid_total'     => $paidTotal,
                'due_amount'     => $dueTotal,
                'payment_status' => $status,
                'status'         => 'active',
            ]);

            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'unit_price' => $item['price'],
                    'subtotal'   => $item['subtotal'],
                ]);

                Product::where('id', $item['id'])->decrement('stock_quantity', $item['qty']);
            }

            if ((float)$this->cash_paid > 0) {
                SalePayment::create(['sale_id' => $sale->id, 'payment_method' => 'cash', 'amount' => $this->cash_paid]);
            }
            if ((float)$this->online_paid > 0) {
                SalePayment::create(['sale_id' => $sale->id, 'payment_method' => 'online', 'amount' => $this->online_paid]);
            }
            if ((float)$this->current_due > 0) {
                SalePayment::create(['sale_id' => $sale->id, 'payment_method' => 'due', 'amount' => $this->current_due]);
            }

            DB::commit();

            session()->flash('sale_success', "Bill #{$sale->invoice_no} completed successfully!");
            $this->reset(['cart', 'customer_phone', 'customer_name', 'discount', 'cash_paid', 'online_paid', 'current_due', 'previous_due', 'search_query', 'selected_user_id']);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('scan_error', 'Billing error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // 1. Live Instant Search Products
        $searchResults = [];
        if (strlen(trim($this->search_query)) >= 1) {
            $q = trim($this->search_query);
            $searchResults = Product::where('company_id', $this->company->id)
                                    ->where('stock_quantity', '>', 0)
                                    ->where(function($sub) use ($q) {
                                        $sub->where('product_name', 'like', "%{$q}%")
                                            ->orWhere('product_code', 'like', "%{$q}%");
                                    })
                                    ->take(8)
                                    ->get();
        }

        // 2. Modal Catalog Products
        $catalogProducts = [];
        if ($this->showCatalogModal) {
            $catalogProducts = Product::where('company_id', $this->company->id)
                                      ->where('stock_quantity', '>', 0)
                                      ->when($this->catalog_search, function($query) {
                                          $query->where(function($sub) {
                                              $sub->where('product_name', 'like', "%{$this->catalog_search}%")
                                                  ->orWhere('product_code', 'like', "%{$this->catalog_search}%");
                                          });
                                      })
                                      ->latest()
                                      ->get();
        }

        return view('livewire.owner.pos-billing', [
            'searchResults'   => $searchResults,
            'catalogProducts' => $catalogProducts,
        ]);
    }
}