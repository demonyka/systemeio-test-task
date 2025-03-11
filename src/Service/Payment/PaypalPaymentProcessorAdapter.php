<?php

namespace App\Service\Payment;

use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalPaymentProcessorAdapter implements PaymentProcessorInterface
{
    private PaypalPaymentProcessor $paypal;

    public function __construct(PaypalPaymentProcessor $paypal)
    {
        $this->paypal = $paypal;
    }

    public function processPayment(float $price): bool
    {
        try {
            $amountInCents = (int) round($price * 100);
            $this->paypal->pay($amountInCents);
            return true;
        } catch (Exception $e) {
            throw new \RuntimeException('Ошибка при обработке платежа через Paypal: ' . $e->getMessage());
        }
    }
}