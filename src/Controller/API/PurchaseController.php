<?php

namespace App\Controller\API;

use App\Service\Product\Validation\PurchaseValidationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PurchaseController extends BaseAPIController
{
    private PurchaseValidationService $validationService;

    public function __construct(PurchaseValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

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

        return $this->json([]);
    }
}