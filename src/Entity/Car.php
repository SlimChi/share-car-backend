<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
 
#[ApiResource]
#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number_of_places = null;

    #[ORM\Column]
    private ?int $number_of_small_bags = null;

    #[ORM\Column]
    private ?int $number_of_big_bags = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Models $models = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberOfPlaces(): ?int
    {
        return $this->number_of_places;
    }

    public function setNumberOfPlaces(int $number_of_places): static
    {
        $this->number_of_places = $number_of_places;

        return $this;
    }

    public function getNumberOfSmallBags(): ?int
    {
        return $this->number_of_small_bags;
    }

    public function setNumberOfSmallBags(int $number_of_small_bags): static
    {
        $this->number_of_small_bags = $number_of_small_bags;

        return $this;
    }

    public function getNumberOfBigBags(): ?int
    {
        return $this->number_of_big_bags;
    }

    public function setNumberOfBigBags(int $number_of_big_bags): static
    {
        $this->number_of_big_bags = $number_of_big_bags;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
    public function getModels(): ?Models
    {
        return $this->models;
    }

    public function setModels(?Models $models): static
    {
        $this->models = $models;

        return $this;
    }
}
