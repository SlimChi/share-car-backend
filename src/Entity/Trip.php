<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\TripRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
 
#[ApiResource]
#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?bool $smoker = null;

    #[ORM\Column]
    private ?bool $silence = null;

    #[ORM\Column]
    private ?bool $music = null;

    #[ORM\Column]
    private ?bool $pets = null;

    #[ORM\Column]
    private ?string $departure_date = null;

    #[ORM\Column]
    private ?string $departure_time = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;
   
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: Step::class, orphanRemoval: true)]
    private Collection $steps;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
    }
    

    public function getSteps(): Collection
    {
        return $this->steps;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isSmoker(): ?bool
    {
        return $this->smoker;
    }

    public function setSmoker(bool $smoker): static
    {
        $this->smoker = $smoker;

        return $this;
    }

    public function isSilence(): ?bool
    {
        return $this->silence;
    }

    public function setSilence(bool $silence): static
    {
        $this->silence = $silence;

        return $this;
    }

    public function isMusic(): ?bool
    {
        return $this->music;
    }

    public function setMusic(bool $music): static
    {
        $this->music = $music;

        return $this;
    }

    public function isPets(): ?bool
    {
        return $this->pets;
    }

    public function setPets(bool $pets): static
    {
        $this->pets = $pets;

        return $this;
    }

    public function getDepartureDate(): ?string
    {
        return $this->departure_date;
    }

    public function setDepartureDate(string $departure_date): static
    {
        $this->departure_date = $departure_date;

        return $this;
    }

    public function getDepartureTime(): ?string
    {
        return $this->departure_time;
    }

    public function setDepartureTime(string $departure_time): static
    {
        $this->departure_time = $departure_time;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

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

public function addStep(Step $step): self
{
    if (!$this->steps->contains($step)) {
        $this->steps[] = $step;
        $step->setTrip($this);
    }

    return $this;
}

}