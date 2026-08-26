<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('invoice_no')->unique();
            $table->enum('customer_type', ['salesman', 'retailer', 'customer'])->default('customer');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            
            // Amount Details
            $table->decimal('grand_total', 10, 2);
            $table->decimal('paid_total', 10, 2)->default(0.00);
            $table->decimal('due_amount', 10, 2)->default(0.00);
            
            // Status Tracking
            $table->enum('payment_status', ['paid', 'partial', 'unpaid', 'cancelled'])->default('paid');
            $table->enum('status', ['active', 'cancelled'])->default('active');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};