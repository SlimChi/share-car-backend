<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ConnexionController extends AbstractController
{
    private $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    #[Route('/connexion', name: 'app_connexion', methods: ['POST'])]
    public function connexion(Request $request, UtilisateurRepository $utilisateurRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $mot_de_passe = $data['mot_de_passe'];

        $utilisateur = $utilisateurRepository->findOneByEmail($email);
        
        if (!$utilisateur || !password_verify($mot_de_passe, $utilisateur->getPassword())) {
            return $this->json(['message' => 'Email ou mot de passe invalide'], 403);
        }

        // Ici, nous devons créer un tableau associatif pour les données du payload.
        $payload = [
            'id' => $utilisateur->getId(),
            'email' => $utilisateur->getEmail(),
            'roles' => $utilisateur->getRoles(),
        ];

        // Créez le jeton JWT avec les données du payload.
        $token = $this->jwtManager->create($utilisateur, $payload);

        return $this->json(['token' => $token]);
    }

   
}