<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;
use NumberFormatter;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(inversedBy: 'cartItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(name: 'sub_total', type: 'integer', options: ['default' => 0])]
    private ?int $subTotal = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getSubTotal(): ?int
    {
        return $this->subTotal;
    }

    public function getSubTotalInCurrency(string $currency = 'EUR', float $rate = 1.0): float
    {
        $priceInEuro = $this->getSubTotal() / 100;

        return $priceInEuro * $rate;
    }

    public function getFormattedSubTotalInCurrency(string $currency = 'EUR', float $rate = 1.0, string $locale = 'fr_FR'): string
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($this->getSubTotalInCurrency($currency, $rate), $currency);
    }

    public function setSubTotal(int $subTotal): static
    {
        $this->subTotal = $subTotal;

        return $this;
    }
}
