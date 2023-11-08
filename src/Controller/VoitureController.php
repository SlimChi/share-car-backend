<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Modeles;
use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use App\Repository\ModelesRepository;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class VoitureController extends AbstractController
{
    private $manager;
    private $utilisateur;

    public function __construct(EntityManagerInterface $manager, UtilisateurRepository $utilisateur, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $this->manager=$manager;
        $this->utilisateur=$utilisateur;
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    #[Route('/api/modeles', name: 'app_voiture', methods: ['GET'])]
    public function voitureModeles(Request $request, JWTTokenManagerInterface $jwtManager, TokenStorageInterface $tokenStorage, UtilisateurRepository $utilisateur, ModelesRepository $modeles): Response
    {
       $modeles = $modeles->findAll();

       return $this->json($modeles);
    }
}