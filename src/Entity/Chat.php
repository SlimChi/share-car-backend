<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(normalizationContext: ['groups' => ['chat:read']], denormalizationContext: ['groups' => ['chat:write']])]
#[ORM\Entity(repositoryClass: ChatRepository::class)]
#[UniqueConstraint(name: 'unique_chat', columns: ['sender_id', 'recipient_id'])]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['chat:read'])]
    private ?int $id;

    #[ORM\Column]
    #[Groups(['chat:read', 'chat:write'])]
    private ?string $message;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['chat:read', 'chat:write'])] 
    private ?\DateTimeInterface $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['chat:read'])]
    private ?User $sender;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['chat:read'])]
    private ?User $recipient;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $value)
    {
        $this->message = $value;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $value)
    {
        $this->id = $value;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $value)
    {
        $this->createdAt = $value;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): void
    {
        $this->sender = $sender;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): void
    {
        $this->recipient = $recipient;
    }


}