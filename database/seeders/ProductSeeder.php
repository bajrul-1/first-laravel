<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ১ম কোম্পানি বা নির্দিষ্ট কোম্পানি খুঁজে বের করা
        $company = Company::first();

        if (!$company) {
            $this->command->error("No Company found! Please create or seed a company first.");
            return;
        }

        $products = [
            // 🥖 Bakery Own Production (Tiered Pricing)
            [
                'product_name'       => 'White Sandwich Bread',
                'product_type'       => 'own_production',
                'pricing_type'       => 'tiered',
                'buying_price'       => null,
                'flat_selling_price' => null,
                'salesman_price'     => 28.00,
                'retailer_price'     => 32.00,
                'customer_price'     => 40.00,
                'stock_quantity'     => 50,
                'unit'               => 'pkt',
                'expiry_date'        => Carbon::now()->addDays(5)->toDateString(),
            ],
            [
                'product_name'       => 'Brown Whole Wheat Bread',
                'product_type'       => 'own_production',
                'pricing_type'       => 'tiered',
                'buying_price'       => null,
                'flat_selling_price' => null,
                'salesman_price'     => 35.00,
                'retailer_price'     => 40.00,
                'customer_price'     => 50.00,
                'stock_quantity'     => 30,
                'unit'               => 'pkt',
                'expiry_date'        => Carbon::now()->addDays(4)->toDateString(),
            ],
            [
                'product_name'       => 'French Baguette',
                'product_type'       => 'own_production',
                'pricing_type'       => 'flat',
                'buying_price'       => null,
                'flat_selling_price' => 60.00,
                'salesman_price'     => null,
                'retailer_price'     => null,
                'customer_price'     => null,
                'stock_quantity'     => 15,
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(3)->toDateString(),
            ],
            [
                'product_name'       => 'Butter Croissant',
                'product_type'       => 'own_production',
                'pricing_type'       => 'flat',
                'buying_price'       => null,
                'flat_selling_price' => 45.00,
                'salesman_price'     => null,
                'retailer_price'     => null,
                'customer_price'     => null,
                'stock_quantity'     => 25,
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(2)->toDateString(),
            ],
            [
                'product_name'       => 'Chocolate Muffin',
                'product_type'       => 'own_production',
                'pricing_type'       => 'tiered',
                'buying_price'       => null,
                'flat_selling_price' => null,
                'salesman_price'     => 20.00,
                'retailer_price'     => 25.00,
                'customer_price'     => 35.00,
                'stock_quantity'     => 40,
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(6)->toDateString(),
            ],
            [
                'product_name'       => 'Vanilla Dry Cake (250g)',
                'product_type'       => 'own_production',
                'pricing_type'       => 'tiered',
                'buying_price'       => null,
                'flat_selling_price' => null,
                'salesman_price'     => 70.00,
                'retailer_price'     => 80.00,
                'customer_price'     => 100.00,
                'stock_quantity'     => 20,
                'unit'               => 'pkt',
                'expiry_date'        => Carbon::now()->addDays(15)->toDateString(),
            ],
            [
                'product_name'       => 'Fruit Toast Biscuits (200g)',
                'product_type'       => 'own_production',
                'pricing_type'       => 'flat',
                'buying_price'       => null,
                'flat_selling_price' => 55.00,
                'salesman_price'     => null,
                'retailer_price'     => null,
                'customer_price'     => null,
                'stock_quantity'     => 60,
                'unit'               => 'pkt',
                'expiry_date'        => Carbon::now()->addDays(30)->toDateString(),
            ],
            [
                'product_name'       => 'Crispy Garlic Rusk',
                'product_type'       => 'own_production',
                'pricing_type'       => 'tiered',
                'buying_price'       => null,
                'flat_selling_price' => null,
                'salesman_price'     => 30.00,
                'retailer_price'     => 35.00,
                'customer_price'     => 45.00,
                'stock_quantity'     => 8, // Low Stock Example
                'unit'               => 'pkt',
                'expiry_date'        => Carbon::now()->addDays(20)->toDateString(),
            ],
            [
                'product_name'       => 'Black Forest Pastry',
                'product_type'       => 'own_production',
                'pricing_type'       => 'flat',
                'buying_price'       => null,
                'flat_selling_price' => 70.00,
                'salesman_price'     => null,
                'retailer_price'     => null,
                'customer_price'     => null,
                'stock_quantity'     => 12,
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(2)->toDateString(),
            ],
            [
                'product_name'       => 'Red Velvet Pastry',
                'product_type'       => 'own_production',
                'pricing_type'       => 'flat',
                'buying_price'       => null,
                'flat_selling_price' => 85.00,
                'salesman_price'     => null,
                'retailer_price'     => null,
                'customer_price'     => null,
                'stock_quantity'     => 10,
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->subDay()->toDateString(), // Expired Example
            ],

            // 🛒 Purchased / Trading Items (Buying Price Included)
            [
                'product_name'       => ' Amul Butter (100g)',
                'product_type'       => 'purchased',
                'pricing_type'       => 'flat',
                'buying_price'       => 48.00,
                'flat_selling_price' => 56.00,
                'salesman_price'     => null,
                'retailer_price'     => null,
                'customer_price'     => null,
                'stock_quantity'     => 45,
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(40)->toDateString(),
            ],
            [
                'product_name'       => 'Cadbury Dairy Milk Silk',
                'product_type'       => 'purchased',
                'pricing_type'       => 'flat',
                'buying_price'       => 145.00,
                'flat_selling_price' => 175.00,
                'salesman_price'     => null,
                'retailer_price'     => null,
                'customer_price'     => null,
                'stock_quantity'     => 25,
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(90)->toDateString(),
            ],
            [
                'product_name'       => 'Coca-Cola 500ml Bottle',
                'product_type'       => 'purchased',
                'pricing_type'       => 'tiered',
                'buying_price'       => 30.00,
                'flat_selling_price' => null,
                'salesman_price'     => 33.00,
                'retailer_price'     => 35.00,
                'customer_price'     => 40.00,
                'stock_quantity'     => 100,
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(60)->toDateString(),
            ],
            [
                'product_name'       => 'Sprite 500ml Bottle',
                'product_type'       => 'purchased',
                'pricing_type'       => 'tiered',
                'buying_price'       => 30.00,
                'flat_selling_price' => null,
                'salesman_price'     => 33.00,
                'retailer_price'     => 35.00,
                'customer_price'     => 40.00,
                'stock_quantity'     => 5, // Low Stock Example
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(60)->toDateString(),
            ],
            [
                'product_name'       => 'Real Mango Juice 1L',
                'product_type'       => 'purchased',
                'pricing_type'       => 'flat',
                'buying_price'       => 90.00,
                'flat_selling_price' => 110.00,
                'salesman_price'     => null,
                'retailer_price'     => null,
                'customer_price'     => null,
                'stock_quantity'     => 18,
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(25)->toDateString(),
            ],
            [
                'product_name'       => 'Nutella Hazelnut Spread (350g)',
                'product_type'       => 'purchased',
                'pricing_type'       => 'flat',
                'buying_price'       => 320.00,
                'flat_selling_price' => 380.00,
                'salesman_price'     => null,
                'retailer_price'     => null,
                'customer_price'     => null,
                'stock_quantity'     => 10,
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(120)->toDateString(),
            ],
            [
                'product_name'       => 'Kissan Mixed Fruit Jam (500g)',
                'product_type'       => 'purchased',
                'pricing_type'       => 'tiered',
                'buying_price'       => 120.00,
                'flat_selling_price' => null,
                'salesman_price'     => 130.00,
                'retailer_price'     => 140.00,
                'customer_price'     => 160.00,
                'stock_quantity'     => 15,
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(180)->toDateString(),
            ],
            [
                'product_name'       => 'Lays Potato Chips (India Masala)',
                'product_type'       => 'purchased',
                'pricing_type'       => 'flat',
                'buying_price'       => 16.00,
                'flat_selling_price' => 20.00,
                'salesman_price'     => null,
                'retailer_price'     => null,
                'customer_price'     => null,
                'stock_quantity'     => 80,
                'unit'               => 'pkt',
                'expiry_date'        => Carbon::now()->addDays(45)->toDateString(),
            ],
            [
                'product_name'       => 'Nescafe Classic Instant Coffee (50g)',
                'product_type'       => 'purchased',
                'pricing_type'       => 'flat',
                'buying_price'       => 135.00,
                'flat_selling_price' => 160.00,
                'salesman_price'     => null,
                'retailer_price'     => null,
                'customer_price'     => null,
                'stock_quantity'     => 0, // Out of Stock Example
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(200)->toDateString(),
            ],
            [
                'product_name'       => 'Dairy Milk Fruit & Nut (80g)',
                'product_type'       => 'purchased',
                'pricing_type'       => 'flat',
                'buying_price'       => 70.00,
                'flat_selling_price' => 90.00,
                'salesman_price'     => null,
                'retailer_price'     => null,
                'customer_price'     => null,
                'stock_quantity'     => 35,
                'unit'               => 'pcs',
                'expiry_date'        => Carbon::now()->addDays(3)->toDateString(), // Expiring Soon Example
            ],
        ];

        foreach ($products as $item) {
            Product::create([
                'company_id'         => $company->id,
                'product_name'       => $item['product_name'],
                'product_code'       => 'BKR' . strtoupper(Str::random(6)),
                'main_image'         => null, // Image Excluded
                'product_type'       => $item['product_type'],
                'buying_price'       => $item['buying_price'],
                'pricing_type'       => $item['pricing_type'],
                'flat_selling_price' => $item['flat_selling_price'],
                'salesman_price'     => $item['salesman_price'],
                'retailer_price'     => $item['retailer_price'],
                'customer_price'     => $item['customer_price'],
                'stock_quantity'     => $item['stock_quantity'],
                'expiry_date'        => $item['expiry_date'],
                'unit'               => $item['unit'],
                'status'             => 'active',
            ]);
        }

        $this->command->info('20 Bakery Products seeded successfully!');
    }
}