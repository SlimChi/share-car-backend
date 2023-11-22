<?php

namespace App\Entity;

use App\Repository\StepRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
 
#[ApiResource]
#[ORM\Entity(repositoryClass: StepRepository::class)]
class Step
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $departure_address = null;

    #[ORM\Column]
    private ?int $departure_zip_code = null;

    #[ORM\Column(length: 255)]
    private ?string $departure_city = null;

    #[ORM\Column(length: 255)]
    private ?string $arrival_address = null;

    #[ORM\Column]
    private ?int $arrival_zip_code = null;

    #[ORM\Column(length: 255)]
    private ?string $arrival_city = null;

   

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trip $trip = null;

    public function getId(): ?int
    {
        return $this->id;
    }

  
    public function getDepartureAddress(): ?string
    {
        return $this->departure_address;
    }

    public function setDepartureAddress(string $departure_address): static
    {
        $this->departure_address = $departure_address;

        return $this;
    }

    public function getDepartureZipCode(): ?int
    {
        return $this->departure_zip_code;
    }

    public function setDepartureZipCode(int $departure_zip_code): static
    {
        $this->departure_zip_code = $departure_zip_code;

        return $this;
    }

    public function getDepartureCity(): ?string
    {
        return $this->departure_city;
    }

    public function setDepartureCity(string $departure_city): static
    {
        $this->departure_city = $departure_city;

        return $this;
    }
    public function getArrivalAddress(): ?string
    {
        return $this->arrival_address;
    }

    public function setArrivalAddress(string $arrival_address): static
    {
        $this->arrival_address = $arrival_address;

        return $this;
    }

    public function getArrivalZipCode(): ?int
    {
        return $this->arrival_zip_code;
    }

    public function setArrivalZipCode(int $arrival_zip_code): static
    {
        $this->arrival_zip_code = $arrival_zip_code;

        return $this;
    }

    public function getArrivalCity(): ?string
    {
        return $this->arrival_city;
    }

    public function setArrivalCity(string $arrival_city): static
    {
        $this->arrival_city = $arrival_city;

        return $this;
    }

    

    public function getTrip(): ?Trip
    {
        return $this->trip;
    }

    public function setTrip(?Trip $trip): static
    {
        $this->trip = $trip;

        return $this;

        
    }

    
}
