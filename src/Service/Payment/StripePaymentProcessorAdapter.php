<?php

namespace App\Service\Payment;

use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentProcessorAdapter implements PaymentProcessorInterface
{
    private StripePaymentProcessor $stripe;

    public function __construct(StripePaymentProcessor $stripe)
    {
        $this->stripe = $stripe;
    }

    public function processPayment(float $price): bool
    {
        $paymentSuccessful = $this->stripe->processPayment($price);
        if (!$paymentSuccessful) {
            throw new \RuntimeException('Ошибка при обработке платежа через Stripe: Сумма платежа слишком мала.');
        }

        return true;
    }
}