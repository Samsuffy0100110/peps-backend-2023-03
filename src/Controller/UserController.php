<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/api/users', name: 'users', methods: ['GET'])]
    public function index(
        UserRepository $userRepository,
        SerializerInterface $serializer
    ): JsonResponse {
        // On récupère tous les utilisateurs
        $users = $userRepository->findAll();
        /* On les sérialise en JSON, serializerInterface est un service qui permet de sérialiser et désérialiser
        des objets en JSON pour les envoyer dans une réponse HTTP
        On utilise le groupe 'user' pour sérialiser les données de l'entité User
        On peut créer plusieurs groupes pour sérialiser des données différentes */
        $result = $serializer->serialize($users, 'json', [
            'groups' => ['user']
        ]);
        // On retourne une réponse HTTP avec le code 200 (OK) et le JSON des utilisateurs
        return new JsonResponse($result, 200, [], true);
    }

    #[Route('/api/user/{id}', name: 'user', methods: ['GET'])]
    public function show(
        User $user,
        SerializerInterface $serializer
    ): JsonResponse {
        // On sérialise l'utilisateur en JSON
        $result = $serializer->serialize($user, 'json', [
            'groups' => ['user']
        ]);
        // On retourne une réponse HTTP avec le code 200 (OK) et le JSON de l'utilisateur
        return new JsonResponse($result, Response::HTTP_OK, [], true);
    }

    #[Route('/api/user/{id}', name: 'user_update', methods: ['PUT'])]
    public function update(
        User $user,
        Request $request,
        UserRepository $userRepository,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        // On désérialise le JSON reçu dans une entité User pour pouvoir l'enregistrer en base de données
        /** @var User $user */
        $user = $serializer->deserialize(
            // On récupère le contenu de la requête HTTP
            $request->getContent(),
            // On indique l'entité User
            User::class,
            // On indique le format de la requête HTTP
            "json",
            // On indique que l'on veut hydrater l'entité User avec les données de la requête HTTP
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        );
        /* On hash le mot de passe de l'utilisateur et on set le password de l'entité User,
        on récupère le mot de passe en clair dans l'entité User */
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        // On enregistre l'utilisateur en base de données
        $userRepository->save($user, true);
        // On sérialise l'utilisateur en JSON
        $jsonUser = $serializer->serialize($user, 'json', ['groups' => ['user']]);
        // On génère l'URL de l'utilisateur pour la mettre dans le header Location de la réponse HTTP
        $userHeaderLocation = $urlGenerator->generate(
            'user_show',
            ['id' => $user->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        // On retourne une réponse HTTP avec le code 200 (OK) et le JSON de l'utilisateur
        return new JsonResponse($jsonUser, Response::HTTP_OK, ['Location' => $userHeaderLocation], true);
    }

    #[Route('/api/user/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(
        User $user,
        UserRepository $userRepository
    ): JsonResponse {
        // On supprime l'utilisateur en base de données
        $userRepository->remove($user);
        // On retourne une réponse HTTP avec le code 204 (No Content)
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
