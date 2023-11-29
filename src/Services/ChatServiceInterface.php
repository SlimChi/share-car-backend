<?php
namespace App\Services;

interface ChatServiceInterface
{
    public function createChat(array $data, $senderId): array;

    public function getSentChats($userId): array;

    public function getReceivedChats($userId): array;

    public function getChats($userId, $recipientId): array;

    public function deleteAllChats($userId): array;

    public function deleteChatById($userId, $chatId): array;
}