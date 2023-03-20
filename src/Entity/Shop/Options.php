<?php

namespace App\Entity\Shop;

use App\Entity\Shop\Product;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Shop\OptionsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OptionsRepository::class)]
#[ORM\Table(name: 'options')]
#[ORM\HasLifecycleCallbacks]
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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Options
     */
    public function setName(string $name): Options
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return Options
     */
    public function setQuantity(int $quantity): Options
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustom(): ?string
    {
        return $this->custom;
    }

    /**
     * @param string|null $custom
     * @return Options
     */
    public function setCustom(?string $custom): Options
    {
        $this->custom = $custom;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPriceCustom(): ?float
    {
        return $this->priceCustom;
    }

    /**
     * @param float|null $priceCustom
     * @return Options
     */
    public function setPriceCustom(?float $priceCustom): Options
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

    /**
     * @param Product $product
     * @return Options
     */
    public function addProduct(Product $product): Options
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    /**
     * @param Product $product
     * @return Options
     */
    public function removeProduct(Product $product): Options
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
