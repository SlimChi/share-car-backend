<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UtilisateurController extends AbstractController

{
    private $manager;
    private $utilisateur;

    public function __construct(EntityManagerInterface $manager, UtilisateurRepository $utilisateur)
    {
         $this->manager=$manager;

         $this->utilisateur=$utilisateur;
    }

    #[Route('/utilisateurs_liste', name: 'utilisateurs_liste', methods: ['GET'])]
    public function utilisateur(): Response
    {
        $utilisateurs=$this->manager->getRepository(Utilisateur::class)->findAll();

        return $this->json($utilisateurs);
    }
}
