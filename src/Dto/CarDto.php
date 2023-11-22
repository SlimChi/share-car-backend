<?php

namespace App\Dto;

final class CarDto
{
    private ?int $id;
    private ?int $number_of_places;
    private ?int $number_of_small_bags;
    private ?int $number_of_big_bags;
    private ?UserDto $user;
    private ?ModelsDto $models;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNumberOfPlaces(): ?int
    {
        return $this->number_of_places;
    }

    public function setNumberOfPlaces(?int $number_of_places): void
    {
        $this->number_of_places = $number_of_places;
    }

    public function getNumberOfSmallBags(): ?int
    {
        return $this->number_of_small_bags;
    }

    public function setNumberOfSmallBags(?int $number_of_small_bags): void
    {
        $this->number_of_small_bags = $number_of_small_bags;
    }

    public function getNumberOfBigBags(): ?int
    {
        return $this->number_of_big_bags;
    }

    public function setNumberOfBigBags(?int $number_of_big_bags): void
    {
        $this->number_of_big_bags = $number_of_big_bags;
    }

    public function getUser(): ?UserDto
    {
        return $this->user;
    }

    public function setUser(?UserDto $user): void
    {
        $this->user = $user;
    }

    public function getModels(): ?ModelsDto
    {
        return $this->models;
    }

    public function setModels(?ModelsDto $models): void
    {
        $this->models = $models;
    }
}
