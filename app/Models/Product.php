<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'product_name',
        'product_code',
        'main_image',
        'product_type',
        'buying_price',
        'pricing_type',
        'flat_selling_price',
        'salesman_price',
        'retailer_price',
        'customer_price',
        'stock_quantity',
        'expiry_date',
        'unit',
        'status',
    ];

    /**
     * Boot Method for Model Events
     */
    protected static function booted(): void
    {
        static::deleting(function ($product) {
            // 1. Delete Main Cover Image from Storage
            if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
                Storage::disk('public')->delete($product->main_image);
            }

            // 2. Delete All Gallery Images from Storage & Database
            foreach ($product->images as $image) {
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }
        });
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}