<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->string('product_code')->unique(); // বারকোড
            
            // 🚀 ১. প্রোডাক্ট সোর্স টাইপ
            $table->enum('product_type', ['own_production', 'purchased'])->default('own_production');
            
            // 🚀 ২. কিনে আনা মালের জন্য কেনা দাম
            $table->decimal('buying_price', 10, 2)->nullable();
            
            // 🚀 ৩. প্রাইসিং টাইপ (ডিফারেন্ট রেট নাকি সবার জন্য ফিক্সড রেট)
            $table->enum('pricing_type', ['flat', 'tiered'])->default('flat');
            
            // 🚀 ৪. সেলস রেট ক্যাটাগরি
            $table->decimal('flat_selling_price', 10, 2)->nullable(); // সবার জন্য একই দাম হলে
            $table->decimal('salesman_price', 10, 2)->nullable();     // সেলসম্যানের রেট
            $table->decimal('retailer_price', 10, 2)->nullable();     // রিটেইলার/পাইকারি রেট
            $table->decimal('customer_price', 10, 2)->nullable();     // সরাসরি কাস্টমার/MRP রেট
            
            // স্টক এবং মেয়াদ
            $table->integer('stock_quantity')->default(0);
            $table->date('expiry_date')->nullable();
            $table->string('unit')->default('pcs'); // pcs, pkt, kg
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};