<?php

namespace App\Service\Product\Validation;

use App\DTO\PriceValidationDTO;
use Symfony\Component\HttpFoundation\JsonResponse;

class PriceValidationService extends BaseValidationService
{
    public function validate(array $data): array|JsonResponse
    {
        $dto = new PriceValidationDTO();
        $dto->product = $data['product'] ?? null;
        $dto->TaxNumber = $data['TaxNumber'] ?? null;
        $dto->couponCode = $data['couponCode'] ?? null;

        return $this->validateCommonData($dto);
    }
}