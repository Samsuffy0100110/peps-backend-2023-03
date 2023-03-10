<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function create(
        Request $request,
        UserRepository $userRepository,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {

        $user = new User();

        /** @var User $user */
        $user = $serializer->deserialize(
            $request->getContent(),
            User::class,
            "json"
        );

        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setRoles(['ROLE_USER']);

        $userRepository->save($user, true);

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => ['user']]);

        $userHeaderLocation = $urlGenerator->generate(
            'user',
            ['id' => $user->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new JsonResponse(
            $jsonUser,
            JsonResponse::HTTP_CREATED,
            ['Location' => $userHeaderLocation],
            true
        );
    }

    #[Route('/api/login', name: 'login', methods: ['POST', 'GET'])]
    public function index(
        #[CurrentUser] ?User $user
    ): Response {

        if (!$user) {
            return new JsonResponse(
                ['error' => 'Identifiants incorrects'],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        return new JsonResponse(
            [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ]
        );
    }

    #[Route('/api/logout', name: 'api_logout', methods: ['GET', 'POST'])]
    public function logout(): void
    {
        throw new Exception('This should never be reached!');
    }
}
