<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Chat;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use App\Services\ServiceImpl\ChatServiceImpl;

class ChatController extends AbstractController
{
    private $chatService;

    public function __construct (ChatServiceImpl $chatService)
    {
        $this->chatService = $chatService;
    }

    #[Route('/api/chat_list_all_users', name: 'app_chat_list_all_users', methods: ['GET'])]
    public function getAllUsersByUsername(): Response
    {
        return $this->chatService->getAllUsersByUsername();
    }
}