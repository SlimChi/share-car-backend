<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\TrajetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
 
#[ApiResource]
#[ORM\Entity(repositoryClass: TrajetRepository::class)]
class Trajet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column]
    private ?bool $fumeur = null;

    #[ORM\Column]
    private ?bool $silence = null;

    #[ORM\Column]
    private ?bool $musique = null;

    #[ORM\Column]
    private ?bool $animaux = null;

    #[ORM\Column]
    private ?string $date_depart = null;

    #[ORM\Column]
    private ?string $heure_depart = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Voiture $voiture = null;
   
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\OneToMany(mappedBy: 'trajet', targetEntity: Etape::class, orphanRemoval: true)]
    private Collection $etapes;

    public function __construct()
    {
        $this->etapes = new ArrayCollection();
    }

    public function getEtapes(): Collection
    {
        return $this->etapes;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function isFumeur(): ?bool
    {
        return $this->fumeur;
    }

    public function setFumeur(bool $fumeur): static
    {
        $this->fumeur = $fumeur;

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

    public function isMusique(): ?bool
    {
        return $this->musique;
    }

    public function setMusique(bool $musique): static
    {
        $this->musique = $musique;

        return $this;
    }

    public function isAnimaux(): ?bool
    {
        return $this->animaux;
    }

    public function setAnimaux(bool $animaux): static
    {
        $this->animaux = $animaux;

        return $this;
    }

    public function getDateDepart(): ?string
    {
        return $this->date_depart;
    }

    public function setDateDepart(string $date_depart): static
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    public function getHeureDepart(): ?string
    {
        return $this->heure_depart;
    }

    public function setHeureDepart(string $heure_depart): static
    {
        $this->heure_depart = $heure_depart;

        return $this;
    }

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(?Voiture $voiture): static
    {
        $this->voiture = $voiture;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
{
    $this->utilisateur = $utilisateur;

    return $this;
}

}