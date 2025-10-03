<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use NumberFormatter;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $total_amount = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalAmount(): ?int
    {
        return $this->total_amount;
    }

    public function getTotalAmountInCurrency(string $currency = 'EUR', float $rate = 1.0): float
    {
        $priceInEuro = $this->getTotalAmount() / 100;

        return $priceInEuro * $rate;
    }

    public function getFormattedPriceInCurrency(string $currency = 'EUR', float $rate = 1.0, string $locale = 'fr_FR'): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($this->getTotalAmountInCurrency($currency, $rate), $currency);
    }

    public function setTotalAmount(int $total_amount): static
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function hasItems(): bool
    {
        // This is a placeholder implementation.
        // In a real application, you would check if the order has associated items.
        return true;
    }
}
