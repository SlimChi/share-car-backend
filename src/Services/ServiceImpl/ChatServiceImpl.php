<?php

namespace App\Services\ServiceImpl;

use App\Entity\Chat;
use App\Entity\User;
use App\Dto\UserChatDto;
use App\Mapper\UserMapper;
use App\Repository\ChatRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\ChatServiceInterface;

class ChatServiceImpl implements ChatServiceInterface
{
    private EntityManagerInterface $entityManager;
    private UserMapper $userMapper;
    private ChatRepository $chatRepository;

    public function __construct(EntityManagerInterface $entityManager, UserMapper $userMapper, ChatRepository $chatRepository)
    {
        $this->entityManager = $entityManager;
        $this->userMapper = $userMapper;
        $this->chatRepository = $chatRepository;
    }

    public function createChat(array $data, $senderId): array
    {
        $sender = $this->entityManager->getRepository(User::class)->find($senderId);
        $recipient = $this->entityManager->getRepository(User::class)->find($data['recipientId']);

        if (!$recipient) {
            return ['status' => false, 'message' => 'Recipient not found'];
        }

        $chat = new Chat();
        $chat->setSender($sender);
        $chat->setRecipient($recipient);
        $chat->setMessage($data['message']);

        $this->entityManager->persist($chat);
        $this->entityManager->flush();

        $chatDto = $this->userMapper->convertEntityToUserChatDto($chat);

        return ['status' => true, 'data' => $chatDto];
    }

    public function getSentChats($userId): array
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return ['status' => false, 'message' => 'User not found'];
        }

        $sentChats = $user->getSentChats();
        $sentChatsDto = $this->userMapper->convertChatCollectionToUserChatDtoCollection($sentChats);

        return ['status' => true, 'data' => $sentChatsDto];
    }

    public function getReceivedChats($userId): array
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return ['status' => false, 'message' => 'User not found'];
        }

        $receivedChats = $user->getReceivedChats();
        $receivedChatsDto = $this->userMapper->convertChatCollectionToUserChatDtoCollection($receivedChats);

        return ['status' => true, 'data' => $receivedChatsDto];
    }

    public function getChats($userId, $recipientId): array
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        $recipient = $this->entityManager->getRepository(User::class)->find($recipientId);

        if (!$user || !$recipient) {
            return ['status' => false, 'message' => 'User or recipient not found'];
        }

        $chats = $this->chatRepository->findBy([
            'sender' => $user,
            'recipient' => $recipient,
        ]);

        $chatsDto = $this->userMapper->convertChatCollectionToUserChatDtoCollection($chats);

        return ['status' => true, 'data' => $chatsDto];
    }

    public function deleteAllChats($userId): array
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return ['status' => false, 'message' => 'User not found'];
        }

        $this->chatRepository->deleteAllChatsForUser($user);

        return ['status' => true, 'message' => 'All chats deleted successfully'];
    }

    public function deleteChatById($userId, $chatId): array
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return ['status' => false, 'message' => 'User not found'];
        }

        $chat = $this->chatRepository->findChatByIdForUser($chatId, $user);

        if (!$chat) {
            return ['status' => false, 'message' => 'Chat not found'];
        }

        $this->entityManager->remove($chat);
        $this->entityManager->flush();

        return ['status' => true, 'message' => 'Chat deleted successfully'];
    }
}
