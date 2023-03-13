<?php

namespace App\Entity\Shop;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Shop\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
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

    #[ORM\Column(length: 100)]
    #[Groups(['product'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['product'])]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['product'])]
    private ?string $image = null;

    #[ORM\Column(length: 100)]
    #[Groups(['product'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['product'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Category $category = null;

    #[ORM\Column]
    #[Groups(['product'])]
    private ?\DateTimeImmutable $releaseAt = null;

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
        $this->imagesProducts = new ArrayCollection();
        $this->options = new ArrayCollection();
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

    /**
     * Get the value of price
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */
    public function setPrice(float $price): self
    {
        $this->price = $price / 100;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getReleaseAt(): ?\DateTimeImmutable
    {
        return $this->releaseAt;
    }

    public function setReleaseAt(\DateTimeImmutable $releaseAt): self
    {
        $this->releaseAt = $releaseAt;

        return $this;
    }

    /**
     * @return Collection<int, ImagesProduct>
     */
    public function getImagesProducts(): Collection
    {
        return $this->imagesProducts;
    }

    public function addImagesProduct(ImagesProduct $imagesProduct): self
    {
        if (!$this->imagesProducts->contains($imagesProduct)) {
            $this->imagesProducts->add($imagesProduct);
            $imagesProduct->setProduct($this);
        }

        return $this;
    }

    public function removeImagesProduct(ImagesProduct $imagesProduct): self
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

    public function addOption(Options $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->addProduct($this);
        }

        return $this;
    }

    public function removeOption(Options $option): self
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
