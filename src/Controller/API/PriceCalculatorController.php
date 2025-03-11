<?php

namespace App\Controller\API;

use App\Service\Product\PriceCalculatorService;
use App\Service\Product\Validation\PriceValidationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PriceCalculatorController extends BaseApiController
{
    private PriceValidationService $validationService;
    private PriceCalculatorService $calculatorService;

    public function __construct(
        PriceValidationService $validationService,
        PriceCalculatorService $calculatorService,
    ) {
        $this->validationService = $validationService;
        $this->calculatorService = $calculatorService;
    }

    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
    public function calculatePrice(Request $request): JsonResponse
    {
        $data = $this->handleRequest($request);
        if ($data instanceof JsonResponse) {
            return $data;
        }

        $validationResult = $this->validationService->validate($data);
        if ($validationResult instanceof JsonResponse) {
            return $validationResult;
        }

        ['product' => $product, 'taxRate' => $taxRate, 'coupon' => $coupon] = $validationResult;

        $response = $this->calculatorService->calculate($product, $taxRate, $coupon);

        return $this->json($response);
    }
}