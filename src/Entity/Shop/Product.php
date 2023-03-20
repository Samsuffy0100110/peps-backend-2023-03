<?php

namespace App\Entity\Shop;

use DateTimeImmutable;
use App\Entity\Shop\Options;
use App\Entity\Shop\Category;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Shop\ImagesProduct;
use App\Repository\Shop\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'product')]
#[UniqueEntity('slug')]
#[UniqueEntity('name')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[Groups(['product'])]
    #[Assert\NotBlank(message: 'Le nom du produit est obligatoire.')]
    private string $name;

    #[ORM\Column(nullable: true)]
    #[Groups(['product'])]
    #[Assert\NotBlank(message: 'Le prix du produit est obligatoire.')]
    private float $price;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['product'])]
    private ?string $image = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[Groups(['product'])]
    #[Assert\NotBlank(message: 'Le slug du produit est obligatoire.')]
    private string $slug;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['product'])]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['product'])]
    #[Assert\NotNull]
    private DateTimeImmutable $releaseAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull]
    private DateTimeImmutable $updatedAt;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Category $category = null;

    #[ORM\OneToMany(
        mappedBy: 'product',
        targetEntity: ImagesProduct::class,
        orphanRemoval: true,
        cascade: ['persist', 'remove']
    )]
    #[Groups(['product'])]
    private Collection $imagesProducts;

    #[ORM\ManyToMany(
        targetEntity: Options::class,
        mappedBy: 'products',
        cascade: ['persist', 'remove']
    )]
    #[Groups(['product'])]
    private Collection $options;

    public function __construct()
    {
        $this->releaseAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->imagesProducts = new ArrayCollection();
        $this->options = new ArrayCollection();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Product
     */
    public function setPrice(float $price): Product
    {
        $this->price = $price / 100;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return Product
     */
    public function setImage(?string $image): Product
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Product
     */
    public function setSlug(string $slug): Product
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return Product
     */
    public function setCategory(?Category $category): Product
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getReleaseAt(): DateTimeImmutable
    {
        return $this->releaseAt;
    }

    /**
     * @param DateTimeImmutable $releaseAt
     * @return Product
     */
    public function setReleaseAt(DateTimeImmutable $releaseAt): Product
    {
        $this->releaseAt = $releaseAt;

        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeImmutable $updatedAt
     * @return Product
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): Product
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Product
     */
    public function setDescription(?string $description): Product
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, ImagesProduct>
     */
    public function getImagesProducts(): Collection
    {
        return $this->imagesProducts;
    }

    /**
     * @param ImagesProduct $imagesProduct
     * @return Product
     */
    public function addImagesProduct(ImagesProduct $imagesProduct): Product
    {
        if (!$this->imagesProducts->contains($imagesProduct)) {
            $this->imagesProducts->add($imagesProduct);
            $imagesProduct->setProduct($this);
        }

        return $this;
    }

    /**
     * @param ImagesProduct $imagesProduct
     * @return Product
     */
    public function removeImagesProduct(ImagesProduct $imagesProduct): Product
    {
        if ($this->imagesProducts->removeElement($imagesProduct)) {
            // set the owning side to null (unless already changed)
            if ($imagesProduct->getProduct() === $this) {
                $imagesProduct->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Options>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    /**
     * @param Options $option
     * @return Product
     */
    public function addOption(Options $option): Product
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->addProduct($this);
        }

        return $this;
    }

    /**
     * @param Options $option
     * @return Product
     */
    public function removeOption(Options $option): Product
    {
        if ($this->options->removeElement($option)) {
            $option->removeProduct($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
