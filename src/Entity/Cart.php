<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;
use NumberFormatter;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\Column(name: 'total_price', type: 'integer', options: ['default' => 0])]
    private ?int $totalPrice = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(User $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getTotalPriceInCurrency(string $currency = 'EUR', float $rate = 1.0): float
    {
        $priceInEuro = $this->getTotalPrice() / 100;

        return $priceInEuro * $rate;
    }

    public function getFormattedPriceInCurrency(string $currency = 'EUR', float $rate = 1.0, string $locale = 'fr_FR'): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($this->getTotalPriceInCurrency($currency, $rate), $currency);
    }
}
