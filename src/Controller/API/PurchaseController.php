<?php

namespace App\Controller\API;

use App\Service\Product\PriceCalculatorService;
use App\Service\Product\Validation\PurchaseValidationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PurchaseController extends BaseAPIController
{
    private PurchaseValidationService $validationService;
    private PriceCalculatorService $calculatorService;

    public function __construct(
        PurchaseValidationService $validationService,
        PriceCalculatorService $calculatorService,
    )
    {
        $this->validationService = $validationService;
        $this->calculatorService = $calculatorService;
    }

    /**
     * @throws \Exception
     */
    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    public function purchase(Request $request): JsonResponse
    {
        $data = $this->handleRequest($request);
        if ($data instanceof JsonResponse) {
            return $data;
        }

        $validationResult = $this->validationService->validate($data);
        if ($validationResult instanceof JsonResponse) {
            return $validationResult;
        }

        [
            'product' => $product,
            'taxRate' => $taxRate,
            'coupon' => $coupon,
            'paymentProcessor' => $paymentProcessor
        ] = $validationResult;

        $price = $this->calculatorService->calculate($product, $taxRate, $coupon);

        return $this->json([]);
    }
}
