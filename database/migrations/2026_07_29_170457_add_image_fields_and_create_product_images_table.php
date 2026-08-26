<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Products টেবিলে main_image কলাম যোগ
        Schema::table('products', function (Blueprint $table) {
            $table->string('main_image')->nullable()->after('product_code');
        });

        // 2. Multiple Images এর জন্য আলাদা টেবিল
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('main_image');
        });
        Schema::dropIfExists('product_images');
    }
};