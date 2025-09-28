<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, CartItem>
     */
    #[ORM\OneToMany(targetEntity: CartItem::class, mappedBy: 'cart', orphanRemoval: true)]
    private Collection $cartItems;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

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

    public function hasItems(): bool
    {
        return !$this->cartItems->isEmpty();
    }

/**
 * @return Collection<int, CartItem>
 */
public function getCartItems(): Collection
{
    return $this->cartItems;
}

public function addCartItem(CartItem $cartItem): static
{
    if (!$this->cartItems->contains($cartItem)) {
        $this->cartItems->add($cartItem);
        $cartItem->setCart($this);
    }

    return $this;
}

public function removeCartItem(CartItem $cartItem): static
{
    if ($this->cartItems->removeElement($cartItem)) {
        // set the owning side to null (unless already changed)
        if ($cartItem->getCart() === $this) {
            $cartItem->setCart(null);
        }
    }

    return $this;
}
}
