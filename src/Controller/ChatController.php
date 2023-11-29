<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\ChatServiceInterface;

class ChatController extends AbstractController
{
    private ChatServiceInterface $chatService;

    public function __construct(ChatServiceInterface $chatService)
    {
        $this->chatService = $chatService;
    }

    #[Route('/api/chats', name: 'create_chat', methods: ['POST'])]
    public function createChat(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $senderId = $this->getUser()->getId();

        $result = $this->chatService->createChat($data, $senderId);

        if ($result['status']) {
            return $this->json($result['data'], JsonResponse::HTTP_CREATED);
        } else {
            return $this->json(['error' => $result['message']], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/chats/sent', name: 'get_sent_chats', methods: ['GET'])]
    public function getSentChats(): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $result = $this->chatService->getSentChats($userId);

        if ($result['status']) {
            return $this->json($result['data'], JsonResponse::HTTP_OK);
        } else {
            return $this->json(['error' => $result['message']], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/chats/received', name: 'get_received_chats', methods: ['GET'])]
    public function getReceivedChats(): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $result = $this->chatService->getReceivedChats($userId);

        if ($result['status']) {
            return $this->json($result['data'], JsonResponse::HTTP_OK);
        } else {
            return $this->json(['error' => $result['message']], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/chats/{recipientId}', name: 'get_chats', methods: ['GET'])]
    public function getChats($recipientId): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $result = $this->chatService->getChats($userId, $recipientId);

        if ($result['status']) {
            return $this->json($result['data'], JsonResponse::HTTP_OK);
        } else {
            return $this->json(['error' => $result['message']], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/chats/delete/all', name: 'delete_all_chats', methods: ['DELETE'])]
    public function deleteAllChats(): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $result = $this->chatService->deleteAllChats($userId);

        if ($result['status']) {
            return $this->json(['message' => $result['message']], JsonResponse::HTTP_OK);
        } else {
            return $this->json(['error' => $result['message']], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/chats/delete/{id}', name: 'delete_chat_by_id', methods: ['DELETE'])]
    public function deleteChatById($id): JsonResponse
    {
        $userId = $this->getUser()->getId();
        $result = $this->chatService->deleteChatById($userId, $id);

        if ($result['status']) {
            return $this->json(['message' => $result['message']], JsonResponse::HTTP_OK);
        } else {
            return $this->json(['error' => $result['message']], JsonResponse::HTTP_BAD_REQUEST);
        }
    }
}
