<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PurchaseValidationDTO
{
    #[Assert\NotBlank(message: "Product ID is required")]
    #[Assert\Type(type: "integer", message: "Product ID must be an integer")]
    public ?int $product = null;

    #[Assert\NotBlank(message: "TaxNumber is required")]
    #[Assert\Type(type: "string", message: "TaxNumber must be a string")]
    public ?string $TaxNumber = null;

    #[Assert\Type(type: "string", message: "couponCode must be a string")]
    public ?string $couponCode = null;

    #[Assert\NotBlank(message: "paymentProcessor is required")]
    #[Assert\Type(type: "string", message: "paymentProcessor must be a string")]
    #[Assert\Choice(choices: ['paypal', 'stripe'], message: "paymentProcessor must be one of: paypal, stripe")]
    public ?string $paymentProcessor = null;
}