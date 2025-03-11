<?php

namespace App\Service\Payment;

use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class PaymentProcessorFactory
{
    /**
     * Создаёт экземпляр PaymentProcessorInterface на основании имени процессора.
     *
     * @param string $processorName
     * @return PaymentProcessorInterface
     * @throws \InvalidArgumentException если процессор неизвестен
     */
    public static function create(string $processorName): PaymentProcessorInterface
    {
        return match (strtolower($processorName)) {
            'paypal' => new PaypalPaymentProcessorAdapter(new PaypalPaymentProcessor()),
            'stripe' => new StripePaymentProcessorAdapter(new StripePaymentProcessor()),
            default => throw new \InvalidArgumentException("Неизвестный процессор: {$processorName}"),
        };
    }
}