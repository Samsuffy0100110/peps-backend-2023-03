<?php

namespace App\Entity\Shop;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\Shop\ImagesProductRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ImagesProductRepository::class)]
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

    #[ORM\ManyToOne(inversedBy: 'imagesProducts')]
    private ?Product $product = null;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
