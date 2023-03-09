<?php

namespace App\Controller\Shop;

use DateTimeImmutable;
use App\Entity\Shop\Product;
use App\Repository\Shop\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'products', methods: ['GET'])]
    public function index(
        SerializerInterface $serializer,
        ProductRepository $productRepository,
    ): JsonResponse {

        $products = $serializer->serialize(
            $productRepository->findAll(),
            'json',
            ['groups' => ['product']]
        );

        return new JsonResponse($products, Response::HTTP_OK, [], true);
    }

    #[Route('/api/product/{slug}', name: 'product', methods: ['GET'])]
    public function show(
        Product $product,
        SerializerInterface $serializer,
    ): JsonResponse {

        $jsonProduct = $serializer->serialize(
            $product,
            'json',
            ['groups' => ['product']]
        );

        return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
    }

    #[Route('/api/product/create', name: 'product_create', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        ProductRepository $productRepository,
    ): JsonResponse {

        $product = $serializer->deserialize(
            $request->getContent(),
            Product::class,
            'json'
        );

        $product->setReleaseAt(new DateTimeImmutable());
        $productRepository->save($product, true);

        $jsonProduct = $serializer->serialize(
            $product,
            'json',
            ['groups' => ['product']]
        );

        $location = $urlGenerator->generate(
            'product',
            ['slug' => $product->getSlug()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new JsonResponse($jsonProduct, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    #[Route('/api/product/{slug}', name: 'product_update', methods: ['PUT'])]
    public function update(
        Product $product,
        Request $request,
        SerializerInterface $serializer,
        ProductRepository $productRepository,
    ): JsonResponse {

        $product = $serializer->deserialize(
            $request->getContent(),
            Product::class,
            'json',
            ['object_to_populate' => $product]
        );

        $productRepository->save($product, true);

        $jsonProduct = $serializer->serialize(
            $product,
            'json',
            ['groups' => ['product']]
        );

        return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
    }

    #[Route('/api/product/{slug}', name: 'product_delete', methods: ['DELETE'])]
    public function delete(
        Product $product,
        ProductRepository $productRepository,
    ): JsonResponse {

        $productRepository->remove($product);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
