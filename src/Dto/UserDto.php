<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $firstName = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $lastName = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    private ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $password = null;

    #[Assert\NotBlank]
    #[Assert\DateTime]
    private ?\DateTimeInterface $createdAt = null;

    #[Assert\Length(max: 255)]
    private ?string $username = null;

    #[Assert\NotNull]
    private ?int $creditCoin = null;

    #[Assert\NotNull]
    private array $roles = [];

    #[Assert\Length(max: 255)]
    private ?string $address = null;

    #[Assert\Length(max: 255)]
    private ?string $zipCode = null;

    #[Assert\Length(max: 255)]
    private ?string $city = null;

    #[Assert\Date]
    private ?string $dateOfBirth = null;

    #[Assert\Length(max: 255)]
    private ?string $confirmationToken = null;

    #[Assert\Length(max: 255)]
    private ?string $resetPasswordToken = null;

    #[Assert\NotNull]
    private bool $enabled = false;

    #[Assert\Length(max: 255)]
    private ?string $biography = null;

    public function toArray(): array
    {
        return [
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'createdAt' => $this->getCreatedAt(),
            'username' => $this->getUsername(),
            'creditCoin' => $this->getCreditCoin(),
            'roles' => $this->getRoles(),
            'address' => $this->getAddress(),
            'zipCode' => $this->getZipCode(),
            'city' => $this->getCity(),
            'dateOfBirth' => $this->getDateOfBirth(),
            'confirmationToken' => $this->getConfirmationToken(),
            'resetPasswordToken' => $this->getResetPasswordToken(),
            'enabled' => $this->getEnabled(),
            'biography' => $this->getBiography(),
        ];
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

 
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getCreditCoin(): ?int
    {
        return $this->creditCoin;
    }

    public function setCreditCoin(?int $creditCoin): self
    {
        $this->creditCoin = $creditCoin;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?string $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $resetPasswordToken): self
    {
        $this->resetPasswordToken = $resetPasswordToken;
        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): self
    {
        $this->biography = $biography;
        return $this;
    }
}

