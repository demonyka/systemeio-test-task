<?php

namespace App\Service\Product;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\TaxRate;

class PriceCalculatorService
{
    public function calculate(Product $product, TaxRate $taxRate, ?Coupon $coupon = null): array
    {
        $originalPrice = $product->getPrice();
        $discountValue = $coupon ? $coupon->applyCoupon($originalPrice) - $originalPrice : 0;
        $finalPrice = $coupon ? $coupon->applyCoupon($originalPrice) : $originalPrice;
        $finalPrice = $taxRate->applyTax($finalPrice);

        return [
            'productName' => $product->getName(),
            'originalPrice' => $originalPrice,
            'discount' => abs($discountValue),
            'tax' => $taxRate->getRate(),
            'finalPrice' => max($finalPrice, 0),
        ];
    }
}
