<?php

namespace App\Service\Product;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\TaxRate;

class PriceCalculatorService
{
    public function calculate(Product $product, TaxRate $taxRate, ?Coupon $coupon = null): float
    {
        $originalPrice = $product->getPrice();
        $finalPrice = $coupon ? $coupon->applyCoupon($originalPrice) : $originalPrice;
        return $taxRate->applyTax($finalPrice);
    }
}
