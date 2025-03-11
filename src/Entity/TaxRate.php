<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: 'App\Repository\TaxRateRepository')]
#[ORM\Table(name: "tax_rates")]
class TaxRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 2, unique: true)]
    #[Assert\Choice(choices: ['DE', 'IT', 'GR', 'FR'], message: 'The country code must be one of: DE, IT, GR, FR.')]
    private string $countryCode;

    #[ORM\Column(type: "float")]
    private float $rate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;
        return $this;
    }

    public function applyTax(float $value): float
    {
        return $value + ($value * $this->rate / 100);
    }
}