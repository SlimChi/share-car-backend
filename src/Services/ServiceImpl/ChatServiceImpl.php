<?php


namespace App\Services\ServiceImpl;

use App\Entity\User;
use App\Entity\Chat;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Image;

class ChatServiceImpl
{
    private $jwtManager;
    private $tokenStorageInterface;
    private $manager;
    private $userRepository;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        TokenStorageInterface $tokenStorageInterface, 
        EntityManagerInterface $manager,
        UserRepository $userRepository
    )

    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->manager = $manager;
        $this->userRepository = $userRepository;
    }

    public function getAllUsersByUsername(): JsonResponse
    {
        $users= $this->userRepository->findAll();

        $usersByUsername = [];

        foreach ($users as $user) {
            $usersByUsername[] = [
                'id' => $user->getId(),
                'username' => $user->getUsernameProfile(),
            ];
        }

        return new JsonResponse($usersByUsername);


    }
}