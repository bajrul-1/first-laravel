<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    // 1. POS View Screen
    public function index($company_slug)
    {
        $company = Company::where('company_slug', $company_slug)->firstOrFail();
        $products = Product::where('company_id', $company->id)
                            ->where('stock_quantity', '>', 0)
                            ->get();

        return view('owner.pos.index', compact('company_slug', 'products'));
    }

    // 2. Process Sale with Split Payment & Stock Deduct
    public function store(Request $request, $company_slug)
    {
        $company = Company::where('company_slug', $company_slug)->firstOrFail();

        $request->validate([
            'customer_type'  => 'required|in:salesman,retailer,customer',
            'customer_name'  => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'cart'           => 'required|array|min:1',
            'payments'       => 'required|array|min:1', // Cash, Online, Due array
        ]);

        DB::beginTransaction();
        try {
            $grandTotal = 0;
            $cartItems = [];

            // A. Calculate Cart Items & Check Stock Availability
            foreach ($request->cart as $item) {
                $product = Product::where('company_id', $company->id)->findOrFail($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for: {$product->product_name} (Available: {$product->stock_quantity})"
                    ], 422);
                }

                // Customer Specific Price Logic
                $unitPrice = 0;
                if ($product->pricing_type === 'flat') {
                    $unitPrice = $product->flat_selling_price;
                } else {
                    if ($request->customer_type === 'salesman') {
                        $unitPrice = $product->salesman_price;
                    } elseif ($request->customer_type === 'retailer') {
                        $unitPrice = $product->retailer_price;
                    } else {
                        $unitPrice = $product->customer_price;
                    }
                }

                $subtotal = $unitPrice * $item['quantity'];
                $grandTotal += $subtotal;

                $cartItems[] = [
                    'product'    => $product,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal'   => $subtotal,
                ];
            }

            // B. Calculate Split Payments (Cash, Online, Due)
            $paidTotal = 0;
            $dueAmount = 0;
            $validPayments = [];

            foreach ($request->payments as $pm) {
                $amt = (float) $pm['amount'];
                if ($amt > 0) {
                    if ($pm['method'] === 'due') {
                        $dueAmount += $amt;
                    } else {
                        $paidTotal += $amt;
                    }

                    $validPayments[] = [
                        'method' => $pm['method'],
                        'amount' => $amt,
                        'ref'    => $pm['reference'] ?? null
                    ];
                }
            }

            // Determine Payment Status
            $paymentStatus = 'paid';
            if ($dueAmount > 0 && $paidTotal > 0) {
                $paymentStatus = 'partial';
            } elseif ($dueAmount > 0 && $paidTotal == 0) {
                $paymentStatus = 'unpaid';
            }

            // C. Create Master Sale Record
            $invoiceNo = 'INV-' . strtoupper(Str::random(8));
            $sale = Sale::create([
                'company_id'     => $company->id,
                'invoice_no'     => $invoiceNo,
                'customer_type'  => $request->customer_type,
                'customer_name'  => $request->customer_name ?: ucfirst($request->customer_type),
                'customer_phone' => $request->customer_phone,
                'grand_total'    => $grandTotal,
                'paid_total'     => $paidTotal,
                'due_amount'     => $dueAmount,
                'payment_status' => $paymentStatus,
                'status'         => 'active',
            ]);

            // D. Save Sale Items and Deduct Stock Quantity
            foreach ($cartItems as $cItem) {
                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $cItem['product']->id,
                    'quantity'   => $cItem['quantity'],
                    'unit_price' => $cItem['unit_price'],
                    'subtotal'   => $cItem['subtotal'],
                ]);

                // Deduct Product Stock
                $cItem['product']->decrement('stock_quantity', $cItem['quantity']);
            }

            // E. Save Payment Split Entries
            foreach ($validPayments as $pEntry) {
                SalePayment::create([
                    'sale_id'               => $sale->id,
                    'payment_method'        => $pEntry['method'],
                    'amount'                => $pEntry['amount'],
                    'transaction_reference' => $pEntry['ref'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale processed successfully!',
                'sale_id' => $sale->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 3. CANCEL BILL & AUTOMATICALLY RESTORE STOCK QUANTITY
    public function cancelSale($company_slug, $sale_id)
    {
        $company = Company::where('company_slug', $company_slug)->firstOrFail();
        
        DB::beginTransaction();
        try {
            $sale = Sale::where('company_id', $company->id)->with('items')->findOrFail($sale_id);

            if ($sale->status === 'cancelled') {
                return response()->json(['success' => false, 'message' => 'This invoice is already cancelled.'], 400);
            }

            // A. Restore Product Stock Quantity
            foreach ($sale->items as $item) {
                Product::where('id', $item->product_id)
                       ->increment('stock_quantity', $item->quantity);
            }

            // B. Update Sale Status
            $sale->update([
                'status'         => 'cancelled',
                'payment_status' => 'cancelled'
            ]);

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => "Invoice {$sale->invoice_no} cancelled & stock restored successfully!"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}