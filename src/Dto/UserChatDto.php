<?php

namespace App\Dto;

class UserChatDto
{
    private ?int $id = null;
    private ?string $message = null;
    private ?int $recipientId = null;
    private ?string $recipientUsername = null;
    private $createdAt; 
    private ?int $senderId = null;
    public function __construct($id, $message, $recipientId, $recipientUsername, $createdAt, $senderId)
    {
        $this->id = $id;
        $this->message = $message;
        $this->recipientId = $recipientId;
        $this->recipientUsername = $recipientUsername;
        $this->createdAt = $createdAt;
        $this->senderId = $senderId; 
    }

    public function getSenderId(): ?int
    {
        return $this->senderId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    public function getRecipientId(): ?int
    {
        return $this->recipientId;
    }

    public function getRecipientUsername(): ?string
    {
        return $this->recipientUsername;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;
        return $this;
    }
    

    public function setRecipientId(?int $recipientId): self
    {
        $this->recipientId = $recipientId;
        return $this;
    }

    public function setRecipientUsername(?string $recipientUsername): self
    {
        $this->recipientUsername = $recipientUsername;
        return $this;
    }
}
