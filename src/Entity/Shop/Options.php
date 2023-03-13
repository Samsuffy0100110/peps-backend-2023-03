<?php

namespace App\Entity\Shop;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Shop\OptionsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: OptionsRepository::class)]
#[ORM\Table(name: 'options')]
#[UniqueEntity('name')]
class Options
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['product'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['product'])]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['product'])]
    private ?string $custom = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['product'])]
    private ?float $priceCustom = null;

    #[ORM\ManyToMany(
        targetEntity: Product::class,
        inversedBy: 'options',
        cascade: ['persist', 'remove']
    )]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCustom(): ?string
    {
        return $this->custom;
    }

    public function setCustom(?string $custom): self
    {
        $this->custom = $custom;

        return $this;
    }

    public function getPriceCustom(): ?float
    {
        return $this->priceCustom;
    }

    public function setPriceCustom(?float $priceCustom): self
    {
        $this->priceCustom = $priceCustom / 100;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
