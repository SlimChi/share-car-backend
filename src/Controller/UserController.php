<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    private JWTTokenManagerInterface $jwtTokenManager;
    private TokenStorageInterface $tokenStorageInterface;

    public function __construct(JWTTokenManagerInterface $jwtTokenManager, TokenStorageInterface $tokenStorageInterface)
    {
        $this->jwtTokenManager = $jwtTokenManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    #[Route('/api/get_users_chat', name: 'user_list_chat', methods: ['GET'])]
    public function indexChat(UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        try {
            // Récupérer le token JWT depuis la requête
            $token = $this->tokenStorageInterface->getToken();
            $user = $token->getUser();

            // Récupérer tous les utilisateurs sauf l'utilisateur connecté
            $users = $userRepository->findAllExceptCurrentUser($user->getId());

            $normalizedUsers = [];
            foreach ($users as $user) {
                $normalizedUsers[] = [
                    'id' => $user->getId(),
                    'lastName' => $user->getLastName(),
                    'firstName' => $user->getFirstName(),
                    'email' => $user->getEmail(),
                    'createdAt' => $user->getCreatedAt()->format(\DateTime::RFC3339),
                    'username' => $user->getUsername(),
                    'creditCoin' => $user->getCreditCoin(),
                    'address' => $user->getAddress(),
                ];
            }

            $data = $serializer->serialize($normalizedUsers, 'json');

            $response = new JsonResponse($data, Response::HTTP_OK, [], true);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } catch (JWTDecodeFailureException $e) {
            // Gérer les erreurs de décodage JWT
            return new JsonResponse(['error' => 'Invalid JWT token'], Response::HTTP_UNAUTHORIZED);
        }
    }

    #[Route('/api/get_users', name: 'user_list', methods: ['GET'])]
    public function index(UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $users = $userRepository->findAll();

        $normalizedUsers = [];
        foreach ($users as $user) {
            $normalizedUsers[] = [
                'id' => $user->getId(),
                'lastName' => $user->getLastName(),
                'firstName' => $user->getFirstName(),
                'email' => $user->getEmail(),
                'createdAt' => $user->getCreatedAt()->format(\DateTime::RFC3339),
                'username' => $user->getUsername(),
                'creditCoin' => $user->getCreditCoin(),
                'address' => $user->getAddress(),
            ];
        }

        $data = $serializer->serialize($normalizedUsers, 'json');

        $response = new JsonResponse($data, Response::HTTP_OK, [], true);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
