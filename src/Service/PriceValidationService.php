<?php

namespace App\Service;

use App\Repository\ProductRepository;
use App\Repository\TaxRateRepository;
use App\Repository\CouponRepository;
use App\DTO\PriceValidationDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PriceValidationService
{
    private ProductRepository $productRepository;
    private TaxRateRepository $taxRateRepository;
    private CouponRepository $couponRepository;
    private ValidatorInterface $validator;

    public function __construct(
        ProductRepository $productRepository,
        TaxRateRepository $taxRateRepository,
        CouponRepository $couponRepository,
        ValidatorInterface $validator
    ) {
        $this->productRepository = $productRepository;
        $this->taxRateRepository = $taxRateRepository;
        $this->couponRepository = $couponRepository;
        $this->validator = $validator;
    }

    public function validate(array $data): array|JsonResponse
    {
        $dto = new PriceValidationDTO();
        $dto->product = $data['product'] ?? null;
        $dto->TaxNumber = $data['TaxNumber'] ?? null;
        $dto->couponCode = $data['couponCode'] ?? null;

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 422);
        }

        $product = $this->productRepository->find($dto->product);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found.'], 422);
        }

        $taxRate = $this->taxRateRepository->findRateByTaxNumber($dto->TaxNumber);
        if (!$taxRate) {
            return new JsonResponse(['error' => 'Tax rate not found.'], 422);
        }

        $coupon = null;
        if ($dto->couponCode) {
            $coupon = $this->couponRepository->findOneBy(['code' => $dto->couponCode]);
            if (!$coupon) {
                return new JsonResponse(['error' => 'Invalid coupon code.'], 422);
            }
        }

        return [
            'product' => $product,
            'taxRate' => $taxRate,
            'coupon' => $coupon,
        ];
    }
}