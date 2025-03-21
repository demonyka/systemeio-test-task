<?php

namespace App\Service\Product\Validation;

use App\DTO\PurchaseValidationDTO;
use Symfony\Component\HttpFoundation\JsonResponse;

class PurchaseValidationService extends BaseValidationService
{
    public function validate(array $data): array|JsonResponse
    {
        $dto = new PurchaseValidationDTO();
        $dto->product = $data['product'] ?? null;
        $dto->TaxNumber = $data['TaxNumber'] ?? null;
        $dto->couponCode = $data['couponCode'] ?? null;
        $dto->paymentProcessor = $data['paymentProcessor'] ?? null;

        $result = $this->validateCommonData($dto);

        if ($result instanceof JsonResponse) {
            return $result;
        }

        $result['paymentProcessor'] = $dto->paymentProcessor;

        return $result;
    }
}