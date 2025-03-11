<?php

namespace App\Service\Payment;

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
            'paypal' => new PaypalPaymentProcessorAdapter(),
            'stripe' => new StripePaymentProcessorAdapter(),
            default => throw new \InvalidArgumentException("Неизвестный процессор: {$processorName}"),
        };
    }
}