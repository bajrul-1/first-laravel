<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
        // 🚀 কর্মচারীরাও সরাসরি কোম্পানির আন্ডারে থাকবেন
        $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
        $table->string('company_name');
        $table->string('company_slug');
        
        // কর্মচারীর প্রোফাইল ও কাজের তথ্য
        $table->string('name');
        $table->string('email')->unique();
        $table->string('mobile')->unique();
        $table->date('dob');
        $table->string('department'); // Management & Accounts, Kitchen, etc.
        $table->string('category');   // Manager, Head Chef, Salesman, etc.
        $table->string('role')->default('employee'); // manager, employee (সিস্টেম অ্যাক্সেস রোল)
        
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
