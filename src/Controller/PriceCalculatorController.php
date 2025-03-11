<?php

namespace App\Controller;

use App\Service\PriceCalculatorService;
use App\Service\PriceValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PriceCalculatorController extends AbstractController
{
    private PriceValidationService $priceValidation;
    private PriceCalculatorService $priceCalculator;

    public function __construct(
        PriceValidationService $priceValidation,
        PriceCalculatorService $priceCalculator
    ) {
        $this->priceValidation = $priceValidation;
        $this->priceCalculator = $priceCalculator;
    }

    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
    public function calculatePrice(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $validationResult = $this->priceValidation->validate($data);
        if ($validationResult instanceof JsonResponse) {
            return $validationResult;
        }

        ['product' => $product, 'taxRate' => $taxRate, 'coupon' => $coupon] = $validationResult;

        $response = $this->priceCalculator->calculate($product, $taxRate, $coupon);

        return $this->json($response);
    }
}