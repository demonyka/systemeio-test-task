<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: 'App\Repository\CouponRepository')]
#[ORM\Table(name: "product_coupons")]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $code;

    #[ORM\Column(type: "float")]
    private float $discount;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\Choice(choices: ['fixed', 'percentage'], message: 'The type must be either "fixed" or "percentage".')]
    private string $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function setDiscount(float $value): self
    {
        $this->discount = $value;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function applyCoupon(float $value): float
    {
        if ($this->type === 'fixed') {
            $newPrice = $value - $this->discount;
        } elseif ($this->type === 'percentage') {
            $newPrice = $value - ($value * $this->discount / 100);
        } else {
            throw new \InvalidArgumentException('Invalid discount type');
        }

        return max($newPrice, 0);
    }

}