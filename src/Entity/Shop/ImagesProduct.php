<?php

namespace App\Entity\Shop;

use DateTimeImmutable;
use App\Entity\Shop\Product;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\Shop\ImagesProductRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ImagesProductRepository::class)]
#[ORM\Table(name: 'images_product')]
#[ORM\HasLifecycleCallbacks]
class ImagesProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product'])]
    private ?string $name = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    #[ORM\ManyToOne(inversedBy: 'imagesProducts')]
    private ?Product $product = null;

    public function __construct()
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
    // function for delete image when delete product from easy admin
    #[ORM\PostRemove]
    public function postRemove(): void
    {
        if ($this->name) {
            unlink('images/products/' . $this->name);
        }
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
     * @return ImagesProduct
     */
    public function setName(string $name): ImagesProduct
    {
        $this->name = $name;

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
     * @return ImagesProduct
     */
    public function setUpdatedAt(DateTimeImmutable $updatedAt): ImagesProduct
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return ImagesProduct
     */
    public function setProduct(?Product $product): ImagesProduct
    {
        $this->product = $product;

        return $this;
    }
}
