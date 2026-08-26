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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            
            // ⭐️ 1. Core Mandatories (Onboarding Required Fields)
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile');
            $table->date('dob');
            $table->string('department');
            $table->string('designation'); // প্রফেশনাল জব টাইটেল
            $table->string('role')->default('employee'); // system access permission ('manager' or 'employee')
            $table->string('password');
            $table->string('status')->default('active');

            // 📂 2. Personal & Family Details (Optional at onboarding)
            $table->string('father_name')->nullable();
            $table->string('avatar')->nullable(); // কর্মচারীর ছবি
            $table->string('blood_group')->nullable();

            // 📍 3. Address Matrix (Optional at onboarding)
            $table->text('address')->nullable();
            $table->string('pincode', 10)->nullable();

            // 🪪 4. Identity KYC Verification (Optional at onboarding)
            $table->string('document_type')->nullable(); // Dropdown: Aadhaar, Voter, PAN, Passport
            $table->string('document_file')->nullable(); // আপলোড করা ডকুমেন্টের ফাইল পাথ

            // 🚨 5. Emergency Contact Node (Optional at onboarding)
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_relation')->nullable(); // Dropdown: Father, Mother, Spouse, etc.
            $table->string('emergency_mobile')->nullable();

            // 📅 6. ERP HR Metrics
            $table->date('joining_date')->nullable(); // অফিসিয়াল জয়েনিং ডেট
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};