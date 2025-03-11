<?php

namespace App\Service;

use App\Repository\ProductRepository;
use App\Repository\TaxRateRepository;
use App\Repository\CouponRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class PriceValidationService
{
    private ProductRepository $productRepository;
    private TaxRateRepository $taxRateRepository;
    private CouponRepository $couponRepository;

    public function __construct(
        ProductRepository $productRepository,
        TaxRateRepository $taxRateRepository,
        CouponRepository $couponRepository
    ) {
        $this->productRepository = $productRepository;
        $this->taxRateRepository = $taxRateRepository;
        $this->couponRepository = $couponRepository;
    }

    public function validate(array $data): array|JsonResponse
    {
        if (!isset($data['product']) || !is_int($data['product'])) {
            return new JsonResponse(['error' => 'Product ID is required and must be an integer.'], 400);
        }

        $product = $this->productRepository->find($data['product']);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found.'], 400);
        }

        if (empty($data['TaxNumber'])) {
            return new JsonResponse(['error' => 'TaxNumber is required.'], 400);
        }

        $taxRate = $this->taxRateRepository->findRateByTaxNumber($data['TaxNumber']);
        if (!$taxRate) {
            return new JsonResponse(['error' => 'Tax rate not found.'], 400);
        }

        $coupon = null;
        if (!empty($data['couponCode'])) {
            $coupon = $this->couponRepository->findOneBy(['code' => $data['couponCode']]);
            if (!$coupon) {
                return new JsonResponse(['error' => 'Invalid coupon code.'], 400);
            }
        }

        return [
            'product' => $product,
            'taxRate' => $taxRate,
            'coupon' => $coupon,
        ];
    }
}