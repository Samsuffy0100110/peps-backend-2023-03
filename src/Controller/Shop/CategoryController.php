<?php

namespace App\Controller\Shop;

use App\Entity\Shop\Category;
use App\Repository\Shop\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/api/categories', name: 'categories', methods: ['GET'])]
    public function index(
        SerializerInterface $serializer,
        CategoryRepository $categoryRepository,
    ): JsonResponse {
        $categories = $serializer->serialize(
            $categoryRepository->findAll(),
            'json',
            ['groups' => ['category']]
        );
        return new JsonResponse($categories, Response::HTTP_OK, [], true);
    }

    #[Route('/api/category/{slug}', name: 'category', methods: ['GET'])]
    public function show(
        Category $category,
        SerializerInterface $serializer,
    ): JsonResponse {

            $jsonCategory = $serializer->serialize(
                $category,
                'json',
                ['groups' => ['category']]
            );

            return new JsonResponse($jsonCategory, Response::HTTP_OK, [], true);
    }

    #[Route('/api/category/create', name: 'category_create', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        CategoryRepository $categoryRepository,
    ): JsonResponse {

        $category = $serializer->deserialize(
            $request->getContent(),
            Category::class,
            'json'
        );

        $categoryRepository->save($category, true);

        $jsonCategory = $serializer->serialize(
            $category,
            'json',
            ['groups' => ['category']]
        );

        $location = $urlGenerator->generate(
            'category',
            ['slug' => $category->getSlug(),
            UrlGeneratorInterface::ABSOLUTE_URL]
        );
        return new JsonResponse($jsonCategory, Response::HTTP_CREATED, ['Location' => $location], true);
    }

    #[Route('/api/category/{slug}', name: 'category_update', methods: ['PUT'])]
    public function update(
        Request $request,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        CategoryRepository $categoryRepository,
        string $slug
    ): JsonResponse {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);
        $serializer->deserialize(
            $request->getContent(),
            Category::class,
            'json',
            ['object_to_populate' => $category]
        );
        $categoryRepository->save($category);
        return new JsonResponse(
            $serializer->serialize(
                $category,
                'json',
                ['groups' => ['category']]
            ),
            Response::HTTP_OK,
            ['Location' => $urlGenerator->generate('category', ['slug' => $category->getSlug()])],
            true
        );
    }

    #[Route('/api/category/{slug}', name: 'category_delete', methods: ['DELETE'])]
    public function delete(
        Category $category,
        CategoryRepository $categoryRepository,
    ): JsonResponse {

        $categoryRepository->remove($category);
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
