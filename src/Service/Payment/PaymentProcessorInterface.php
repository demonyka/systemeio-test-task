<?php

namespace App\Service\Payment;

interface PaymentProcessorInterface
{
    public function processPayment(float $price): bool;
}