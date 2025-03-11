<?php
namespace App\Controller\API;

use App\Service\Payment\PaymentProcessorFactory;
use App\Service\Product\PriceCalculatorService;
use App\Service\Product\Validation\PurchaseValidationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use RuntimeException;

class PurchaseController extends BaseAPIController
{
    private PurchaseValidationService $validationService;
    private PriceCalculatorService $calculatorService;

    public function __construct(
        PurchaseValidationService $validationService,
        PriceCalculatorService $calculatorService,
    ) {
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
            'paymentProcessor' => $paymentProcessorName
        ] = $validationResult;

        $price = $this->calculatorService->calculate($product, $taxRate, $coupon);


        try {
            $processor = PaymentProcessorFactory::create($paymentProcessorName);
            $processor->processPayment($price);
        } catch (RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => 'Неизвестный процессор: ' . $e->getMessage()], 400);
        }

        return $this->json(['message' => 'Покупка успешно оформлена']);
    }
}