<?php

namespace App\Services;

class BarcodeService
{
    /**
     * ইন-হাউস EAN-13 বারকোড জেনারেট করা
     * Prefix: 20, Company: 3 digits, Product: 7 digits + 1 Check Digit
     */
    public static function generateEAN13(int $companyId, int $productId): string
    {
        $prefix = "20";
        $companyCode = str_pad((string)$companyId, 3, '0', STR_PAD_LEFT);
        $productCode = str_pad((string)$productId, 7, '0', STR_PAD_LEFT);

        // ১২ ডিজিটের বেস কোড
        $baseCode = $prefix . $companyCode . $productCode;

        // ১৩তম চেক ডিজিট ক্যালকুলেশন (GS1 Modulo-10 Algorithm)
        $checksum = self::calculateChecksum($baseCode);

        return $baseCode . $checksum;
    }

    /**
     * GS1 Modulo 10 Check Digit Calculator
     */
    private static function calculateChecksum(string $digits): int
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $val = (int)$digits[$i];
            // বিজোড় ইনডেক্স (0, 2, 4...) গুণ ১, জোড় ইনডেক্স (1, 3, 5...) গুণ ৩
            $sum += ($i % 2 === 0) ? $val * 1 : $val * 3;
        }

        $remainder = $sum % 10;
        return ($remainder === 0) ? 0 : 10 - $remainder;
    }
}