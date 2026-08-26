<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 1. All Products Catalog with Search, Filter, Tabs & Pagination
public function index(Request $request, $company_slug)
{
    $company = Company::where('company_slug', $company_slug)->firstOrFail();
    
    $query = Product::where('company_id', $company->id)->with('images');

    // A. Search by Product Name or Barcode
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('product_name', 'like', "%{$search}%")
              ->orWhere('product_code', 'like', "%{$search}%");
        });
    }

    // B. Filter by Product Source (type)
    if ($request->filled('type') && in_array($request->type, ['own_production', 'purchased'])) {
        $query->where('product_type', $request->type);
    }

    // C. Tab Based Filtering (Low Stock & Expiring)
    $activeTab = $request->get('tab', 'all');
    
    if ($activeTab === 'low_stock') {
        $query->where('stock_quantity', '<=', 10);
    } elseif ($activeTab === 'expiring') {
        $today = Carbon::today()->toDateString();
        $next7Days = Carbon::today()->addDays(7)->toDateString();
        
        $query->whereNotNull('expiry_date')
              ->where(function($q) use ($today, $next7Days) {
                  $q->where('expiry_date', '<', $today) // Already expired
                    ->orWhereBetween('expiry_date', [$today, $next7Days]); // Expiring in 7 days
              });
    }

    // Pagination with query parameters appended
    $products = $query->latest()->paginate(10)->withQueryString();

    // Counts for Tabs
    $lowStockCount = Product::where('company_id', $company->id)->where('stock_quantity', '<=', 10)->count();
    
    $today = Carbon::today()->toDateString();
    $next7Days = Carbon::today()->addDays(7)->toDateString();
    $expiringCount = Product::where('company_id', $company->id)
                            ->whereNotNull('expiry_date')
                            ->where(function($q) use ($today, $next7Days) {
                                $q->where('expiry_date', '<', $today)
                                  ->orWhereBetween('expiry_date', [$today, $next7Days]);
                            })->count();

    return view('owner.products.index', compact('company_slug', 'products', 'activeTab', 'lowStockCount', 'expiringCount'));
}

    // 2. Product Create Form
    public function create($company_slug)
    {
        return view('owner.products.create', compact('company_slug'));
    }

    // 3. Store Product
    public function store(Request $request, $company_slug)
    {
        $company = Company::where('company_slug', $company_slug)->firstOrFail();

        $rules = [
            'product_name'   => 'required|string|max:255',
            'product_type'   => 'required|in:own_production,purchased',
            'pricing_type'   => 'required|in:flat,tiered',
            'stock_quantity' => 'required|integer|min:0',
            'unit'           => 'required|string',
            'expiry_date'    => 'nullable|date',
            'main_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'gallery_images.*' => 'nullable|file|max:5120',
            'gallery_images' => 'nullable|array|max:10',
        ];

        if ($request->product_type === 'purchased') {
            $rules['buying_price'] = 'required|numeric|min:0';
        }

        if ($request->pricing_type === 'flat') {
            $rules['flat_selling_price'] = 'required|numeric|min:0';
        } else {
            $rules['salesman_price'] = 'required|numeric|min:0';
            $rules['retailer_price'] = 'required|numeric|min:0';
            $rules['customer_price'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        // Upload Main Image
        $mainImagePath = null;
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('products/main', 'public');
        }

        $generatedBarcode = 'BKR' . strtoupper(Str::random(6));

        // Create Product
        $product = Product::create([
            'company_id'         => $company->id,
            'product_name'       => $request->product_name,
            'product_code'       => $generatedBarcode,
            'main_image'         => $mainImagePath,
            'product_type'       => $request->product_type,
            'buying_price'       => $request->product_type === 'purchased' ? $request->buying_price : null,
            'pricing_type'       => $request->pricing_type,
            'flat_selling_price' => $request->pricing_type === 'flat' ? $request->flat_selling_price : null,
            'salesman_price'     => $request->pricing_type === 'tiered' ? $request->salesman_price : null,
            'retailer_price'     => $request->pricing_type === 'tiered' ? $request->retailer_price : null,
            'customer_price'     => $request->pricing_type === 'tiered' ? $request->customer_price : null,
            'stock_quantity'     => $request->stock_quantity,
            'expiry_date'        => $request->expiry_date ?: null,
            'unit'               => $request->unit,
            'status'             => 'active',
        ]);

        // Upload Gallery Images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('company.owner.products.create', $company_slug)
                         ->with('success', "Product added successfully! Barcode: {$generatedBarcode}");
    }

    // 4. Show Details
    public function show($company_slug, $id)
    {
        $company = Company::where('company_slug', $company_slug)->firstOrFail();
        $product = Product::where('company_id', $company->id)->with('images')->findOrFail($id);

        return view('owner.products.show', compact('company_slug', 'product'));
    }

    // 5. Edit Form
    public function edit($company_slug, $id)
    {
        $company = Company::where('company_slug', $company_slug)->firstOrFail();
        $product = Product::where('company_id', $company->id)->findOrFail($id);

        return view('owner.products.edit', compact('company_slug', 'product'));
    }

public function update(Request $request, $company_slug, $id)
{
    $company = Company::where('company_slug', $company_slug)->firstOrFail();
    $product = Product::where('company_id', $company->id)->findOrFail($id);

    $rules = [
        'product_name'   => 'required|string|max:255',
        'stock_quantity' => 'required|integer|min:0',
        'unit'           => 'required|string',
        'expiry_date'    => 'nullable|date',
        'main_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        'gallery_images.*' => 'nullable|file|max:5120',
        'gallery_images' => 'nullable|array|max:10',
        'deleted_gallery_ids' => 'nullable|array',
    ];

    if ($product->product_type === 'purchased') {
        $rules['buying_price'] = 'required|numeric|min:0';
    }

    if ($product->pricing_type === 'flat') {
        $rules['flat_selling_price'] = 'required|numeric|min:0';
    } else {
        $rules['salesman_price'] = 'required|numeric|min:0';
        $rules['retailer_price'] = 'required|numeric|min:0';
        $rules['customer_price'] = 'required|numeric|min:0';
    }

    $request->validate($rules);

    // Track Changes
    $changes = [];

    // 1. Check Text Data Changes
    if ($product->product_name !== $request->product_name) {
        $changes[] = "Product name updated";
    }
    if ((int)$product->stock_quantity !== (int)$request->stock_quantity) {
        $changes[] = "Stock quantity updated to {$request->stock_quantity} " . strtoupper($request->unit);
    }
    if ($product->unit !== $request->unit && !in_array("Stock quantity updated", $changes)) {
        $changes[] = "Measurement unit updated to " . strtoupper($request->unit);
    }
    if ($product->expiry_date !== $request->expiry_date) {
        $changes[] = "Expiry date updated";
    }

    // Pricing Changes Tracking
    if ($product->product_type === 'purchased' && (float)$product->buying_price !== (float)$request->buying_price) {
        $changes[] = "Buying price updated";
    }
    if ($product->pricing_type === 'flat' && (float)$product->flat_selling_price !== (float)$request->flat_selling_price) {
        $changes[] = "Selling price updated to ₹" . number_format($request->flat_selling_price, 2);
    } elseif ($product->pricing_type === 'tiered') {
        if ((float)$product->salesman_price !== (float)$request->salesman_price ||
            (float)$product->retailer_price !== (float)$request->retailer_price ||
            (float)$product->customer_price !== (float)$request->customer_price) {
            $changes[] = "Price rates updated";
        }
    }

    // 2. Delete Gallery Images
    if ($request->has('deleted_gallery_ids') && is_array($request->deleted_gallery_ids) && count($request->deleted_gallery_ids) > 0) {
        $imagesToDelete = ProductImage::where('product_id', $product->id)
                                      ->whereIn('id', $request->deleted_gallery_ids)
                                      ->get();

        foreach ($imagesToDelete as $img) {
            if ($img->image_path && Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
            $img->delete();
        }
        $changes[] = "Gallery images removed";
    }

    // 3. Main Image Upload / Replace
    if ($request->hasFile('main_image')) {
        if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
            Storage::disk('public')->delete($product->main_image);
        }
        $product->main_image = $request->file('main_image')->store('products/main', 'public');
        $changes[] = "Main cover image updated";
    }

    // Update Text Data in DB
    $product->update([
        'product_name'       => $request->product_name,
        'buying_price'       => $product->product_type === 'purchased' ? $request->buying_price : null,
        'flat_selling_price' => $product->pricing_type === 'flat' ? $request->flat_selling_price : null,
        'salesman_price'     => $product->pricing_type === 'tiered' ? $request->salesman_price : null,
        'retailer_price'     => $product->pricing_type === 'tiered' ? $request->retailer_price : null,
        'customer_price'     => $product->pricing_type === 'tiered' ? $request->customer_price : null,
        'stock_quantity'     => $request->stock_quantity,
        'expiry_date'        => $request->expiry_date ?: null,
        'unit'               => $request->unit,
    ]);

    // 4. Add New Gallery Images
    if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $image) {
            $path = $image->store('products/gallery', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
            ]);
        }
        $changes[] = "New gallery images added";
    }

    // Construct Response Message
    if (count($changes) === 1) {
        $finalMessage = "Success: " . $changes[0] . ".";
    } elseif (count($changes) > 1) {
        $finalMessage = "Product details & inventory updated successfully!";
    } else {
        $finalMessage = "Product updated with no major changes.";
    }

    return redirect()->route('company.owner.products.show', [$company_slug, $product->id])
                     ->with('success', $finalMessage);
}

// 2. NEW METHOD: Delete Single Gallery Image
public function destroyImage($company_slug, $id, $image_id)
{
    $company = Company::where('company_slug', $company_slug)->firstOrFail();
    $product = Product::where('company_id', $company->id)->findOrFail($id);
    $galleryImage = ProductImage::where('product_id', $product->id)->findOrFail($image_id);

    if ($galleryImage->image_path && Storage::disk('public')->exists($galleryImage->image_path)) {
        Storage::disk('public')->delete($galleryImage->image_path);
    }
    
    $galleryImage->delete();

    return redirect()->back()->with('success', 'Gallery image deleted successfully!');
}

    // 7. Print Barcode
    public function printBarcode($company_slug, $id)
    {
        $company = Company::where('company_slug', $company_slug)->firstOrFail();
        $product = Product::where('company_id', $company->id)->findOrFail($id);

        return view('owner.products.print-barcode', compact('company_slug', 'product'));
    }

    // 2. Destroy Method with Product Name in Response
    public function destroy($company_slug, $id)
    {
        $company = Company::where('company_slug', $company_slug)->firstOrFail();
        $product = Product::where('company_id', $company->id)->findOrFail($id);
    
        $productName = $product->product_name;
        $product->delete();

    return redirect()->route('company.owner.products.index', $company_slug)
                     ->with('success', "Product '{$productName}' deleted successfully!");
    }
}