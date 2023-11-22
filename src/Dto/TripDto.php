<?php

namespace App\Dto;

class TripDto
{
    public  ?int $id;
    public  ?int $price;
    public  ?bool $smoker;
    public  ?bool $silence;
    public  ?bool $music;
    public  ?bool $pets;
    public  ?string $departure_date;
    public  ?string $departure_time;
    public  ?int $carId;
    public  ?int $userId;

    public array $steps;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): void
    {
        $this->price = $price;
    }

    public function isSmoker(): ?bool
    {
        return $this->smoker;
    }

    public function setSmoker(?bool $smoker): void
    {
        $this->smoker = $smoker;
    }

    public function isSilence(): ?bool
    {
        return $this->silence;
    }

    public function setSilence(?bool $silence): void
    {
        $this->silence = $silence;
    }

    public function isMusic(): ?bool
    {
        return $this->music;
    }

    public function setMusic(?bool $music): void
    {
        $this->music = $music;
    }

    public function isPets(): ?bool
    {
        return $this->pets;
    }

    public function setPets(?bool $pets): void
    {
        $this->pets = $pets;
    }

    public function getDepartureDate(): ?string
    {
        return $this->departure_date;
    }

    public function setDepartureDate(?string $departure_date): void
    {
        $this->departure_date = $departure_date;
    }

    public function getDepartureTime(): ?string
    {
        return $this->departure_time;
    }

    public function setDepartureTime(?string $departure_time): void
    {
        $this->departure_time = $departure_time;
    }

    public function getCarId(): ?int
    {
        return $this->carId;
    }

    public function setCarId(?int $carId): void
    {
        $this->carId = $carId;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function setSteps(array $steps): void
    {
        $this->steps = $steps;
    }
}