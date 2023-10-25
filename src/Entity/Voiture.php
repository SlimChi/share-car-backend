<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
 
#[ApiResource]
#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbre_de_places = null;

    #[ORM\Column]
    private ?int $nbre_petits_bagages = null;

    #[ORM\Column]
    private ?int $nbre_grands_bagages = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Modeles $modeles = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbreDePlaces(): ?int
    {
        return $this->nbre_de_places;
    }

    public function setNbreDePlaces(int $nbre_de_places): static
    {
        $this->nbre_de_places = $nbre_de_places;

        return $this;
    }

    public function getNbrePetitsBagages(): ?int
    {
        return $this->nbre_petits_bagages;
    }

    public function setNbrePetitsBagages(int $nbre_petits_bagages): static
    {
        $this->nbre_petits_bagages = $nbre_petits_bagages;

        return $this;
    }

    public function getNbreGrandsBagages(): ?int
    {
        return $this->nbre_grands_bagages;
    }

    public function setNbreGrandsBagages(int $nbre_grands_bagages): static
    {
        $this->nbre_grands_bagages = $nbre_grands_bagages;

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
    public function getModeles(): ?Modeles
    {
        return $this->modeles;
    }

    public function setModeles(?Modeles $modeles): static
    {
        $this->modeles = $modeles;

        return $this;
    }
}
